@extends('layouts.app')
@section('body_class', 'admin-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <main class="admin-main">
        <div class="admin-heading">
            <div>
                <h2>Editar cupon</h2>
                <p>Actualiza el descuento disponible.</p>
            </div>
        </div>
        @include('admin.coupons.form', ['action' => route('admin.coupons.update', $cupon), 'method' => 'PATCH'])
    </main>
</section>
@endsection
