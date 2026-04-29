<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Mokkao' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('menu.index') }}">Mokkao</a>
        <nav>
            <a href="{{ route('menu.index') }}">Menú</a>
            <a href="{{ route('cart.index') }}">Carrito ({{ collect(session('cart', []))->sum('cantidad') }})</a>
            @auth
                <a href="{{ route('orders.index') }}">Mis pedidos</a>
                @if (auth()->user()->rol === 'administrador')
                    <a href="{{ route('admin.dashboard') }}">Administración</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">Entrar</a>
                <a href="{{ route('register') }}">Registro</a>
            @endauth
        </nav>
    </header>

    <main class="page">
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
</body>
</html>
