<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'estado' => ['nullable', 'in:pendiente,pagado,rechazado,cancelado'],
            'metodo_pago' => ['nullable', 'in:tarjeta,efectivo,paypal,bizum'],
        ]);

        $query = Pago::with(['pedido.usuario', 'pedido.sucursal'])
            ->when($filters['desde'] ?? null, fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($filters['hasta'] ?? null, fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->when($filters['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->when($filters['metodo_pago'] ?? null, fn ($query, $metodo) => $query->where('metodo_pago', $metodo));

        $pagos = $query
            ->latest('id_pago')
            ->get();

        $totalPagado = $pagos->where('estado', 'pagado')->sum('monto');
        $totalPendiente = $pagos->where('estado', 'pendiente')->sum('monto');

        return view('admin.sales.index', [
            'pagos' => $pagos,
            'filters' => $filters,
            'totalPagado' => $totalPagado,
            'totalPendiente' => $totalPendiente,
            'pagosPendientes' => $pagos->where('estado', 'pendiente')->count(),
            'ventasConfirmadas' => $pagos->where('estado', 'pagado')->count(),
            'ticketMedio' => $pagos->where('estado', 'pagado')->count() > 0
                ? $totalPagado / $pagos->where('estado', 'pagado')->count()
                : 0,
            'porMetodo' => $pagos->groupBy('metodo_pago')->map(fn ($items) => [
                'total' => $items->where('estado', 'pagado')->sum('monto'),
                'count' => $items->count(),
            ]),
        ]);
    }
}
