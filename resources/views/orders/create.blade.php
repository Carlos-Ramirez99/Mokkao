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
        <label>Método de pago
            <select name="metodo_pago" required>
                <option value="tarjeta" @selected(old('metodo_pago') === 'tarjeta')>Tarjeta</option>
                <option value="bizum" @selected(old('metodo_pago') === 'bizum')>Bizum</option>
                <option value="paypal" @selected(old('metodo_pago') === 'paypal')>PayPal</option>
                <option value="efectivo" @selected(old('metodo_pago') === 'efectivo')>Efectivo en tienda</option>
            </select>
        </label>
        <p class="payment-note">No almacenamos datos de tarjeta. En este sprint el pago online queda simulado de forma segura.</p>
        <button type="submit">Confirmar pedido</button>
    </form>
</section>
@endsection
