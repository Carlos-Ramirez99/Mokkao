@extends('layouts.app')
@section('body_class', 'admin-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <main class="admin-main">
        <div class="admin-heading">
            <div>
                <h2>Cupones</h2>
                <p>Gestiona codigos de descuento para la web.</p>
            </div>
            <a class="button" href="{{ route('admin.coupons.create') }}">Crear cupon</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cupones as $cupon)
                        <tr>
                            <td>{{ $cupon->codigo }}</td>
                            <td>{{ $cupon->tipo === 'porcentaje' ? 'Porcentaje' : 'Importe fijo' }}</td>
                            <td>{{ $cupon->tipo === 'porcentaje' ? number_format($cupon->valor, 2).'%' : number_format($cupon->valor, 2).' €' }}</td>
                            <td><span class="badge {{ $cupon->activo ? 'paid' : 'pending' }}">{{ $cupon->activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="actions">
                                <a href="{{ route('admin.coupons.edit', $cupon) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $cupon) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No hay cupones creados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</section>
@endsection
