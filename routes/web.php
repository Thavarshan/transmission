<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/home', [VehicleController::class, 'index'])
    ->middleware(['auth'])->name('home');
Route::resource('vehicles', VehicleController::class, [
    'only' => ['store', 'update', 'destroy', 'show'],
])->middleware(['auth']);

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
