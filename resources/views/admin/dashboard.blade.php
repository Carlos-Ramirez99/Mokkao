@extends('layouts.app')

@section('content')
<p class="eyebrow">Administración</p>
<h1>Panel de control</h1>

<section class="metric-grid">
    <article class="metric-card">
        <span>Productos disponibles</span>
        <strong>{{ $productosActivos }}</strong>
    </article>
    <article class="metric-card">
        <span>Pedidos en curso</span>
        <strong>{{ $pedidosPendientes }}</strong>
    </article>
    <article class="metric-card">
        <span>Pedidos listos</span>
        <strong>{{ $pedidosListos }}</strong>
    </article>
    <article class="metric-card">
        <span>Ventas registradas</span>
        <strong>{{ number_format($ventasRegistradas, 2) }} €</strong>
    </article>
</section>

<section class="admin-links">
    <a class="button" href="{{ route('admin.products.index') }}">Gestionar productos</a>
    <a class="button" href="{{ route('admin.orders.index') }}">Gestionar pedidos</a>
</section>
@endsection
