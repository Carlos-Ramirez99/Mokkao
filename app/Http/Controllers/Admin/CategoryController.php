<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        Categoria::create($request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Categoría creada.');
    }
}
