<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\ResourceTypeController;
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

Route::namespace('locations')
    /*->middleware('')*/
    ->name('locations')
    ->prefix('locations')
    ->as('locations.')
    ->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::post('/', [LocationController::class, 'store'])->name('store');
        Route::get('/{id}', [LocationController::class, 'show'])->name('show');
        Route::put('/{id}', [LocationController::class, 'update'])->name('update');
        Route::delete('/{id}', [LocationController::class, 'destroy'])->name('destroy');
    });

Route::apiResource('resources', ResourceTypeController::class);
//Route::resource('events', EventController::class);
