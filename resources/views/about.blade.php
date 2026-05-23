@extends('layouts.app')
@section('body_class', 'about-page')

@section('content')
<section class="about-hero">
    <img
        class="about-hero-image"
        src="{{ asset('appearance/about/img/dae52202783eaf8b19f34fc98c88513fcb092203.png') }}"
        alt="Interior de Mokkao"
    >
    <div class="about-hero-copy">
        <p>Hey, esto es <em>Mokkao</em></p>
        <h1>Conoce nuestra historia y experiencia.</h1>
    </div>
    <aside class="about-hero-note">
        <strong>Marca número 1 en España actual en cafés y tés.</strong>
        <span>Con el mejor café espresso arábica y filtrado.</span>
    </aside>
</section>

<section class="about-intro">
    <p class="about-breadcrumb"><a href="{{ route('home') }}">Home</a> / <strong>About</strong></p>
    <div class="about-intro-grid">
        <div>
            <span>¿Qué es Mokkao?</span>
            <h2>Mokkao es velocidad, juventud y calidad.</h2>
        </div>
        <p>
            Mokkao, una marca de café de especialidad centrada en los jóvenes y su estilo de vida rápido y práctico.
            En Mokkao nos especializamos en café <em>Espresso Arábica</em> y <em>filtrado</em>.
        </p>
    </div>
    <div class="about-gallery">
        <img src="{{ asset('appearance/about/img/455a568c105ee6e3ebb9171cc534085845132650.png') }}" alt="Cafetería Mokkao">
        <img src="{{ asset('appearance/about/img/d3130c83947bed1490cfe21b8b4b7cdf3fcaed8f.png') }}" alt="Preparación de café">
        <img src="{{ asset('appearance/about/img/4710efe6c8b5e284667389ae8d883e46323fdd6a.png') }}" alt="Café de especialidad">
    </div>
</section>

<section class="about-coffee-section">
    <div class="about-coffee-grid">
        <article>
            <h2>Espresso Arábica</h2>
            <p>El espresso Arábica es un café muy concentrado, obtenido forzando agua caliente a través de granos finamente molidos con alta presión. Es rico, espeso y tiene una crema dorada en la parte superior.</p>
        </article>
        <article class="featured">
            <h2>Café filtrado</h2>
            <p>El café filtrado consiste en verter agua caliente sobre café molido en un filtro. El resultado es una taza suave, clara y limpia, perfecta para apreciar matices y aromas.</p>
        </article>
        <article>
            <h2>Ritmo Mokkao</h2>
            <p>Una base intensa para cappuccino, latte y bebidas rápidas sin renunciar a calidad. Mokkao une técnica, selección de grano y ritmo urbano.</p>
        </article>
    </div>
</section>
@endsection
