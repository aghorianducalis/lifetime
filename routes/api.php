<?php

use App\Http\Controllers\CoordinateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('coordinates')
    /*->middleware('')*/
    ->name('coordinates')
    ->prefix('coordinates')
    ->as('coordinates.')
    ->group(function () {
        Route::get('/', [CoordinateController::class, 'index'])->name('index');
        Route::post('/', [CoordinateController::class, 'store'])->name('store');
        Route::get('/{id}', [CoordinateController::class, 'show'])->name('show');
        Route::put('/{id}', [CoordinateController::class, 'update'])->name('update');
        Route::delete('/{id}', [CoordinateController::class, 'destroy'])->name('destroy');
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

Route::namespace('resources')
    /*->middleware('')*/
    ->name('resources')
    ->prefix('resources')
    ->as('resources.')
    ->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        Route::post('/', [ResourceController::class, 'store'])->name('store');
        Route::get('/{id}', [ResourceController::class, 'show'])->name('show');
        Route::put('/{id}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{id}', [ResourceController::class, 'destroy'])->name('destroy');
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

Route::namespace('users')
    /*->middleware('')*/
    ->name('users')
    ->prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

//     Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//                 ->middleware('guest')
// // Route::post('/login', function(){
//     // dd('func');
// // })
//                 ->name('login');
