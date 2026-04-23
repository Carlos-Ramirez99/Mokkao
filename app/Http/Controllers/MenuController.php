<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::with(['productos' => fn ($query) => $query->where('disponible', true)])
            ->whereHas('productos', fn ($query) => $query->where('disponible', true))
            ->get();

        return view('menu.index', compact('categorias'));
    }
}
