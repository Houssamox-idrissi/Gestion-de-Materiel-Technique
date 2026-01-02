<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Categ
Route::resource('categories', CategorieController::class)->middleware('auth');
//Materiel
Route::resource('materiels', MaterielController::class)->middleware('auth');

//Réservation
Route::resource('reservations', ReservationController::class)
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::post('/reservations/{reservation}/valider', [ReservationController::class, 'valider'])
        ->name('reservations.valider');

    Route::post('/reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])
        ->name('reservations.annuler');

    Route::post('/reservations/{reservation}/checkout', [ReservationController::class, 'checkout'])
        ->name('reservations.checkout');

    Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin'])
        ->name('reservations.checkin');
});

require __DIR__.'/auth.php';
