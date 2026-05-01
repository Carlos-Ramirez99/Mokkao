@extends('layouts.app')

@section('content')
<p class="eyebrow">Operaciones</p>
<div class="title-row">
    <h1>Pedidos</h1>
    <form method="GET" class="filter-form">
        <select name="estado">
            <option value="">Todos</option>
            @foreach (['pendiente', 'en_preparacion', 'listo', 'recogido', 'cancelado'] as $opcion)
                <option value="{{ $opcion }}" @selected($estado === $opcion)>{{ str_replace('_', ' ', $opcion) }}</option>
            @endforeach
        </select>
        <button type="submit">Filtrar</button>
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Recogida</th>
                <th>Sucursal</th>
                <th>Total</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $pedido)
                <tr>
                    <td>#{{ $pedido->id_pedido }}</td>
                    <td>{{ $pedido->usuario->nombre }}</td>
                    <td>{{ $pedido->fecha->format('d/m/Y') }} {{ substr($pedido->hora_recogida, 0, 5) }}</td>
                    <td>{{ $pedido->sucursal->nombre }}</td>
                    <td>{{ number_format($pedido->total, 2) }} €</td>
                    <td>{{ str_replace('_', ' ', $pedido->estado) }}</td>
                    <td><a href="{{ route('admin.orders.show', $pedido) }}">Gestionar</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay pedidos para este filtro.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
