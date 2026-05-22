<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Mokkao' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/cart.js') }}" defer></script>
</head>
<body class="@yield('body_class', 'default-page') {{ session('cart_preview') ? 'cart-preview-open' : '' }}">
    @include('partials.header')
    @include('partials.cart-preview')
    <div class="shipping" aria-label="Envíos nacionales gratis en pedidos superiores a 39€ (Península)">
        <div class="shipping-track" aria-hidden="true">
            <span>Envíos nacionales gratis en pedidos superiores a 39€ (Península)</span>
            <span>Envíos nacionales gratis en pedidos superiores a 39€ (Península)</span>
        </div>
    </div>
    <main>
        @if (session('success'))
            <div class="flash success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>
