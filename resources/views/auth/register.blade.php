@extends('layouts.app')
@section('body_class', 'register-page')

@section('content')
<section class="auth-shell">
    <aside class="auth-side">
        <div>
            <h2>¡Hola de nuevo!</h2>
            <p>Inicia sesión para continuar</p>
            <a class="light-button" href="{{ route('login') }}">Inicia sesión</a>
        </div>
    </aside>
    <div class="auth-main">
        <div class="auth-card">
            <h1>Crear cuenta</h1>
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" required>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Introduce tu email" required>
                <input name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono">
                <input type="password" name="contrasena" placeholder="Introduce tu contraseña" required>
                <input type="password" name="contrasena_confirmation" placeholder="Repite tu contraseña" required>
                <button type="submit">Regístrate</button>
            </form>
        </div>
    </div>
</section>
@endsection
