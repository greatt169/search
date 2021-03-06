<?php

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
Route::get('/', 'PagesController@index');
Route::get('/test', 'TestController@index');
Route::get('/test/json', 'TestController@jsonPack');
Route::get('/swagger/index.html', 'PagesController@swagger')->name('swagger_page');

Route::group([
    'namespace' => '\App\Demo\Controllers',
], function () {
    Route::group([
        'prefix' => '/demo',
    ], function () {
        Route::get('/', 'IndexController@index')->name('demo_home');
        Route::get('/catalog', 'CatalogController@index')->name('demo_catalog');
        Route::get('/search', 'SearchController@index')->name('demo_search');
        Route::get('/update', 'UpdateController@index')->name('demo_update');
    });
});