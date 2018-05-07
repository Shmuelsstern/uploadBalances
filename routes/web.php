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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $request->session()->forget(['group','facility']);
    return view('uploadBalancesFile');
});


Route::post('/uploadNewBalances', 'FileController@parseFileIntoArray');

Route::post('/setColumns', 'NewBalancesController@setColumns');

Route::get('/setNewBalances', 'NewBalancesController@setNewBalances');

Route::get('/matchFacilities', 'FacilitiesController@matchFacilities');

Route::post('/updateNewFacilities', 'FacilitiesController@updateNewFacilities');

Route::get('/matchResidents', 'ResidentsController@matchResidents');

Route::get('/updateNewResidents', 'ResidentsController@updateNewResidents');

Route::get('/matchPayers', 'PayersController@matchPayers');

Route::post('/updateNewPayers', 'PayersController@updateNewPayers');

Route::get('/uploadNewBalancesToQuickbase', 'NewBalancesController@uploadNewBalancesToQuickbase');

Route::get('/layouts/groupSettings', 'AjaxController@groupSettings');

Route::get('/layouts/facilitySettings', 'AjaxController@facilitySettings');