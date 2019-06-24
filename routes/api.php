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
Route::group([
    'namespace' => 'Search',
    'prefix' => '/v{version}/search',
], function () {
    Route::get('/', 'IndexController@index')->name('search_home');
    Route::post('/catalog/list/', 'SearchController@catalogList')->name('catalog_list');
});

Route::get('/swagger/index.html')->name('search_swagger');
Route::get('/frontend/index.html')->name('search_frontend');