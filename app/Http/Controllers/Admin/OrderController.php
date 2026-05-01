<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $estado = $request->string('estado')->toString();

        $pedidos = Pedido::with(['usuario', 'sucursal'])
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->latest('id_pedido')
            ->get();

        return view('admin.orders.index', compact('pedidos', 'estado'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['usuario', 'sucursal', 'detalles.producto']);

        return view('admin.orders.show', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:pendiente,en_preparacion,listo,recogido,cancelado'],
        ]);

        $pedido->update($validated);

        return back()->with('success', 'Estado del pedido actualizado.');
    }
}
