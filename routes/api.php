<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TodoListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::resource('todo-lists', TodoListController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('cards', CardController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);
});