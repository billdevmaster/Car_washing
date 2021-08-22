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
})->name('signin');

Route::get('/signup', function () {
    return view('auth.backend.signup');
})->name('signup');

Route::post('/backend/signin', 'Auth\Backend\AuthController@signin')->name('backend.signin');
Route::post('/backend/signup', 'Auth\Backend\AuthController@signup')->name('backend.signup');


// frontend
Route::resource('/', 'Frontend\HomePageController');

// backend
Route::group(['middleware' => 'auth'], function(){
    Route::group(['middleware'=>'isadmin'], function() {
        Route::get('/admin', 'Backend\AdminController@index')->name('admin');
        // vehicle route
        Route::get('/admin/vehicles', 'Backend\AdminVehicleController@index')->name('admin.vehicles');;
        Route::post('/admin/vehicles/save', 'Backend\AdminVehicleController@save')->name('admin.vehicles.save');
        Route::get('/admin/vehicles/get_list', 'Backend\AdminVehicleController@get_list');
        // end vehicle route
        
        // service route
        Route::get('/admin/services', 'Backend\AdminServiceController@index')->name('admin.services');
        Route::get('/admin/services/get_list', 'Backend\AdminServiceController@get_list');
        Route::post('/admin/services/save', 'Backend\AdminServiceController@save')->name('admin.services.save');
        Route::post('/admin/services/remove', 'Backend\AdminServiceController@remove');
        // end service route
        
        // location route
        Route::get('/admin/locations', 'Backend\AdminLocationController@index')->name('admin.locations');
        Route::post('/admin/locations/save', 'Backend\AdminLocationController@save')->name('admin.locations.save');
        Route::post('/admin/locations/save_general', 'Backend\AdminLocationController@save_general')->name('admin.locations.save_general');
        Route::get('/admin/locations/edit/', 'Backend\AdminLocationController@edit')->name('admin.locations.edit');
        Route::get('/admin/locations/get_list', 'Backend\AdminLocationController@get_list');
        // end location route
    });
});

