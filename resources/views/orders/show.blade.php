@extends('layouts.app')

@section('content')
<p class="eyebrow">Estado actual</p>
<h1>Pedido #{{ $pedido->id_pedido }}</h1>

<section class="order-detail">
    <p><strong>Estado:</strong> {{ str_replace('_', ' ', $pedido->estado) }}</p>
    <p><strong>Recogida:</strong> {{ $pedido->fecha->format('d/m/Y') }} a las {{ substr($pedido->hora_recogida, 0, 5) }}</p>
    <p><strong>Sucursal:</strong> {{ $pedido->sucursal->nombre }} — {{ $pedido->sucursal->direccion }}</p>
    <h2>Productos</h2>
    <ul>
        @foreach ($pedido->detalles as $detalle)
            <li>
                {{ $detalle->cantidad }} × {{ $detalle->producto->nombre }}
                — {{ number_format($detalle->precio_unitario, 2) }} €
                @if ($detalle->notas)
                    <small>({{ $detalle->notas }})</small>
                @endif
            </li>
        @endforeach
    </ul>
    <strong>Total: {{ number_format($pedido->total, 2) }} €</strong>
</section>
@endsection
