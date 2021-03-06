<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/insert-product', [ProductController::class, 'insertProduct']);
Route::post('/update-product/{product_id}', [ProductController::class, 'updateProduct']);


Route::post('make-like/{product_id}', [LikeController::class, 'makeLike']);
Route::post('make-dislike/{like_id}', [LikeController::class, 'makeDislike']);
Route::get('get-my-favorite', [LikeController::class, 'getMyFavorite']);