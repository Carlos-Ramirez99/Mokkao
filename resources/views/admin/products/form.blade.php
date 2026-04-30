<label>Categoría
    <select name="id_categoria" required>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->id_categoria }}" @selected(old('id_categoria', $producto->id_categoria ?? null) == $categoria->id_categoria)>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>
</label>
<label>Nombre
    <input name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" required>
</label>
<label>Descripción
    <textarea name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
</label>
<label>Alérgenos
    <input name="alergenos" value="{{ old('alergenos', $producto->alergenos ?? '') }}">
</label>
<label>Precio
    <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio', $producto->precio ?? '') }}" required>
</label>
<label>Disponibilidad
    <select name="disponible" required>
        <option value="1" @selected(old('disponible', $producto->disponible ?? true) == 1)>Disponible</option>
        <option value="0" @selected(old('disponible', $producto->disponible ?? true) == 0)>Oculto</option>
    </select>
</label>
