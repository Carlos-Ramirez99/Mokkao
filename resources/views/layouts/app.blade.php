<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Mokkao' }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20260523" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=20260523" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20260523">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/cart.js') }}" defer></script>
</head>
<body class="@yield('body_class', 'default-page') {{ session('cart_preview') ? 'cart-preview-open' : '' }}">
    @include('partials.header')
    @include('partials.cart-preview')
    @php
        $promoMessage = auth()->check()
            ? 'Hola, '.auth()->user()->nombre.'. Tu cafe te esta esperando.'
            : 'Crea tu cuenta en Mokkao y pide tu cafe para recoger sin esperas.';
        $discountMessage = 'Inauguracion web: usa el codigo MOKKAO10 y disfruta un 10% de descuento.';
        $bannerMessage = $promoMessage.' - '.$discountMessage;
    @endphp
    <div class="shipping" aria-label="{{ $bannerMessage }}">
        <div class="shipping-track" aria-hidden="true">
            <span>
                <span class="shipping-greeting">{{ $promoMessage }}</span>
                <span class="shipping-code">{{ $discountMessage }}</span>
            </span>
            <span>
                <span class="shipping-greeting">{{ $promoMessage }}</span>
                <span class="shipping-code">{{ $discountMessage }}</span>
            </span>
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
