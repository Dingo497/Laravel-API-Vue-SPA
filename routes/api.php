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

// Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum')->name('profile');

Route::resource('todo-lists', TodoListController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);

Route::get('todo-list', [TodoListController::class, 'showLast'])->name('todo-lists.showLast');

Route::resource('cards', CardController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);