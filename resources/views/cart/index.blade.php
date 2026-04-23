@extends('layouts.app')

@section('content')
<section class="cart-header">
    <p class="eyebrow">Tu pedido</p>
    <h1>Carrito</h1>
</section>

@if ($items->isEmpty())
    <p>Tu carrito está vacío. El café aún no ha empezado su viaje.</p>
@else
    <div class="cart-list">
        @foreach ($items as $item)
            <article class="cart-item">
                <div>
                    <h2>{{ $item['nombre'] }}</h2>
                    <p>{{ number_format($item['precio'], 2) }} € por unidad</p>
                </div>
                <form method="POST" action="{{ route('cart.update', $item['id_producto']) }}">
                    @csrf
                    @method('PATCH')
                    <label>Cantidad
                        <input type="number" name="cantidad" min="1" max="20" value="{{ $item['cantidad'] }}">
                    </label>
                    <label>Notas
                        <input name="notas" value="{{ $item['notas'] }}">
                    </label>
                    <button type="submit">Actualizar</button>
                </form>
                <form method="POST" action="{{ route('cart.destroy', $item['id_producto']) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ghost">Eliminar</button>
                </form>
            </article>
        @endforeach
    </div>
    <aside class="summary-card">
        <strong>Total: {{ number_format($total, 2) }} €</strong>
        @auth
            <a class="button" href="{{ route('orders.create') }}">Continuar</a>
        @else
            <a class="button" href="{{ route('login') }}">Inicia sesión para pedir</a>
        @endauth
    </aside>
@endif
@endsection
