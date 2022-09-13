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

Route::get('/', 'front\FrontController@index')->name('list');

Route::get('/item/{pid}', 'front\FrontController@item');

Route::get('/cart', 'front\FrontController@cart')->name('cart');

Route::get('test', function (){
    // $products = \App\Models\Products::find(2)->each(function ($item) {
    //     $item->update(['categories' => '[]']);
    // });
    // echo Storage::url('file.jpg');;
});

// Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', 'back\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'back\Auth\LoginController@login');
    Route::get('register', 'back\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'back\Auth\RegisterController@register');
    Route::post('logout', 'back\Auth\LoginController@logout')->name('logout');
    Route::middleware(['back.auth'])->group(function () {
        Route::get('/', 'back\BackController@index')->name('index');
        Route::get('/user', 'back\BackController@user')->name('user');
        Route::get('/categories', 'back\BackController@categoryList')->name('categories');
        Route::get('/products', 'back\BackController@productList')->name('products');
        Route::get('/add_product', 'back\BackController@addProduct')->name('add_product');
        Route::post('/add_product', 'back\ProductController@addProduct');
        Route::get('/edit_product/{pid}', 'back\BackController@editProduct')->name('edit_product');
        Route::post('/edit_product/{pid}', 'back\ProductController@editProduct');
        Route::get('/orders', 'back\BackController@orderList')->name('orders');
    });
});