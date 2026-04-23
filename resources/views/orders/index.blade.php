@extends('layouts.app')

@section('content')
<p class="eyebrow">Seguimiento</p>
<h1>Mis pedidos</h1>

@forelse ($pedidos as $pedido)
    <article class="order-card">
        <div>
            <h2>Pedido #{{ $pedido->id_pedido }}</h2>
            <p>{{ $pedido->fecha->format('d/m/Y') }} · {{ substr($pedido->hora_recogida, 0, 5) }} · {{ $pedido->sucursal->nombre }}</p>
        </div>
        <span class="status">{{ str_replace('_', ' ', $pedido->estado) }}</span>
        <strong>{{ number_format($pedido->total, 2) }} €</strong>
        <a class="button" href="{{ route('orders.show', $pedido) }}">Ver detalle</a>
    </article>
@empty
    <p>Aún no tienes pedidos registrados.</p>
@endforelse
@endsection
