<?php

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

Route::get('/', [\App\Http\Controllers\HomeController::class, "index"]);
Route::get('/configs', [\App\Http\Controllers\HomeController::class, "config"]);
Route::post('/configs', [\App\Http\Controllers\HomeController::class, "postConfig"])->name("configs");
Route::get('/geos/{code}', [\App\Http\Controllers\HomeController::class, "deleteGeo"])->name("deleteGeo");