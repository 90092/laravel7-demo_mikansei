<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:admin_api'], 'as' => 'admin.'], function () {
    Route::post('/addCategory', 'back\CategoryController@addCategory')->name('add_category');
    Route::post('/editCategory', 'back\CategoryController@editCategory')->name('edit_category');
    Route::post('/deleteCategory', 'back\CategoryController@deleteCategory')->name('delete_category');
    Route::post('/deleteProduct', 'back\ProductController@deleteProduct')->name('delete_product');
});