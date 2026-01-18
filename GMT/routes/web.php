<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/materiels/{materiel}/json', [MaterielController::class, 'json'])
    ->name('materiels.json');

Route::get('/test-tailwind', function () {
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

Route::resource('categories', CategorieController::class)->except(['create', 'edit']);
Route::get('/categories/create', [CategorieController::class, 'create'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('categories.create');

Route::get('/categories/{categorie}/edit', [CategorieController::class, 'edit'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('categories.edit');

Route::resource('materiels', MaterielController::class)->middleware('auth');


Route::middleware(['auth'])->group(function () {
    // Route pour JSON (doit être avant resource)
    Route::get('/reservations/{reservation}/json', [ReservationController::class, 'showJson'])
        ->name('reservations.show.json');

    // Route resource complète (INCLUT update)
    Route::resource('reservations', ReservationController::class)->except(['edit']);

    // Actions supplémentaires
    Route::post('/reservations/{reservation}/valider', [ReservationController::class, 'valider'])
        ->name('reservations.valider');

    Route::post('/reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])
        ->name('reservations.annuler');

    Route::post('/reservations/{reservation}/checkout', [ReservationController::class, 'checkout'])
        ->name('reservations.checkout');

    Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin'])
        ->name('reservations.checkin');

    Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailabilityAjax'])
        ->name('reservations.check.availability');
});

// Routes admin (optionnel - si vous voulez une interface admin séparée)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('reservations.')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])
        ->name('admin');

    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'adminEdit'])
        ->name('admin.edit');

    Route::put('/reservations/{reservation}', [ReservationController::class, 'adminUpdate'])
        ->name('admin.update');
});
Route::get('/reservations/{reservation}/json', [ReservationController::class, 'json'])
    ->middleware('auth');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
    ->name('reservations.destroy');

require __DIR__ . '/auth.php';
