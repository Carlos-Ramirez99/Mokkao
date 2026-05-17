@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="dashboard-shell">
    <aside class="sidebar">
        <h1>Café Admin</h1>
        <a class="active" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.orders.index') }}">Pedidos</a>
        <a href="{{ route('admin.products.index') }}">Productos</a>
    </aside>
    <div>
        <h1>Dashboard</h1>
        <section class="metric-grid">
            <article class="metric-card"><span>Productos disponibles</span><strong>{{ $productosActivos }}</strong></article>
            <article class="metric-card"><span>Pedidos en curso</span><strong>{{ $pedidosPendientes }}</strong></article>
            <article class="metric-card"><span>Pedidos listos</span><strong>{{ $pedidosListos }}</strong></article>
            <article class="metric-card"><span>Ventas registradas</span><strong>{{ number_format($ventasRegistradas, 2) }} €</strong></article>
        </section>
    </div>
</section>
@endsection
