<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [NewsController::class, 'home']);

Route::get('/login', [NewsController::class, 'login']);

Route::post('/authenticate', [NewsController::class, 'authenticate']);

Route::middleware('session.has.admin')->group(function () {

    Route::get('/logout', [NewsController::class, 'logout']);

    Route::get('/news', function() {
        return view('news');
    });

    Route::get('/news_list', function() {
        return view('news_list', ['search_text' => '']);
    });

    Route::get('/search_news', [NewsController::class, 'search_news']);

    Route::get('/news_add', [NewsController::class, 'news_add']);

    Route::post('/news_insert', [NewsController::class, 'news_insert']);

    Route::get('/news_edit/{id}', [NewsController::class, 'news_edit']);

    Route::post('/news_update/{id}', [NewsController::class, 'news_update']);

    Route::get('/news_delete/{id}/{search_text?}', [NewsController::class, 'news_delete']);
});
