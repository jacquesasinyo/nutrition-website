<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/foods', function () {
        return view('index');
    })->name('foods.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/foods/create', [FoodController::class, 'create'])->name('food.create');
    Route::post('/foods', [FoodController::class, 'store'])->name('food.store');
    Route::get('/foods/{food}/edit', [FoodController::class, 'edit'])->name('food.edit');
    Route::put('/foods/{food}', [FoodController::class, 'update'])->name('food.update');
    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])->name('food.destroy');
});

Route::get('/food/{food}', [FoodController::class, 'show'])->name('food.show');
Route::get('/foods', [FoodController::class, 'index'])->name('food.index');

