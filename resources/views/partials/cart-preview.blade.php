@php
    $cartPreviewItems = collect(session('cart', []));
    $cartPreviewTotal = $cartPreviewItems->sum(fn ($item) => $item['precio'] * $item['cantidad']);
    $cartPreviewCount = $cartPreviewItems->sum('cantidad');
@endphp

@if (session('cart_preview'))
    <a class="cart-preview-backdrop" href="{{ url()->full() }}" aria-label="Cerrar previsualizacion del carrito"></a>
    <aside class="cart-preview-panel" aria-label="Previsualizacion del carrito">
        <div class="cart-preview-heading">
            <div>
                <span data-cart-count-label>{{ $cartPreviewCount }} {{ $cartPreviewCount === 1 ? 'producto' : 'productos' }}</span>
                <h2>Tu carrito</h2>
            </div>
            <a href="{{ url()->full() }}" aria-label="Cerrar previsualizacion">x</a>
        </div>

        @if ($cartPreviewItems->isEmpty())
            <p class="cart-preview-empty" data-cart-empty>Tu carrito esta vacio.</p>
        @else
            <p class="cart-preview-empty" data-cart-empty hidden>Tu carrito esta vacio.</p>
            <div class="cart-preview-list" data-cart-preview-list>
                @foreach ($cartPreviewItems as $item)
                    <article class="cart-preview-item" data-cart-item="{{ $item['id_producto'] }}">
                        <img src="{{ asset('appearance/shop/img/9bc1cf1e0ed4866dbe4463f6751acd52b86d4b4f.png') }}" alt="{{ $item['nombre'] }}">
                        <div class="cart-preview-item-body">
                            <div class="cart-preview-item-top">
                                <h3>{{ $item['nombre'] }}</h3>
                                <form class="js-cart-remove-form" method="POST" action="{{ route('cart.destroy', $item['id_producto']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="cart_preview" value="1">
                                    <button type="submit" aria-label="Quitar {{ $item['nombre'] }} del carrito">Quitar</button>
                                </form>
                            </div>
                            <form class="cart-preview-quantity js-cart-update-form" method="POST" action="{{ route('cart.update', $item['id_producto']) }}" data-cart-item-id="{{ $item['id_producto'] }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cart_preview" value="1">
                                <input type="hidden" name="notas" value="{{ $item['notas'] ?? '' }}">
                                <label>
                                    <span>Cantidad</span>
                                    <input
                                        type="number"
                                        name="cantidad"
                                        value="{{ $item['cantidad'] }}"
                                        min="1"
                                        max="20"
                                        aria-label="Cantidad de {{ $item['nombre'] }}"
                                        data-cart-quantity-input
                                    >
                                </label>
                            </form>
                            <strong data-cart-item-subtotal>{{ number_format($item['precio'] * $item['cantidad'], 2) }} &euro;</strong>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="cart-preview-total">
                <span>Total</span>
                <strong data-cart-total>{{ number_format($cartPreviewTotal, 2) }} &euro;</strong>
            </div>
        @endif

        <a class="cart-preview-button" href="{{ route('cart.index') }}">Ver carrito</a>
        <a class="cart-preview-continue" href="{{ url()->full() }}">Seguir comprando</a>
    </aside>
@endif
