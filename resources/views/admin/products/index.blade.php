@extends('layouts.app')

@section('content')
<p class="eyebrow">Catálogo</p>
<div class="title-row">
    <h1>Productos</h1>
    <a class="button" href="{{ route('admin.products.create') }}">Nuevo producto</a>
</div>

<section class="inline-card">
    <h2>Nueva categoría</h2>
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <label>Nombre
            <input name="nombre" required>
        </label>
        <label>Descripción
            <input name="descripcion">
        </label>
        <button type="submit">Crear categoría</button>
    </form>
</section>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Disponibilidad</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>{{ number_format($producto->precio, 2) }} €</td>
                    <td>{{ $producto->disponible ? 'Disponible' : 'Oculto' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.products.edit', $producto) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $producto) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-button">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
