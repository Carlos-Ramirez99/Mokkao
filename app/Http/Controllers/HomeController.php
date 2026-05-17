<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $productos = Producto::where('disponible', true)->take(3)->get();

        return view('home', compact('productos'));
    }
}
