@extends('layouts.app')

@section('content')
<section class="content-shell">
    <p class="eyebrow">Cuenta</p>
    <h1>Mi perfil</h1>
    <article class="panel profile-card">
        <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
        <p><strong>Email:</strong> {{ $usuario->email }}</p>
        <p><strong>Teléfono:</strong> {{ $usuario->telefono ?: 'Sin teléfono' }}</p>
        <p><strong>Rol:</strong> {{ ucfirst($usuario->rol) }}</p>
    </article>
</section>
@endsection
