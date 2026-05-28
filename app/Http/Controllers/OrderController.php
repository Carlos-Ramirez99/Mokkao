<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $pedidos = auth()->user()->pedidos()->with('sucursal')->latest('id_pedido')->get();

        return view('orders.index', compact('pedidos'));
    }

    public function create(): View|RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Anade al menos un producto antes de confirmar.']);
        }

        $items = collect(session('cart', []));
        $subtotal = (float) $items->sum(fn ($item) => $item['precio'] * $item['cantidad']);
        [$discountCode, $discount] = $this->activeDiscount($subtotal);
        $total = max(0, $subtotal - $discount);
        $sucursales = Sucursal::all();

        return view('orders.create', compact('sucursales', 'items', 'subtotal', 'discount', 'discountCode', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = collect(session('cart', []));

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'El carrito esta vacio.']);
        }

        $validated = $request->validate([
            'id_sucursal' => ['required', 'exists:sucursales,id_sucursal'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_recogida' => ['required', 'date_format:H:i'],
            'metodo_pago' => ['required', 'in:tarjeta,efectivo,paypal,bizum'],
        ]);

        $pickupAt = Carbon::createFromFormat('Y-m-d H:i', $validated['fecha'].' '.$validated['hora_recogida']);
        if ($pickupAt->isPast()) {
            return back()->withErrors(['hora_recogida' => 'La hora de recogida debe ser futura.'])->withInput();
        }

        $productos = Producto::whereIn('id_producto', $cart->pluck('id_producto'))->where('disponible', true)->get()->keyBy('id_producto');

        if ($productos->count() !== $cart->count()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Algun producto ya no esta disponible.']);
        }

        $pedido = DB::transaction(function () use ($validated, $cart, $productos) {
            $subtotal = (float) $cart->sum(fn ($item) => $productos[$item['id_producto']]->precio * $item['cantidad']);
            [$discountCode, $discount, $cuponId] = $this->activeDiscount($subtotal);
            $total = max(0, $subtotal - $discount);

            $pedido = Pedido::create([
                'id_usuario' => auth()->id(),
                'id_sucursal' => $validated['id_sucursal'],
                'id_cupon' => $discount > 0 ? $cuponId : null,
                'fecha' => $validated['fecha'],
                'hora_recogida' => $validated['hora_recogida'],
                'estado' => 'pendiente',
                'codigo_descuento' => $discount > 0 ? $discountCode : null,
                'descuento' => $discount,
                'total' => $total,
            ]);

            foreach ($cart as $item) {
                $pedido->detalles()->create([
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $productos[$item['id_producto']]->precio,
                    'notas' => $this->notasConTamano($item),
                ]);
            }

            $estadoPago = $validated['metodo_pago'] === 'efectivo' ? 'pendiente' : 'pagado';

            $pedido->pago()->create([
                'metodo_pago' => $validated['metodo_pago'],
                'monto' => $total,
                'estado' => $estadoPago,
                'fecha_pago' => $estadoPago === 'pagado' ? now() : null,
            ]);

            return $pedido;
        });

        session()->forget(['cart', 'discount']);

        return redirect()->route('orders.show', $pedido)->with('success', 'Pedido confirmado. Hemos registrado el pago de forma segura.');
    }

    public function show(Pedido $pedido): View
    {
        abort_unless($pedido->id_usuario === auth()->id(), 403);

        $pedido->load(['sucursal', 'detalles.producto', 'pago']);

        return view('orders.show', compact('pedido'));
    }

    private function activeDiscount(float $subtotal): array
    {
        $code = session('discount.code');

        if (! $code) {
            return [null, 0, null];
        }

        $cupon = Cupon::where('codigo', $code)->where('activo', true)->first();

        if (! $cupon || $this->discountAlreadyUsed($cupon->codigo)) {
            session()->forget('discount');
            return [null, 0, null];
        }

        $discount = $cupon->tipo === 'porcentaje'
            ? $subtotal * ((float) $cupon->valor / 100)
            : (float) $cupon->valor;

        return [$cupon->codigo, round(min($discount, $subtotal), 2), $cupon->id_cupon];
    }

    private function discountAlreadyUsed(string $code): bool
    {
        return auth()->user()->rol !== 'administrador'
            && auth()->user()->pedidos()->where('codigo_descuento', $code)->exists();
    }

    private function notasConTamano(array $item): ?string
    {
        $notas = trim((string) ($item['notas'] ?? ''));

        if (! ($item['es_bebida'] ?? false)) {
            return $notas !== '' ? $notas : null;
        }

        $labels = ['pequeno' => 'Pequeno', 'mediano' => 'Mediano', 'grande' => 'Grande'];
        $tamano = $labels[$item['tamano'] ?? 'mediano'] ?? 'Mediano';
        $partes = ["Tamano: {$tamano}"];

        if ($notas !== '') {
            $partes[] = $notas;
        }

        return implode(' - ', $partes);
    }
}
