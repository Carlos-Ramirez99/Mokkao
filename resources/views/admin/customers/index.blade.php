@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <p class="eyebrow">Administración</p>
        <div class="title-row">
            <h1>Clientes</h1>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Pedidos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefono ?? 'Sin teléfono' }}</td>
                            <td>{{ ucfirst($cliente->rol) }}</td>
                            <td>{{ $cliente->pedidos_count }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.customers.edit', $cliente) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.customers.destroy', $cliente) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-button">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
