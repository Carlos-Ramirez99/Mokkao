@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <header class="admin-heading">
            <div>
                <h2>Dashboard</h2>
                <p>{{ $pedidosRecientes->count() }} pedidos encontrados</p>
            </div>
            <span>Hoy: {{ $hoy }}</span>
        </header>

        <section class="admin-stats">
            <article>
                <h3>Ventas del día</h3>
                <p>{{ number_format($ventasRegistradas, 2) }}€</p>
            </article>
            <article>
                <h3>Pedidos</h3>
                <p>{{ $pedidosRecientes->count() }}</p>
            </article>
            <article>
                <h3>Clientes</h3>
                <p>{{ $clientes }}</p>
            </article>
            <article>
                <h3>Producto top</h3>
                <p>{{ $pedidosRecientes->first()?->detalles->first()?->producto->nombre ?? 'Sin datos' }}</p>
            </article>
        </section>

        <section class="admin-orders-table">
            <div class="admin-orders-head">
                <span>ID</span>
                <span>Cliente</span>
                <span>Producto</span>
                <span>Total</span>
                <span>Estado</span>
            </div>
            @forelse ($pedidosRecientes as $pedido)
                <div class="admin-order-row">
                    <span>#{{ str_pad($pedido->id_pedido, 3, '0', STR_PAD_LEFT) }}</span>
                    <span>{{ $pedido->usuario->nombre }}</span>
                    <span>{{ $pedido->detalles->first()?->producto->nombre ?? 'Sin producto' }}</span>
                    <span>{{ number_format($pedido->total, 2) }}€</span>
                    <span class="badge {{ $pedido->estado === 'recogido' ? 'paid' : 'pending' }}">
                        {{ str_replace('_', ' ', $pedido->estado) }}
                    </span>
                </div>
            @empty
                <div class="admin-order-row empty-row">
                    <span>No hay pedidos registrados.</span>
                </div>
            @endforelse
        </section>
    </div>
</section>
@endsection
