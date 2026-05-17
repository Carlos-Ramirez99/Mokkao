@extends('layouts.app')
@section('body_class', 'login-page')

@section('content')
<section class="auth-shell">
    <div class="auth-main">
        <div class="auth-card">
            <h1>Inicio de sesión</h1>
            <p>Inicia sesión con tu email y contraseña</p>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Introduce tu email" required>
                <input type="password" name="contrasena" placeholder="Introduce tu contraseña" required>
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>
    </div>
    <aside class="auth-side">
        <div>
            <h2>Hola Mundo</h2>
            <p>Regístrate ahora y disfruta</p>
            <a class="light-button" href="{{ route('register') }}">Regístrate</a>
        </div>
    </aside>
</section>
@endsection
