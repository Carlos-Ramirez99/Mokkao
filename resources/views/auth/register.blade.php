@extends('layouts.app')

@section('content')
<section class="auth-card">
    <p class="eyebrow">Nuevo cliente</p>
    <h1>Crea tu cuenta</h1>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <label>Nombre
            <input name="nombre" value="{{ old('nombre') }}" required>
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>Teléfono
            <input name="telefono" value="{{ old('telefono') }}">
        </label>
        <label>Contraseña
            <input type="password" name="contrasena" required>
        </label>
        <label>Confirmar contraseña
            <input type="password" name="contrasena_confirmation" required>
        </label>
        <button type="submit">Registrarme</button>
    </form>
</section>
@endsection
