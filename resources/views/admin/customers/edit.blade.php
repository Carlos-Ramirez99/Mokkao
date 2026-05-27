@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <section class="auth-card wide-card admin-form-card">
            <p class="eyebrow">Clientes</p>
            <h1>Editar cliente</h1>
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <label>Nombre
                    <input name="nombre" value="{{ old('nombre', $customer->nombre) }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
                </label>
                <label>Teléfono
                    <input name="telefono" value="{{ old('telefono', $customer->telefono) }}">
                </label>
                <label>Rol
                    <select name="rol" required>
                        <option value="cliente" @selected(old('rol', $customer->rol) === 'cliente')>Cliente</option>
                        <option value="administrador" @selected(old('rol', $customer->rol) === 'administrador')>Administrador</option>
                    </select>
                </label>
                <button type="submit">Actualizar cliente</button>
            </form>
        </section>
    </div>
</section>
@endsection
