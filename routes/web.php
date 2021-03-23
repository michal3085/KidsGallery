<?php

use App\Http\Controllers\PicturesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PicturesController::class, 'index']);

// Routes for pictures
Route::get('/pictures/top10', [PicturesController::class, 'top'])->name('top10');
Route::post('/pictures/newlike/{id}', [PicturesController::class, 'like'])->name('pictures.newlike')->middleware('auth');
Route::get('/pictures/{id}/report', [PicturesController::class, 'report'])->name('pictures.report');
Route::put('/pictures/{id}/send/report', [PicturesController::class, 'SendReport'])->name('pictures.sendreport');
Route::resource('pictures', PicturesController::class);

// Routes for Users
Route::resource('users', UsersController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
