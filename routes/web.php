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
// authentication
Route::get('/signin', function () {
    return view('auth.backend.signin');
});

Route::get('/signup', function () {
    return view('auth.backend.signup');
});

Route::post('/backend/signin', 'Auth\Backend\AuthController@signin')->name('backend.signin');
Route::post('/backend/signup', 'Auth\Backend\AuthController@signup')->name('backend.signup');


// frontend
Route::resource('/', 'Frontend\HomePageController');

// backend
Route::group(['middleware'=>'isadmin'], function() {
    Route::get('/admin', 'Backend\AdminController@index');
    Route::resource('admin/services', 'Backend\AdminServiceController');
    Route::resource('admin/locations', 'Backend\AdminLocationController');
    Route::resource('admin/vehicles', 'Backend\AdminVehicleController');
    Route::post('admin/vehicles/save', 'Backend\AdminVehicleController@save')->name('admin.vehicles.save');
});

