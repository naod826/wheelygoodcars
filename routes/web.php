<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CarController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {
    Route::get('my-cars', [CarController::class, 'mine'])->name('my-cars.index');
    Route::get('my-cars/create', [CarController::class, 'create'])->name('my-cars.create.step1');
    Route::post('my-cars/create', [CarController::class, 'storePlate'])->name('my-cars.create.step1.post');
    Route::get('my-cars/create/details', [CarController::class, 'createDetails'])->name('my-cars.create.step2');
    Route::post('my-cars/create/details', [CarController::class, 'storeDetails'])->name('my-cars.create.step2.post');
    Route::get('my-cars/create/tags', [CarController::class, 'createTags'])->name('my-cars.create.step3');
    Route::post('my-cars', [CarController::class, 'store'])->name('my-cars.store');
    Route::delete('my-cars/{id}', [CarController::class, 'destroy'])->name('my-cars.destroy');
});

require __DIR__.'/auth.php';
