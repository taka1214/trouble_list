<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\ReplyController;
use App\Http\Controllers\User\ProfileController;

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
    return view('user.auth.login');
});

Route::middleware('auth:users')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    Route::post('/post/like/{id}', [PostController::class, 'like'])->name('posts.like');
    Route::post('/post/unlike/{id}', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::middleware(['auth:users', 'verified'])->group(function () {
    Route::get('/replies', [ReplyController::class, 'index'])->name('replies.index');
    Route::get('/replies/create/{post}', [ReplyController::class, 'create'])->name('replies.create');
    Route::post('/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::get('/replies/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
});

Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth:users', 'verified'])->name('dashboard');

Route::middleware('auth:users')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
