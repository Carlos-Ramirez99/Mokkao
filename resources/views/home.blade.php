@extends('layouts.app')
@section('body_class', 'home-page')

@section('content')
<section class="home-hero" id="about">
    <div>
        <h1>Brew at the moment, Brew different.</h1>
        <p>Bienvenid@ a Mokkao, café de especialidad para personas que se mueven rápido y eligen bien.</p>
        <a class="light-button" href="{{ route('menu.index') }}">Shop</a>
    </div>
</section>

<section class="home-products">
    <div class="section-heading">
        <h2>Ahora nuestros cafés en tu casa.</h2>
        <a href="{{ route('menu.index') }}">Carta →</a>
    </div>
    <div class="showcase-grid">
        @foreach ($productos as $index => $producto)
            <article class="showcase-card {{ $index === 0 ? 'featured' : '' }}">
                <div>
                    <h3>{{ $producto->nombre }}</h3>
                    <p>{{ $producto->descripcion }}</p>
                </div>
                <strong>{{ number_format($producto->precio, 2) }} €</strong>
                <img src="{{ asset('appearance/home/img/9bc1cf1e0ed4866dbe4463f6751acd52b86d4b4f.png') }}" alt="{{ $producto->nombre }}">
            </article>
        @endforeach
    </div>
</section>

<section class="statement">
    <img src="{{ asset('appearance/home/img/561adc360b6cd90841c11f4edc1619ca9c7cd47e.png') }}" alt="Café Mokkao">
    <div>
        <p>Shop Shop Shop Shop Shop Shop</p>
        <a href="{{ route('menu.index') }}">Ver más</a>
    </div>
</section>
@endsection
