<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $cupones = Cupon::orderBy('codigo')->get();

        return view('admin.coupons.index', compact('cupones'));
    }

    public function create(): View
    {
        return view('admin.coupons.create', ['cupon' => new Cupon(['tipo' => 'porcentaje', 'activo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Cupon::create($this->validated($request));

        return redirect()->route('admin.coupons.index')->with('success', 'Cupon creado.');
    }

    public function edit(Cupon $cupon): View
    {
        return view('admin.coupons.edit', compact('cupon'));
    }

    public function update(Request $request, Cupon $cupon): RedirectResponse
    {
        $cupon->update($this->validated($request, $cupon));

        return redirect()->route('admin.coupons.index')->with('success', 'Cupon actualizado.');
    }

    public function destroy(Cupon $cupon): RedirectResponse
    {
        $cupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Cupon eliminado.');
    }

    private function validated(Request $request, ?Cupon $cupon = null): array
    {
        $id = $cupon?->id_cupon ?? 'NULL';

        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:cupones,codigo,'.$id.',id_cupon'],
            'tipo' => ['required', 'in:porcentaje,importe'],
            'valor' => ['required', 'numeric', 'min:0.01'],
            'activo' => ['required', 'boolean'],
        ]);

        $data['codigo'] = strtoupper(trim($data['codigo']));

        if ($data['tipo'] === 'porcentaje' && $data['valor'] > 100) {
            abort(422, 'El porcentaje no puede ser mayor de 100.');
        }

        return $data;
    }
}
