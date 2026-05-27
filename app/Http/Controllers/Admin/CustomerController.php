<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $clientes = User::withCount('pedidos')
            ->where('rol', 'cliente')
            ->orderBy('nombre')
            ->get();

        return view('admin.customers.index', compact('clientes'));
    }

    public function edit(User $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer): RedirectResponse
    {
        $customer->update($request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($customer->id_usuario, 'id_usuario'),
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rol' => ['required', Rule::in(['cliente', 'administrador'])],
        ]));

        return redirect()->route('admin.customers.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(User $customer): RedirectResponse
    {
        if ($customer->is(auth()->user())) {
            return back()->withErrors(['cliente' => 'No puedes eliminar tu propia cuenta desde el panel.']);
        }

        if ($customer->pedidos()->exists()) {
            return back()->withErrors(['cliente' => 'No se puede eliminar un cliente con pedidos registrados.']);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Cliente eliminado.');
    }
}
