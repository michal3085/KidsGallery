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

Route::get('/', function () {
    return view('index');
});

// Routes for pictures
Route::resource('pictures', PicturesController::class);
Route::post('/pictures/newlike/{id?}', [PicturesController::class, 'like'])->name('pictures.newlike');

// Routes for Users
Route::resource('user', UsersController::class);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
