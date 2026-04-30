<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')->orderBy('nombre')->get();

        return view('admin.products.index', compact('productos'));
    }

    public function create(): View
    {
        return view('admin.products.create', ['categorias' => Categoria::orderBy('nombre')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Producto::create($this->validated($request));

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Producto $producto): View
    {
        return view('admin.products.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $producto->update($this->validated($request));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'id_categoria' => ['required', 'exists:categorias,id_categoria'],
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'alergenos' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'disponible' => ['required', 'boolean'],
        ]);
    }
}
