@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
        <section class="auth-card wide-card admin-form-card">
            <p class="eyebrow">Catálogo</p>
            <h1>Nuevo producto</h1>
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.products.form', ['producto' => null])
                <button type="submit">Guardar producto</button>
            </form>
        </section>
    </div>
</section>
@endsection
