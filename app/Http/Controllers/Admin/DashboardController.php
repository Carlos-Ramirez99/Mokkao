<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
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
            'ventasRegistradas' => Pedido::whereNot('estado', 'cancelado')->sum('total'),
            'pedidosRecientes' => Pedido::with(['usuario', 'detalles.producto'])->latest('id_pedido')->take(5)->get(),
            'clientes' => Pedido::distinct('id_usuario')->count('id_usuario'),
            'hoy' => Carbon::now()->locale('es')->translatedFormat('j M'),
        ]);
    }
}
