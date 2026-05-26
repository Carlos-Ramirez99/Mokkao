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
        @php($tamanoLabels = ['pequeno' => 'Pequeño', 'mediano' => 'Mediano', 'grande' => 'Grande'])

        <section class="cart-product-list">
            @foreach ($items as $item)
                <article class="cart-product" data-cart-item="{{ $item['id_producto'] }}">
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
                        @if ($item['es_bebida'] ?? false)
                            <span>Tamaño: {{ $tamanoLabels[$item['tamano'] ?? 'mediano'] ?? 'Mediano' }}</span>
                        @endif
                        <span>{{ $item['notas'] ?: 'Sin personalizaciones añadidas' }}</span>
                    </div>

                    <div class="cart-product-meta">
                        <form class="cart-update-form js-cart-update-form" method="POST" action="{{ route('cart.update', $item['id_producto']) }}" data-cart-item-id="{{ $item['id_producto'] }}">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="cantidad-{{ $item['id_producto'] }}">Cantidad</label>
                            <div class="cart-quantity">
                                <button type="button" aria-label="Restar una unidad" data-cart-step="-1">−</button>
                                <input
                                    id="cantidad-{{ $item['id_producto'] }}"
                                    type="number"
                                    name="cantidad"
                                    min="1"
                                    max="20"
                                    value="{{ $item['cantidad'] }}"
                                    data-cart-quantity-input
                                >
                                <button type="button" aria-label="Sumar una unidad" data-cart-step="1">+</button>
                            </div>

                            @if ($item['es_bebida'] ?? false)
                                <label class="cart-size-label" for="tamano-{{ $item['id_producto'] }}">Tamaño</label>
                                <select id="tamano-{{ $item['id_producto'] }}" class="cart-size-select" name="tamano">
                                    <option value="pequeno" @selected(($item['tamano'] ?? 'mediano') === 'pequeno')>Pequeño</option>
                                    <option value="mediano" @selected(($item['tamano'] ?? 'mediano') === 'mediano')>Mediano</option>
                                    <option value="grande" @selected(($item['tamano'] ?? 'mediano') === 'grande')>Grande</option>
                                </select>
                            @endif

                            <label class="sr-only" for="notas-{{ $item['id_producto'] }}">Notas</label>
                            <input
                                id="notas-{{ $item['id_producto'] }}"
                                class="cart-notes-input"
                                name="notas"
                                value="{{ $item['notas'] }}"
                                placeholder="Notas o personalización"
                            >
                            <button class="cart-update-button" type="submit">Guardar cambios</button>
                        </form>

                        <strong data-cart-item-subtotal>{{ number_format($item['precio'] * $item['cantidad'], 2) }} €</strong>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="cart-discount">
            <p>¿Tienes un código de descuento? Usa <strong>MOKKAO10</strong> por la inauguración de la web.</p>
            @auth
                @if ($discountCode)
                    <div class="cart-discount-applied">
                        <span>Código aplicado: <strong>{{ $discountCode }}</strong></span>
                        <form method="POST" action="{{ route('cart.discount.remove') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Quitar</button>
                        </form>
                    </div>
                @else
                    <form class="cart-discount-row" method="POST" action="{{ route('cart.discount.apply') }}">
                        @csrf
                        <input type="text" name="codigo_descuento" placeholder="Escribe tu código" value="{{ old('codigo_descuento') }}">
                        <button type="submit">Aplicar</button>
                    </form>
                @endif
            @else
                <div class="cart-discount-row">
                    <input type="text" placeholder="Inicia sesión para usar MOKKAO10" disabled>
                    <a class="cart-discount-login" href="{{ route('login') }}">Iniciar sesión</a>
                </div>
            @endauth
        </section>

        <section class="cart-summary">
            <h2>Total del carrito</h2>

            @foreach ($items as $item)
                <div class="cart-summary-row" data-cart-summary-item="{{ $item['id_producto'] }}">
                    <span data-cart-summary-label>
                        {{ $item['nombre'] }} × {{ $item['cantidad'] }}
                        @if ($item['es_bebida'] ?? false)
                            · {{ $tamanoLabels[$item['tamano'] ?? 'mediano'] ?? 'Mediano' }}
                        @endif
                    </span>
                    <strong data-cart-summary-subtotal>{{ number_format($item['precio'] * $item['cantidad'], 2) }} €</strong>
                </div>
            @endforeach

            @if ($discount > 0)
                <div class="cart-summary-row cart-summary-discount">
                    <span>Descuento {{ $discountCode }}</span>
                    <strong data-cart-discount>-{{ number_format($discount, 2) }} €</strong>
                </div>
            @endif

            <div class="cart-summary-total">
                <span>Total</span>
                <strong data-cart-total>{{ number_format($total, 2) }} €</strong>
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
