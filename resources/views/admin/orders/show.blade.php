@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <p class="eyebrow">Operaciones</p>
        <h1>Pedido #{{ $pedido->id_pedido }}</h1>

        <section class="split-grid">
            <article class="order-detail">
                <p><strong>Cliente:</strong> {{ $pedido->usuario->nombre }} · {{ $pedido->usuario->email }}</p>
                <p><strong>Recogida:</strong> {{ $pedido->fecha->format('d/m/Y') }} a las {{ substr($pedido->hora_recogida, 0, 5) }}</p>
                <p><strong>Sucursal:</strong> {{ $pedido->sucursal->nombre }}</p>
                <h2>Productos</h2>
                <ul>
                    @foreach ($pedido->detalles as $detalle)
                        <li>{{ $detalle->cantidad }} × {{ $detalle->producto->nombre }} — {{ number_format($detalle->precio_unitario, 2) }} €</li>
                    @endforeach
                </ul>
                <strong>Total: {{ number_format($pedido->total, 2) }} €</strong>
            </article>

            <article class="inline-card">
                <h2>Actualizar estado</h2>
                <form method="POST" action="{{ route('admin.orders.update', $pedido) }}">
                    @csrf
                    @method('PATCH')
                    <label>Estado
                        <select name="estado" required>
                            @foreach (['pendiente', 'en_preparacion', 'listo', 'recogido', 'cancelado'] as $opcion)
                                <option value="{{ $opcion }}" @selected($pedido->estado === $opcion)>{{ str_replace('_', ' ', $opcion) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit">Guardar estado</button>
                </form>
            </article>
        </section>
    </div>
</section>
@endsection
