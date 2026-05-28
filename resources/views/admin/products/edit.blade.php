@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <section class="auth-card wide-card admin-form-card">
            <p class="eyebrow">Catálogo</p>
            <h1>Editar producto</h1>
            <form method="POST" action="{{ route('admin.products.update', $producto) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.products.form')
                <button type="submit">Actualizar producto</button>
            </form>
        </section>
    </div>
</section>
@endsection
