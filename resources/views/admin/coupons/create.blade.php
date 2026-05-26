@extends('layouts.app')
@section('body_class', 'admin-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <main class="admin-main">
        <div class="admin-heading">
            <div>
                <h2>Crear cupon</h2>
                <p>Define el codigo y la cantidad de descuento.</p>
            </div>
        </div>
        @include('admin.coupons.form', ['action' => route('admin.coupons.store'), 'method' => 'POST'])
    </main>
</section>
@endsection
