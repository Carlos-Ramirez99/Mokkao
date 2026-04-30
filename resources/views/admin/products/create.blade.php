@extends('layouts.app')

@section('content')
<section class="auth-card wide-card">
    <p class="eyebrow">Catálogo</p>
    <h1>Nuevo producto</h1>
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        @include('admin.products.form', ['producto' => null])
        <button type="submit">Guardar producto</button>
    </form>
</section>
@endsection
