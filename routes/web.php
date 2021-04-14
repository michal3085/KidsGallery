<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PicturesController;
use App\Http\Controllers\ProfilesController;
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

Route::get('/', [PicturesController::class, 'index'])->name('index');


// Routes for comments
Route::post('/pictures/{id}/comment', [CommentsController::class, 'store'])->name('commnents.add')
    ->middleware('auth');
Route::delete('/comments/delete/{id}', [CommentsController::class, 'destroy'])->middleware('auth');
Route::get('/comments/report/{id}', [CommentsController::class, 'report'])->middleware('auth');


// Routes for pictures
Route::get('/pictures/top10', [PicturesController::class, 'top'])->name('top10');
Route::get('/pictures/{id}/report', [PicturesController::class, 'report'])->name('pictures.report');
Route::put('/pictures/{id}/send/report', [PicturesController::class, 'SendReport'])->name('pictures.sendreport');
Route::resource('pictures', PicturesController::class);

Route::post('/newlike/{id}', [LikesController::class, 'getLike'])->name('like.new')->middleware('auth');

// Profiles routes
Route::get('/profile/{name}', [ProfilesController::class, 'index'])->name('profiles.index');
Route::get('/profile/{name}/comments', [ProfilesController::class, 'comments'])->name('profiles.comments');

// Routes for Users
Route::resource('users', UsersController::class);
Route::get('users/{id}/set/avatar/{x}', [UsersController::class, 'defaultAvatar'])->name('set.avatar')->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
