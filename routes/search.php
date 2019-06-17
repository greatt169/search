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
], function () {

    Route::get('/', 'IndexController@index')->name('search_home');
    Route::get('/docs', 'IndexController@index')->name('search_docs');

    Route::get('/reindex', 'IndexController@reindex')->name('reindex');
    Route::get('/build', 'IndexController@build')->name('build');
});