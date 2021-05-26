<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ModeratorsController;
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

// Routes for pictures
Route::get('/pictures/top10', [PicturesController::class, 'top'])->name('top10');
Route::get('/pictures/topviews', [PicturesController::class, 'topviews'])->name('top10.views');
Route::put('/pictures/{id}/send/report', [PicturesController::class, 'SendReport'])->name('pictures.sendreport');
Route::get('/pictures/search/', [PicturesController::class, 'search'])->name('picture.search');
Route::resource('pictures', PicturesController::class);


Route::middleware('auth')->group(function () {
    // Routes for comments
    Route::post('/pictures/{id}/comment', [CommentsController::class, 'store'])->name('commnents.add');
    Route::delete('/comments/delete/{id}', [CommentsController::class, 'destroy']);
    Route::get('/comments/report/{id}', [CommentsController::class, 'report']);

    // Likes
    Route::post('/newlike/{id}', [LikesController::class, 'getLike'])->name('like.new');

    // pictures routes
    Route::get('/pictures/{id}/report', [PicturesController::class, 'report'])->name('pictures.report');
    Route::get('/pictures/yours/gallery', [PicturesController::class, 'yoursgallery'])->name('yours.gallery');

    // Profiles routes
    Route::get('/profile/{name}', [ProfilesController::class, 'about'])->name('profiles.about');
    Route::get('/profile/{name}/gallery', [ProfilesController::class, 'index'])->name('profiles.gallery');
    Route::get('/profile/{name}/comments', [ProfilesController::class, 'comments'])->name('profiles.comments');
    Route::get('/profile/{name}/favorites', [ProfilesController::class, 'favorites'])->name('profiles.favorites');
    Route::get('/profile/{name}/following', [ProfilesController::class, 'following'])->name('profiles.following');
    Route::get('/profile/{name}/followers', [ProfilesController::class, 'followers'])->name('profiles.followers');
    Route::post('/followers/add/{id}', [FollowersController::class, 'addFollower'])->name('profiles.addfollowers');
    Route::delete('/followers/delete/{id}', [FollowersController::class, 'deleteFollower'])->name('profiles.delfollowers');
    Route::get('/profile/{name}/info', [ProfilesController::class, 'info'])->name('profiles.info');
    Route::get('/profiles/banned/{picture_id}/{mod_info}/{name}', [ProfilesController::class, 'bannedPictureShow'])->name('banned.picture');
    Route::post('/profiles/banned/picture/answer', [ProfilesController::class, 'banAnswer'])->name('banned.answer');
    Route::post('/followers/add/rights/{id}', [FollowersController::class, 'addRights']);
    Route::delete('/followers/delete/rights/{id}', [FollowersController::class, 'deleteRights']);
    Route::post('/profile/block/{id}', [UsersController::class, 'block']);
    Route::post('/profile/unblock/{id}', [UsersController::class, 'unblock']);

    //Messages routes
    Route::get('/messages/', [MessagesController::class, 'index'])->name('messages.list');
    Route::get('/messages/show/{to}/{id?}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/message/send', [MessagesController::class, 'send'])->name('send.message');
    Route::get('/messages/unread', [MessagesController::class, 'unreadIndex'])->name('unread.messages');
    Route::get('/messages/search/', [MessagesController::class, 'search'])->name('search.messages');
});

// Moderator routes
Route::middleware(['check.role:moderator'])->group(function () {
    Route::get('/moderator/gallery', [ModeratorsController::class, 'index'])->name('moderator.index');
    Route::get('/moderator/gallery/blocked', [ModeratorsController::class, 'showBlocked'])->name('show.blocked');
    Route::post('/moderator/picture/block/{id}', [ModeratorsController::class, 'blockPicture'])->name('block.picture');
    Route::post('/moderator/picture/unblock/{id}', [ModeratorsController::class, 'unblockPicture'])->name('unblock.picture');
    Route::get('/moderator/reported/pictures/{id?}', [ModeratorsController::class, 'showReportedPictures'])->name('reported.pictures');
    Route::delete('/report/down/{id}', [ModeratorsController::class, 'reportDown']);
    Route::delete('/report/down/all/{id}', [ModeratorsController::class, 'reportDownAll']);
    Route::get('moderator/reported/comments/{id?}', [ModeratorsController::class, 'reportedComments'])->name('reported.comments');
    Route::delete('/moderator/delete/comment/report/{id}', [ModeratorsController::class, 'deleteCommentReport']);
});

// Routes for Users
Route::get('users/{id}/set/avatar/{x}', [UsersController::class, 'defaultAvatar'])->name('set.avatar')->middleware('auth');
Route::get('users/{id}/info', [UsersController::class, 'userinfo'])->name('user.info')->middleware('auth');
Route::post('users/{id}/info/save', [UsersController::class, 'aboutsave'])->name('user.aboutsave')->middleware('auth');
Route::get('users/search/{name}', [UsersController::class, 'search'])->name('users.search')->middleware('auth');
Route::resource('users', UsersController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
