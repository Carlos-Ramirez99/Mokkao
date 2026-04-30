@extends('layouts.app')

@section('content')
<section class="auth-card wide-card">
    <p class="eyebrow">Catálogo</p>
    <h1>Editar producto</h1>
    <form method="POST" action="{{ route('admin.products.update', $producto) }}">
        @csrf
        @method('PUT')
        @include('admin.products.form')
        <button type="submit">Actualizar producto</button>
    </form>
</section>
@endsection
