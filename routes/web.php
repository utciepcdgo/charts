<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('auth:sanctum', config('jetstream.auth_session'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\ChartController::class, 'index'])->name('dashboard');
    /*    Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');*/
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->prefix('stats')->group(function () {
    Route::get('/durango', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.durango');
    Route::get('/santiagopapasquiaro', [App\Http\Controllers\Stats\Dip\SantiagoPapasquiaroController::class, 'index'])->name('stats.santiago');
    Route::get('/guadalupevictoria', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.guadalupe');
    Route::get('/mapimi', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.mapimi');
    Route::get('/gomezpalacio', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.gomez');
    Route::get('/lerdo', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.lerdo');
    Route::get('/purblonuevo', [App\Http\Controllers\Stats\Dip\DurangoController::class, 'index'])->name('stats.pueblonuevo');
});
