<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');

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
Route::post('/carrito/descuento', [CartController::class, 'applyDiscount'])->middleware('auth')->name('cart.discount.apply');
Route::delete('/carrito/descuento', [CartController::class, 'removeDiscount'])->name('cart.discount.remove');
Route::patch('/carrito/{idProducto}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{idProducto}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/nuevo', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pedidos/{pedido}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::resource('productos', ProductController::class)
        ->parameters(['productos' => 'producto'])
        ->except('show')
        ->names('products');
    Route::resource('cupones', CouponController::class)
        ->parameters(['cupones' => 'cupon'])
        ->except('show')
        ->names('coupons');
    Route::resource('clientes', CustomerController::class)
        ->parameters(['clientes' => 'customer'])
        ->only(['index', 'edit', 'update', 'destroy'])
        ->names('customers');
    Route::get('/pedidos', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{pedido}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/pedidos/{pedido}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::get('/ventas', [SalesController::class, 'index'])->name('sales.index');
});
