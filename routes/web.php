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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes(['register' => false]);

Route::group(
    [
        'prefix'    => '/consultant',
        'namespace' => 'Consultant',
    ], function () {

        // Login
        Route::get('/login', [
            'as'    => 'consultant.login',
            'uses'  => 'Auth\LoginController@showLoginForm'
        ]);

        Route::post('/login', [
            'as' => 'consultant.login.submit',
            'uses' => 'Auth\LoginController@login'
        ]);

        Route::post('/logout', [
            'as'    => 'consultant.logout',
            'uses'  => 'Auth\LoginController@logout'
        ]);

        Route::group(
            [
                'middleware' => ['auth:web']
            ], function () {

                Route::get('/home', 'HomeController@index')->name('home');

                Route::resource('assistances', 'AssistanceController')->only([
                    'index'
                ]);

                Route::resource('assistances.bookings', 'BookingController')->only([
                    'index', 'update'
                ]);
            }
        );
    }
);
