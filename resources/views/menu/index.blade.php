@extends('layouts.app')
@section('body_class', 'shop-page')

@section('content')
<section class="shop-shell">
    <p>Home/Shop</p>
    <div class="products">
        @foreach ($categorias->flatMap->productos as $producto)
            <article class="shop-card">
                <div class="card-top">
                    <strong>{{ number_format($producto->precio, 2) }} €</strong>
                </div>
                <h2>{{ $producto->nombre }}</h2>
                <p>{{ $producto->descripcion }}</p>
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                    <input type="number" name="cantidad" min="1" max="20" value="1">
                    <textarea name="notas" rows="2" placeholder="Notas"></textarea>
                    <button type="submit">Añadir al carrito</button>
                </form>
                <img src="{{ asset('appearance/shop/img/9bc1cf1e0ed4866dbe4463f6751acd52b86d4b4f.png') }}" alt="{{ $producto->nombre }}">
            </article>
        @endforeach
    </div>
</section>
@endsection
