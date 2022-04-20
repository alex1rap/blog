<?php

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\PostController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\PostController::class, 'index']);

Route::get('/posts/create',[App\Http\Controllers\PostController::class, 'create']);

Route::get('/posts/{post}', [App\Http\Controllers\PostController::class, 'show']);

Route::post('/post',[App\Http\Controllers\PostController::class, 'store']);

Route::get('/posts/{post}/edit', [App\Http\Controllers\PostController::class, 'edit']);

Route::patch('/posts/{post}', [App\Http\Controllers\PostController::class, 'update']);

Route::get('/posts/{post}/like', [App\Http\Controllers\PostController::class, 'like']);

Route::get('/posts/{post}/dislike', [App\Http\Controllers\PostController::class, 'dislike']);

Route::delete('/posts/{post}', [App\Http\Controllers\PostController::class, 'destroy']);


