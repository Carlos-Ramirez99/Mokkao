<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = collect(session('cart', []));
        $total = $items->sum(fn ($item) => $item['precio'] * $item['cantidad']);

        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_producto' => ['required', 'exists:productos,id_producto'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:20'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $producto = Producto::where('disponible', true)->findOrFail($validated['id_producto']);
        $cart = session('cart', []);
        $key = (string) $producto->id_producto;

        if (isset($cart[$key])) {
            $cart[$key]['cantidad'] += $validated['cantidad'];
            $cart[$key]['notas'] = $validated['notas'] ?? $cart[$key]['notas'];
        } else {
            $cart[$key] = [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio,
                'cantidad' => $validated['cantidad'],
                'notas' => $validated['notas'] ?? null,
            ];
        }

        session(['cart' => $cart]);

        return back()
            ->with('success', 'Producto añadido al carrito.')
            ->with('cart_preview', true);
    }

    public function update(Request $request, int $idProducto): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:20'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = session('cart', []);
        abort_unless(isset($cart[$idProducto]), 404);

        $cart[$idProducto]['cantidad'] = $validated['cantidad'];
        $cart[$idProducto]['notas'] = $validated['notas'] ?? null;
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            $items = collect($cart);
            $item = $cart[$idProducto];
            $subtotal = $item['precio'] * $item['cantidad'];
            $total = $items->sum(fn ($cartItem) => $cartItem['precio'] * $cartItem['cantidad']);

            return response()->json([
                'message' => 'Carrito actualizado.',
                'cart_count' => $items->sum('cantidad'),
                'item' => [
                    'id' => $item['id_producto'],
                    'quantity' => $item['cantidad'],
                    'subtotal' => $subtotal,
                    'subtotal_formatted' => number_format($subtotal, 2) . ' €',
                ],
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

        if ($request->expectsJson()) {
            $items = collect($cart);
            $total = $items->sum(fn ($item) => $item['precio'] * $item['cantidad']);

            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'cart_count' => $items->sum('cantidad'),
                'removed_item_id' => $idProducto,
                'is_empty' => $items->isEmpty(),
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
}
