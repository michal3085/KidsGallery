<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FollowersController;
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
Route::get('/pictures/topviews', [PicturesController::class, 'topviews'])->name('top10.views');
Route::get('/pictures/{id}/report', [PicturesController::class, 'report'])->name('pictures.report')->middleware('auth');
Route::put('/pictures/{id}/send/report', [PicturesController::class, 'SendReport'])->name('pictures.sendreport');
Route::get('/pictures/search/', [PicturesController::class, 'search'])->name('picture.search');
Route::resource('pictures', PicturesController::class);

//Route::get('/pictures/{picture}', [PicturesController::class, 'show'])->name('unloged.show');

Route::post('/newlike/{id}', [LikesController::class, 'getLike'])->name('like.new')->middleware('auth');

Route::middleware('auth')->group(function () {
    // Profiles routes
    Route::get('/profile/{name}', [ProfilesController::class, 'info'])->name('profiles.info');
    Route::get('/profile/{name}/gallery', [ProfilesController::class, 'index'])->name('profiles.gallery');
    Route::get('/profile/{name}/comments', [ProfilesController::class, 'comments'])->name('profiles.comments');
    Route::get('/profile/{name}/favorites', [ProfilesController::class, 'favorites'])->name('profiles.favorites');
    Route::get('/profile/{name}/following', [ProfilesController::class, 'following'])->name('profiles.following');
    Route::get('/profile/{name}/followers', [ProfilesController::class, 'followers'])->name('profiles.followers');
    Route::post('/followers/add/{id}', [FollowersController::class, 'addFollower'])->name('profiles.addfollowers');
    Route::delete('/followers/delete/{id}', [FollowersController::class, 'deleteFollower'])->name('profiles.delfollowers');
    Route::post('/followers/add/rights/{id}', [FollowersController::class, 'addRights']);
    Route::delete('/followers/delete/rights/{id}', [FollowersController::class, 'deleteRights']);
});

// Routes for Users
Route::get('users/{id}/set/avatar/{x}', [UsersController::class, 'defaultAvatar'])->name('set.avatar')->middleware('auth');
Route::get('users/{id}/info', [UsersController::class, 'userinfo'])->name('user.info')->middleware('auth');
Route::post('users/{id}/info/save', [UsersController::class, 'aboutsave'])->name('user.aboutsave')->middleware('auth');
Route::get('users/search/{name}', [UsersController::class, 'search'])->name('users.search')->middleware('auth');
Route::resource('users', UsersController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
