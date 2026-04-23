@extends('layouts.app')

@section('content')
<section class="auth-card">
    <p class="eyebrow">Último paso</p>
    <h1>Confirmar pedido</h1>
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <label>Sucursal
            <select name="id_sucursal" required>
                @foreach ($sucursales as $sucursal)
                    <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre }} — {{ $sucursal->direccion }}</option>
                @endforeach
            </select>
        </label>
        <label>Fecha de recogida
            <input type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required>
        </label>
        <label>Hora de recogida
            <input type="time" name="hora_recogida" value="{{ old('hora_recogida', now()->addMinutes(30)->format('H:i')) }}" required>
        </label>
        <button type="submit">Confirmar pedido</button>
    </form>
</section>
@endsection
