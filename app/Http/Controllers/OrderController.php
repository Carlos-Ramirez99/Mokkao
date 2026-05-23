<?php

namespace App\Http\Controllers;

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
        $pedidos = auth()->user()
            ->pedidos()
            ->with('sucursal')
            ->latest('id_pedido')
            ->get();

        return view('orders.index', compact('pedidos'));
    }

    public function create(): View|RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Añade al menos un producto antes de confirmar.']);
        }

        $items = collect(session('cart', []));
        $total = $items->sum(fn ($item) => $item['precio'] * $item['cantidad']);
        $sucursales = Sucursal::all();

        return view('orders.create', compact('sucursales', 'items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = collect(session('cart', []));

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'El carrito está vacío.']);
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

        $productos = Producto::whereIn('id_producto', $cart->pluck('id_producto'))
            ->where('disponible', true)
            ->get()
            ->keyBy('id_producto');

        if ($productos->count() !== $cart->count()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Algún producto ya no está disponible.']);
        }

        $pedido = DB::transaction(function () use ($validated, $cart, $productos) {
            $total = $cart->sum(fn ($item) => $productos[$item['id_producto']]->precio * $item['cantidad']);

            $pedido = Pedido::create([
                'id_usuario' => auth()->id(),
                'id_sucursal' => $validated['id_sucursal'],
                'fecha' => $validated['fecha'],
                'hora_recogida' => $validated['hora_recogida'],
                'estado' => 'pendiente',
                'total' => $total,
            ]);

            foreach ($cart as $item) {
                $pedido->detalles()->create([
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $productos[$item['id_producto']]->precio,
                    'notas' => $item['notas'],
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

        session()->forget('cart');

        return redirect()->route('orders.show', $pedido)->with('success', 'Pedido confirmado. Hemos registrado el pago de forma segura.');
    }

    public function show(Pedido $pedido): View
    {
        abort_unless($pedido->id_usuario === auth()->id(), 403);

        $pedido->load(['sucursal', 'detalles.producto', 'pago']);

        return view('orders.show', compact('pedido'));
    }
}
