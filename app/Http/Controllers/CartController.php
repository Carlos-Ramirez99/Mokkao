<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->normalizarCarrito(session('cart', []));
        session(['cart' => $cart]);

        $items = collect($cart);
        $subtotal = $this->subtotal($items);
        $discount = $this->activeDiscount($subtotal);
        $discountCode = session('discount.code');
        $total = max(0, $subtotal - $discount);

        return view('cart.index', compact('items', 'subtotal', 'discount', 'discountCode', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_producto' => ['required', 'exists:productos,id_producto'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:20'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $producto = Producto::with('categoria')->where('disponible', true)->findOrFail($validated['id_producto']);
        $cart = session('cart', []);
        $key = (string) $producto->id_producto;
        $esBebida = $this->esBebidaPersonalizable($producto);

        if (isset($cart[$key])) {
            $cart[$key]['cantidad'] += $validated['cantidad'];
            $cart[$key]['notas'] = $validated['notas'] ?? $cart[$key]['notas'];
            $cart[$key]['es_bebida'] = $cart[$key]['es_bebida'] ?? $esBebida;
            $cart[$key]['tamano'] = $cart[$key]['tamano'] ?? ($esBebida ? 'mediano' : null);
        } else {
            $cart[$key] = [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio,
                'cantidad' => $validated['cantidad'],
                'notas' => $validated['notas'] ?? null,
                'categoria' => $producto->categoria?->nombre,
                'es_bebida' => $esBebida,
                'tamano' => $esBebida ? 'mediano' : null,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Producto anadido al carrito.')->with('cart_preview', true);
    }

    public function update(Request $request, int $idProducto): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:20'],
            'notas' => ['nullable', 'string', 'max:500'],
            'tamano' => ['nullable', 'in:pequeno,mediano,grande'],
        ]);

        $cart = session('cart', []);
        abort_unless(isset($cart[$idProducto]), 404);

        $cart[$idProducto]['cantidad'] = $validated['cantidad'];
        $cart[$idProducto]['notas'] = $validated['notas'] ?? null;

        if ($cart[$idProducto]['es_bebida'] ?? false) {
            $cart[$idProducto]['tamano'] = $validated['tamano'] ?? 'mediano';
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            $items = collect($cart);
            $item = $cart[$idProducto];
            $subtotalItem = $item['precio'] * $item['cantidad'];
            $subtotal = $this->subtotal($items);
            $discount = $this->activeDiscount($subtotal);
            $total = max(0, $subtotal - $discount);

            return response()->json([
                'message' => 'Carrito actualizado.',
                'cart_count' => $items->sum('cantidad'),
                'item' => [
                    'id' => $item['id_producto'],
                    'quantity' => $item['cantidad'],
                    'subtotal' => $subtotalItem,
                    'subtotal_formatted' => number_format($subtotalItem, 2) . ' €',
                    'summary_label' => $this->summaryLabel($item),
                ],
                'discount' => $discount,
                'discount_formatted' => '-' . number_format($discount, 2) . ' €',
                'total' => $total,
                'total_formatted' => number_format($total, 2) . ' €',
            ]);
        }

        $response = back()->with('success', 'Carrito actualizado.');

        if ($request->boolean('cart_preview')) {
            $response->with('cart_preview', true);
        }

        return $response;
    }

    public function destroy(Request $request, int $idProducto): RedirectResponse|JsonResponse
    {
        $cart = session('cart', []);
        unset($cart[$idProducto]);
        session(['cart' => $cart]);

        if (empty($cart)) {
            session()->forget('discount');
        }

        if ($request->expectsJson()) {
            $items = collect($cart);
            $subtotal = $this->subtotal($items);
            $discount = $this->activeDiscount($subtotal);
            $total = max(0, $subtotal - $discount);

            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'cart_count' => $items->sum('cantidad'),
                'removed_item_id' => $idProducto,
                'is_empty' => $items->isEmpty(),
                'discount' => $discount,
                'discount_formatted' => '-' . number_format($discount, 2) . ' €',
                'total' => $total,
                'total_formatted' => number_format($total, 2) . ' €',
            ]);
        }

        $response = back()->with('success', 'Producto eliminado del carrito.');

        if ($request->boolean('cart_preview')) {
            $response->with('cart_preview', true);
        }

        return $response;
    }

    public function applyDiscount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_descuento' => ['required', 'string', 'max:50'],
        ]);

        if (empty(session('cart', []))) {
            return back()->withErrors(['codigo_descuento' => 'Anade productos al carrito antes de aplicar el descuento.']);
        }

        $code = strtoupper(trim($validated['codigo_descuento']));
        $cupon = Cupon::where('codigo', $code)->where('activo', true)->first();

        if (! $cupon) {
            return back()->withErrors(['codigo_descuento' => 'El codigo de descuento no es valido.']);
        }

        if ($this->discountAlreadyUsed($cupon->codigo)) {
            return back()->withErrors(['codigo_descuento' => 'Ya has usado este codigo de descuento.']);
        }

        session(['discount' => [
            'code' => $cupon->codigo,
            'type' => $cupon->tipo,
            'value' => (float) $cupon->valor,
        ]]);

        return back()->with('success', 'Descuento '.$cupon->codigo.' aplicado correctamente.');
    }

    public function removeDiscount(): RedirectResponse
    {
        session()->forget('discount');

        return back()->with('success', 'Descuento retirado del carrito.');
    }

    private function esBebidaPersonalizable(Producto $producto): bool
    {
        $categoria = mb_strtolower($producto->categoria?->nombre ?? '');

        return str_contains($categoria, 'bebida') || str_contains($categoria, 'cafe') || str_contains($categoria, 'caf');
    }

    private function normalizarCarrito(array $cart): array
    {
        $idsPendientes = collect($cart)
            ->filter(fn ($item) => ! array_key_exists('es_bebida', $item))
            ->pluck('id_producto');

        if ($idsPendientes->isEmpty()) {
            return $cart;
        }

        $productos = Producto::with('categoria')->whereIn('id_producto', $idsPendientes)->get()->keyBy('id_producto');

        foreach ($cart as $key => $item) {
            if (array_key_exists('es_bebida', $item)) {
                continue;
            }

            $producto = $productos[$item['id_producto']] ?? null;
            $esBebida = $producto ? $this->esBebidaPersonalizable($producto) : false;

            $cart[$key]['categoria'] = $producto?->categoria?->nombre;
            $cart[$key]['es_bebida'] = $esBebida;
            $cart[$key]['tamano'] = $esBebida ? 'mediano' : null;
        }

        return $cart;
    }

    private function subtotal($items): float
    {
        return (float) $items->sum(fn ($item) => $item['precio'] * $item['cantidad']);
    }

    private function activeDiscount(float $subtotal): float
    {
        $discount = session('discount');

        if (! auth()->check() || empty($discount['code'])) {
            return 0;
        }

        $cupon = Cupon::where('codigo', $discount['code'])->where('activo', true)->first();

        if (! $cupon || $this->discountAlreadyUsed($cupon->codigo)) {
            session()->forget('discount');
            return 0;
        }

        return $this->calculateDiscount($subtotal, $cupon->tipo, (float) $cupon->valor);
    }

    private function calculateDiscount(float $subtotal, string $tipo, float $valor): float
    {
        $discount = $tipo === 'porcentaje' ? $subtotal * ($valor / 100) : $valor;

        return round(min($discount, $subtotal), 2);
    }

    private function discountAlreadyUsed(string $code): bool
    {
        if (auth()->user()->rol === 'administrador') {
            return false;
        }

        return auth()->user()->pedidos()->where('codigo_descuento', $code)->exists();
    }

    private function summaryLabel(array $item): string
    {
        $label = $item['nombre'].' × '.$item['cantidad'];

        if ($item['es_bebida'] ?? false) {
            $labels = ['pequeno' => 'Pequeno', 'mediano' => 'Mediano', 'grande' => 'Grande'];
            $label .= ' · '.($labels[$item['tamano'] ?? 'mediano'] ?? 'Mediano');
        }

        return $label;
    }
}
