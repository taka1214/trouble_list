<?php

use App\Http\Controllers\Owner\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Owner\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Owner\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Owner\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Owner\Auth\NewPasswordController;
use App\Http\Controllers\Owner\Auth\PasswordController;
use App\Http\Controllers\Owner\Auth\PasswordResetLinkController;
use App\Http\Controllers\Owner\Auth\RegisteredUserController;
use App\Http\Controllers\Owner\Auth\VerifyEmailController;
use App\Http\Controllers\Owner\UsersController;
use App\Http\Controllers\Owner\PostController;
use App\Http\Controllers\Owner\ReplyController;
use App\Http\Controllers\Owner\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('owner.auth.login');
});

Route::resource('users', UsersController::class)
    ->middleware(['auth:owners', 'verified']);

Route::middleware(['auth:owners', 'verified'])->group(function () {
    Route::resource('posts', PostController::class);
    
    Route::post('/post/like/{id}', [PostController::class, 'like'])->name('posts.like');
    Route::post('/post/unlike/{id}', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::middleware(['auth:owners', 'verified'])->group(function () {
    Route::get('/replies', [ReplyController::class, 'index'])->name('replies.index');
    Route::get('/replies/create/{post}', [ReplyController::class, 'create'])->name('replies.create');
    Route::post('/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::get('/replies/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
});

Route::prefix('expired-users')->middleware('auth:owners')->group(function () {
    Route::get('/index', [UsersController::class, 'expiredUserIndex'])->name('expired-users.index');
    Route::get('show/{user}', [UsersController::class, 'expiredUserShow'])->name('expired-users.show');
    Route::delete('destroy/{user}', [UsersController::class, 'expiredUserDestroy'])->name('expired-users.destroy');
    Route::put('restore/{user}', [UsersController::class, 'expiredUserRestore'])->name('expired-users.restore');
});

Route::get('/dashboard', function () {
    return view('owner.dashboard');
})->middleware(['auth:owners', 'verified'])->name('dashboard');

Route::middleware('auth:owners')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.reset');
});

Route::middleware('auth:owners')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('owners.posts.index');
    } else {
        return redirect()->route('owner.login');
    }
});
