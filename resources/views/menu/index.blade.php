@extends('layouts.app')

@section('content')
<section class="hero">
    <p class="eyebrow">Take Away</p>
    <h1>Pide ahora. Recoge sin esperar.</h1>
    <p>Elige tu café, ajusta los detalles y déjalo preparado para cuando llegues.</p>
</section>

@forelse ($categorias as $categoria)
    <section class="menu-section">
        <h2>{{ $categoria->nombre }}</h2>
        <div class="product-grid">
            @foreach ($categoria->productos as $producto)
                <article class="product-card">
                    <div>
                        <h3>{{ $producto->nombre }}</h3>
                        <p>{{ $producto->descripcion }}</p>
                        @if ($producto->alergenos)
                            <small>Alérgenos: {{ $producto->alergenos }}</small>
                        @endif
                    </div>
                    <strong>{{ number_format($producto->precio, 2) }} €</strong>
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                        <label>Cantidad
                            <input type="number" name="cantidad" min="1" max="20" value="1">
                        </label>
                        <label>Notas
                            <textarea name="notas" rows="2" placeholder="Sin azúcar, leche vegetal..."></textarea>
                        </label>
                        <button type="submit">Añadir</button>
                    </form>
                </article>
            @endforeach
        </div>
    </section>
@empty
    <p>No hay productos disponibles en este momento.</p>
@endforelse
@endsection
