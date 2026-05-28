@extends('layouts.app')
@section('body_class', 'shop-page')

@section('content')
<section class="shop-shell">
    <p>Home/Carta</p>

    @foreach ($categorias as $categoria)
        <section class="menu-category" id="{{ \Illuminate\Support\Str::slug($categoria->nombre) }}">
            <div class="section-heading menu-category-heading">
                <div>
                    <span>Carta</span>
                    <h2>{{ $categoria->nombre }}</h2>
                </div>
                @if ($categoria->descripcion)
                    <p>{{ $categoria->descripcion }}</p>
                @endif
            </div>

            <div class="products">
                @foreach ($categoria->productos as $producto)
                    <article class="shop-card">
                        <div class="shop-card-head">
                            <strong>{{ number_format($producto->precio, 2) }} €</strong>
                            <form method="POST" action="{{ route('cart.store') }}">
                                @csrf
                                <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                                <input type="hidden" name="cantidad" value="1">
                                <input type="hidden" name="notas" value="">
                                <button type="submit" aria-label="Añadir {{ $producto->nombre }} al carrito">
                                    <svg viewBox="0 0 35 35"><path d="M2.98958 2.98958H5.90625L9.78542 21.1021C9.92772 21.7654 10.2968 22.3584 10.8292 22.779C11.3615 23.1995 12.0238 23.4213 12.7021 23.4062H26.9646C27.6284 23.4052 28.272 23.1777 28.789 22.7614C29.306 22.3451 29.6656 21.7649 29.8083 21.1167L32.2146 10.2813H7.46667M13.125 30.625C13.125 31.4304 12.4721 32.0833 11.6667 32.0833C10.8613 32.0833 10.2083 31.4304 10.2083 30.625C10.2083 29.8196 10.8613 29.1667 11.6667 29.1667C12.4721 29.1667 13.125 29.8196 13.125 30.625ZM29.1667 30.625C29.1667 31.4304 28.5138 32.0833 27.7083 32.0833C26.9029 32.0833 26.25 31.4304 26.25 30.625C26.25 29.8196 26.9029 29.1667 27.7083 29.1667C28.5138 29.1667 29.1667 29.8196 29.1667 30.625Z"/></svg>
                                </button>
                            </form>
                        </div>
                        <div class="shop-copy">
                            <h2>{{ $producto->nombre }}</h2>
                            <p>{{ $producto->descripcion }}</p>
                        </div>
                        <div class="shop-media">
                            <img src="{{ asset($producto->imagen ?: 'appearance/shop/img/9bc1cf1e0ed4866dbe4463f6751acd52b86d4b4f.png') }}" alt="{{ $producto->nombre }}">
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
</section>
@endsection
