<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksControllerApi;

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

// Rotas da API para o recurso de livros
Route::get('/books', [BooksControllerApi::class, 'index']);
Route::get('/books/{id}', [BooksControllerApi::class, 'show']);
Route::post('/books', [BooksControllerApi::class, 'store']);
Route::put('/books/{id}', [BooksControllerApi::class, 'update']);
Route::delete('/books/{id}', [BooksControllerApi::class, 'destroy']);
