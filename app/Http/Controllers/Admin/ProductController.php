<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')->orderBy('nombre')->get();
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('admin.products.index', compact('productos', 'categorias'));
    }

    public function create(): View
    {
        return view('admin.products.create', ['categorias' => Categoria::orderBy('nombre')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $this->storeImage($request);
        }

        Producto::create($validated);

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
        $validated = $this->validated($request);

        if ($request->hasFile('imagen')) {
            $this->deleteImage($producto->imagen);
            $validated['imagen'] = $this->storeImage($request);
        }

        $producto->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->deleteImage($producto->imagen);
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
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        unset($validated['imagen']);

        return $validated;
    }

    private function storeImage(Request $request): string
    {
        $file = $request->file('imagen');
        $directory = public_path('uploads/productos');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::uuid().'.'.$file->extension();
        $file->move($directory, $filename);

        return 'uploads/productos/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || ! Str::startsWith($path, 'uploads/productos/')) {
            return;
        }

        $absolutePath = public_path($path);

        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
        }
    }
}
