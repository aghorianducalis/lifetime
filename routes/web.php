<?php

use App\Http\Controllers\EventController;
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

Route::namespace('resource-types')
    /*->middleware('')*/
    ->name('resource-types')
    ->prefix('resource-types')
    ->as('resource-types.')
    ->group(function () {
        Route::get('/', [ResourceTypeController::class, 'index'])->name('index');
        Route::post('/', [ResourceTypeController::class, 'store'])->name('store');
        Route::get('/{id}', [ResourceTypeController::class, 'show'])->name('show');
        Route::put('/{id}', [ResourceTypeController::class, 'update'])->name('update');
        Route::delete('/{id}', [ResourceTypeController::class, 'destroy'])->name('destroy');
    });

Route::namespace('events')
    /*->middleware('')*/
    ->name('events')
    ->prefix('events')
    ->as('events.')
    ->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id}', [EventController::class, 'show'])->name('show');
        Route::put('/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
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
