@extends('layouts.app')
@section('body_class', 'dashboard-page')

@section('content')
<section class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="admin-main">
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

        <section class="inline-card">
            <h2>Categorias</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Productos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->nombre }}</td>
                                <td>{{ $categoria->productos_count }}</td>
                                <td class="actions">
                                    @if ($categoria->productos_count === 0)
                                        <form method="POST" action="{{ route('admin.categories.destroy', $categoria) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="link-button">Eliminar</button>
                                        </form>
                                    @else
                                        <span>No se puede eliminar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3">No hay categorias creadas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
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
                            <td>
                                @if ($producto->imagen)
                                    <img class="table-thumb" src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}">
                                @else
                                    Sin imagen
                                @endif
                            </td>
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
    </div>
</section>
@endsection
