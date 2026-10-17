<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Surahs;
use App\Http\Controllers\Verses;
use App\Http\Controllers\TafsirController;
use App\Http\Controllers\UserTafsirController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ReadingProgress as ReadingProgressController;
use App\Http\Controllers\Admin\TafsirVideoController;
use App\Http\Controllers\DasahboardController;
use App\Http\Controllers\Auth\GoogleController;



Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
   Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');


Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('authenticate');



Route::get('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'store'])
    ->name('users.store');



Route::get('/users/index', function () {
    return view('users.index');
});

Route::get('/users', [UserController::class, 'index'])
    ->name('users.index');


Route::get(
    '/interpreted-surahs',
    [Surahs::class, 'interpreted']
)->name('interpreted.surahs');

Route::middleware('auth')->group(function () {


   Route::get('/dashboard', [DasahboardController::class, 'index'])
    ->name('dashboard');

    Route::get('/aboutauthor', [DasahboardController::class, 'index_for_about_author'])
        ->name('aboutauthor.index_for_about_author');

    Route::resource('surahs', Surahs::class)
        ->only(['index', 'show']);



    Route::resource('verses', Verses::class)
        ->only(['index']);


   
    Route::get('/surahs-reader', [UserTafsirController::class, 'surahsReader'])
    ->name('surahs.reader');

    Route::get('/tafsir-reader', [UserTafsirController::class, 'index'])
    ->name('tafsir.reader');

  
 

    Route::get('/bookmarks', [BookmarkController::class, 'index'])
    ->name('bookmarks.index');
    

        Route::get('/bookmarks/{id}/show', [BookmarkController::class, 'show'])
    ->name('bookmarks.show');


        Route::post('/bookmarks/store', [BookmarkController::class, 'store'])
    ->name('bookmarks.store');

    Route::post( '/reading-progress',[ReadingProgressController::class, 'store'])->name('reading_progress.store');

    Route::delete('/bookmarks/destroy', [BookmarkController::class, 'destroy'])
    ->name('bookmarks.destroy');

Route::get(
    '/tafsir/surah/{surah:slug}',
    [UserTafsirController::class, 'show']
)->name('tafsir.reader.show');


    Route::prefix('admin') ->name('admin.')->group(function () {

        Route::resource('tafsir', TafsirController::class);


          Route::get(
        'tafsirs/{tafsir}/videos',
        [TafsirVideoController::class, 'index']
    )->name('tafsirs.videos.index');

    Route::get(
        'tafsirs/{tafsir}/videos/create',
        [TafsirVideoController::class, 'create']
    )->name('tafsirs.videos.create');

    Route::post(
        'tafsirs/{tafsir}/videos',
        [TafsirVideoController::class, 'store']
    )->name('tafsirs.videos.store');

    Route::get(
        'tafsir-videos/{video}/edit',
        [TafsirVideoController::class, 'edit']
    )->name('tafsirs.videos.edit');

    Route::put(
        'tafsir-videos/{video}',
        [TafsirVideoController::class, 'update']
    )->name('tafsirs.videos.update');

    Route::delete(
        'tafsir-videos/{video}',
        [TafsirVideoController::class, 'destroy']
    )->name('tafsirs.videos.destroy');

        });

        Route::post('/tafsir/bookmark/{verse}', [UserTafsirController::class, 'bookmark'])
    ->middleware('auth')
    ->name('tafsir.bookmark');


    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


  

});