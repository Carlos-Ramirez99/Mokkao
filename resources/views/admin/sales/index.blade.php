@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <p class="eyebrow">Ventas</p>
        <div class="title-row">
            <div>
                <h1>Reporte de ventas</h1>
                <p class="muted-copy">Analiza ingresos, pagos pendientes y métodos usados.</p>
            </div>
            <a class="button ghost-button" href="{{ route('admin.sales.index') }}">Limpiar filtros</a>
        </div>

        <form method="GET" class="sales-filter-card">
            <label>Desde
                <input type="date" name="desde" value="{{ $filters['desde'] ?? '' }}">
            </label>
            <label>Hasta
                <input type="date" name="hasta" value="{{ $filters['hasta'] ?? '' }}">
            </label>
            <label>Estado
                <select name="estado">
                    <option value="">Todos</option>
                    @foreach (['pendiente', 'pagado', 'rechazado', 'cancelado'] as $estado)
                        <option value="{{ $estado }}" @selected(($filters['estado'] ?? '') === $estado)>{{ ucfirst($estado) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Método
                <select name="metodo_pago">
                    <option value="">Todos</option>
                    @foreach (['tarjeta', 'bizum', 'paypal', 'efectivo'] as $metodo)
                        <option value="{{ $metodo }}" @selected(($filters['metodo_pago'] ?? '') === $metodo)>{{ ucfirst($metodo) }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Filtrar</button>
        </form>

        <section class="admin-stats sales-stats">
            <article>
                <h3>Total pagado</h3>
                <p>{{ number_format($totalPagado, 2) }}€</p>
            </article>
            <article>
                <h3>Pendiente de cobro</h3>
                <p>{{ number_format($totalPendiente, 2) }}€</p>
            </article>
            <article>
                <h3>Ticket medio</h3>
                <p>{{ number_format($ticketMedio, 2) }}€</p>
            </article>
            <article>
                <h3>Pagos pendientes</h3>
                <p>{{ $pagosPendientes }}</p>
            </article>
        </section>

        <section class="method-breakdown">
            @foreach (['tarjeta', 'bizum', 'paypal', 'efectivo'] as $metodo)
                @php($data = $porMetodo->get($metodo, ['total' => 0, 'count' => 0]))
                <article>
                    <span>{{ ucfirst($metodo) }}</span>
                    <strong>{{ number_format($data['total'], 2) }}€</strong>
                    <small>{{ $data['count'] }} pagos</small>
                </article>
            @endforeach
        </section>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pago</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Sucursal</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>#{{ $pago->id_pago }}</td>
                            <td>#{{ $pago->id_pedido }}</td>
                            <td>{{ $pago->pedido->usuario->nombre }}</td>
                            <td>{{ $pago->pedido->sucursal->nombre }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            <td><span class="badge {{ $pago->estado === 'pagado' ? 'paid' : 'pending' }}">{{ ucfirst($pago->estado) }}</span></td>
                            <td>{{ $pago->fecha_pago?->format('d/m/Y H:i') ?? 'Pendiente' }}</td>
                            <td>{{ number_format($pago->monto, 2) }}€</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No hay ventas para los filtros seleccionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
