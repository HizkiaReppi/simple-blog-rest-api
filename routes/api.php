<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::post('/login', [LoginController::class, 'store'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
    Route::resource('/posts', PostController::class)->names('posts')->except(['create', 'edit']);
    Route::resource('/categories', CategoryController::class)->names('categories')->except(['create', 'edit']);
});
