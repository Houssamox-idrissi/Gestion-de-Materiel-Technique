<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DashboardController;
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

Route::get('/test-tailwind', function() {
    return view('test');
});

Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])
->middleware(['auth', 'verified'])
->name('dashboard');

Route::get('/dashboard/etud', [DashboardController::class, 'etudiantDashboard'])
->middleware(['auth', 'verified'])
->name('etud');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Categ
Route::resource('categories', CategorieController::class)->except(['create', 'edit']);
Route::get('/categories/create', [CategorieController::class, 'create'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('categories.create');

Route::get('/categories/{categorie}/edit', [CategorieController::class, 'edit'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('categories.edit');
//Materiel
Route::resource('materiels', MaterielController::class)->middleware('auth');

//Réservation
Route::middleware(['auth'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::get('/reservations/create', [ReservationController::class, 'create'])
        ->name('reservations.create');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])
        ->name('reservations.show');

    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
        ->name('reservations.edit');

    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])
        ->name('reservations.update');

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');
});

// Admin reservations (all reservations)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reservations', [ReservationController::class, 'adminIndex'])
        ->name('reservations.admin');

    Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'adminEdit'])
        ->name('reservations.admin.edit');

    Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'adminUpdate'])
        ->name('reservations.admin.update');
});

require __DIR__.'/auth.php';
