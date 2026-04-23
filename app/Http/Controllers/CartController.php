<?php

namespace App\Http\Controllers;

use App\Models\Producto;
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

        return redirect()->route('cart.index')->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request, int $idProducto): RedirectResponse
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

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(int $idProducto): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$idProducto]);
        session(['cart' => $cart]);

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
