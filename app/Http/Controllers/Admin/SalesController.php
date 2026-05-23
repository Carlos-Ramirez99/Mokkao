<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with(['pedido.usuario', 'pedido.sucursal'])
            ->latest('id_pago')
            ->get();

        return view('admin.sales.index', [
            'pagos' => $pagos,
            'totalPagado' => $pagos->where('estado', 'pagado')->sum('monto'),
            'pagosPendientes' => $pagos->where('estado', 'pendiente')->count(),
            'ventasConfirmadas' => $pagos->where('estado', 'pagado')->count(),
        ]);
    }
}
