@extends('layouts.app')

@section('content')
<section class="auth-card">
    <p class="eyebrow">Bienvenido de nuevo</p>
    <h1>Inicia sesión</h1>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>Contraseña
            <input type="password" name="contrasena" required>
        </label>
        <button type="submit">Entrar</button>
    </form>
</section>
@endsection
