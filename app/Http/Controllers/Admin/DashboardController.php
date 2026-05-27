<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'productosActivos' => Producto::where('disponible', true)->count(),
            'pedidosPendientes' => Pedido::whereIn('estado', ['pendiente', 'en_preparacion'])->count(),
            'pedidosListos' => Pedido::where('estado', 'listo')->count(),
            'ventasRegistradas' => Pago::where('estado', 'pagado')->sum('monto'),
            'pedidosRecientes' => Pedido::with(['usuario', 'detalles.producto'])->latest('id_pedido')->take(5)->get(),
            'clientes' => User::where('rol', 'cliente')->count(),
            'hoy' => Carbon::now()->locale('es')->translatedFormat('j M'),
        ]);
    }
}
