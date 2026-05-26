<form class="panel admin-form-card" method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <label>Codigo
        <input name="codigo" value="{{ old('codigo', $cupon->codigo) }}" placeholder="MOKKAO10" required>
    </label>

    <label>Tipo de descuento
        <select name="tipo" required>
            <option value="porcentaje" @selected(old('tipo', $cupon->tipo) === 'porcentaje')>Porcentaje</option>
            <option value="importe" @selected(old('tipo', $cupon->tipo) === 'importe')>Importe fijo</option>
        </select>
    </label>

    <label>Cantidad
        <input type="number" step="0.01" min="0.01" name="valor" value="{{ old('valor', $cupon->valor) }}" required>
    </label>

    <label>Estado
        <select name="activo" required>
            <option value="1" @selected(old('activo', (int) $cupon->activo) == 1)>Activo</option>
            <option value="0" @selected(old('activo', (int) $cupon->activo) == 0)>Inactivo</option>
        </select>
    </label>

    <button class="button" type="submit">Guardar cupon</button>
</form>
