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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('uploadBalancesFile');
});

//Route::get('/', 'UploadController@upload');

Route::post('/uploadNewBalances',  'FileController@parseFileIntoArray');

// for testing only because the file is not uploading from the post form
Route::get('/uploadNewBalances',  'FileController@parseFileIntoArray');