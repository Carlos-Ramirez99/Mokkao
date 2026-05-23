@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <p class="eyebrow">Ventas</p>
        <h1>Reporte de ventas</h1>

        <section class="admin-stats sales-stats">
            <article>
                <h3>Total pagado</h3>
                <p>{{ number_format($totalPagado, 2) }}€</p>
            </article>
            <article>
                <h3>Ventas confirmadas</h3>
                <p>{{ $ventasConfirmadas }}</p>
            </article>
            <article>
                <h3>Pagos pendientes</h3>
                <p>{{ $pagosPendientes }}</p>
            </article>
            <article>
                <h3>Registros de pago</h3>
                <p>{{ $pagos->count() }}</p>
            </article>
        </section>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pago</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>#{{ $pago->id_pago }}</td>
                            <td>#{{ $pago->id_pedido }}</td>
                            <td>{{ $pago->pedido->usuario->nombre }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            <td>{{ ucfirst($pago->estado) }}</td>
                            <td>{{ number_format($pago->monto, 2) }}€</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Aún no hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
