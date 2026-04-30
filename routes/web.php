<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MenuController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{idProducto}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{idProducto}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/nuevo', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pedidos/{pedido}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::resource('productos', ProductController::class)
        ->parameters(['productos' => 'producto'])
        ->except('show')
        ->names('products');
});
