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
   return redirect()->route('home');
});

Route::prefix('weather')->middleware(['auth'])->group(
        function () {
            Route::get('form', 'WeatherController@addWeatherForm')->name('formWeather');
            Route::post('form', 'WeatherController@addWeather')->name('formAddWeather');
            Route::get('show/{city?}', 'WeatherController@showWeather')->name('showWeather')
            ;
            Route::post('show','WeatherController@targetCity')->name('cityWeather');
        });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
