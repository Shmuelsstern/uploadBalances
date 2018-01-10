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
    return view('uploadBalancesFile');
});

Route::post('/uploadNewBalances',  'FileController@parseFileIntoArray');

Route::post('/setColumns', 'NewBalancesController@setColumns');

Route::get('/setNewBalances','NewBalancesController@setNewBalances');

Route::get('/matchFacilities','FacilitiesController@matchFacilities');

Route::post('/updateNewFacilities','FacilitiesController@updateNewFacilities');