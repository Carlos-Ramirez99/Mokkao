@extends('layouts.app')
@section('body_class', 'cart-view')

@section('content')
<section class="cart-shell">
    <a class="cart-back-link" href="{{ route('menu.index') }}">← Seguir comprando</a>
    <h1>Tu carrito</h1>

    @if ($items->isEmpty())
        <div class="cart-empty-state">
            <p>Tu carrito está vacío. El café aún no ha empezado su viaje.</p>
            <a class="button" href="{{ route('menu.index') }}">Ver la carta</a>
        </div>
    @else
        <section class="cart-product-list">
            @foreach ($items as $item)
                <article class="cart-product">
                    <form method="POST" action="{{ route('cart.destroy', $item['id_producto']) }}">
                        @csrf
                        @method('DELETE')
                        <button class="cart-remove" type="submit" aria-label="Eliminar {{ $item['nombre'] }}">×</button>
                    </form>

                    <img
                        src="{{ asset('appearance/shop/img/9bc1cf1e0ed4866dbe4463f6751acd52b86d4b4f.png') }}"
                        alt="{{ $item['nombre'] }}"
                    >

                    <div class="cart-product-copy">
                        <h2>{{ $item['nombre'] }}</h2>
                        <p>{{ number_format($item['precio'], 2) }} € por unidad</p>
                        <span>{{ $item['notas'] ?: 'Sin personalizaciones añadidas' }}</span>
                    </div>

                    <div class="cart-product-meta">
                        <form class="cart-update-form" method="POST" action="{{ route('cart.update', $item['id_producto']) }}">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="cantidad-{{ $item['id_producto'] }}">Cantidad</label>
                            <div class="cart-quantity">
                                <button
                                    type="button"
                                    aria-label="Restar una unidad"
                                    onclick="this.parentElement.querySelector('input').stepDown()"
                                >−</button>
                                <input
                                    id="cantidad-{{ $item['id_producto'] }}"
                                    type="number"
                                    name="cantidad"
                                    min="1"
                                    max="20"
                                    value="{{ $item['cantidad'] }}"
                                >
                                <button
                                    type="button"
                                    aria-label="Sumar una unidad"
                                    onclick="this.parentElement.querySelector('input').stepUp()"
                                >+</button>
                            </div>
                            <label class="sr-only" for="notas-{{ $item['id_producto'] }}">Notas</label>
                            <input
                                id="notas-{{ $item['id_producto'] }}"
                                class="cart-notes-input"
                                name="notas"
                                value="{{ $item['notas'] }}"
                                placeholder="Notas o personalización"
                            >
                            <button class="cart-update-button" type="submit">Actualizar</button>
                        </form>

                        <strong>{{ number_format($item['precio'] * $item['cantidad'], 2) }} €</strong>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="cart-discount">
            <p>¿Tienes un código de descuento? Escríbelo aquí.</p>
            <div class="cart-discount-row">
                <input type="text" placeholder="Escribe tu código" disabled>
                <button type="button" disabled>Aplicar</button>
            </div>
        </section>

        <section class="cart-summary">
            <h2>Total del carrito</h2>

            @foreach ($items as $item)
                <div class="cart-summary-row">
                    <span>{{ $item['nombre'] }} × {{ $item['cantidad'] }}</span>
                    <strong>{{ number_format($item['precio'] * $item['cantidad'], 2) }} €</strong>
                </div>
            @endforeach

            <div class="cart-summary-total">
                <span>Total</span>
                <strong>{{ number_format($total, 2) }} €</strong>
            </div>

            <div class="cart-summary-actions">
                @auth
                    <a class="primary" href="{{ route('orders.create') }}">Procesar pago</a>
                @else
                    <a class="primary" href="{{ route('login') }}">Inicia sesión para pedir</a>
                @endauth
                <a class="secondary" href="{{ route('menu.index') }}">← Seguir comprando</a>
            </div>
        </section>
    @endif
</section>
@endsection
