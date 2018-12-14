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

Route::prefix('weather')->middleware(['auth', 'myVerifiedEmail'])->group(
        function () {
            Route::get('form', 'WeatherController@addWeatherForm')->name('formWeather');
            Route::post('form', 'WeatherController@addWeather')->name('formAddWeather');
            Route::get('show/{city?}', 'WeatherController@showWeather')->name('showWeather');
            Route::post('show', 'WeatherController@targetCity')->name('cityWeather');
        });

Route::get('/home', 'HomeController@index')->name('home');

//SOCIALITE
Route::prefix('socialite')->group(
        function () {
            Route::get('{provider}', 'Auth\SocialiteController@redirectToProvider')->name('socialiteAuth');
            Route::any('{provider}/callback', 'Auth\SocialiteController@handleProviderCallback');
        }
);

Auth::routes();
//Отправка токена на мыло
Route::get('verify/send', 'Auth\ProfileController@sendToken')
        ->middleware(['auth', 'myCheckExistEmail'])->name('verify_resend');
//Страниццу с информацией о том что надо подтвердить Email
Route::get('verify', 'Auth\ProfileController@verify')->middleware(['auth', 'myCheckExistEmail'])->name('verify');
//Верификация (подтверждение) email-a при изменении
Route::get('verification/{id}/{token}', 'Auth\ProfileController@verificationChangeEmail')
        ->name('verificationEmail');
Route::get('verif/{id}/{token}', 'Auth\SocialiteController@verifySocialite')->name('verifySocialite');
//Роуты профайла пользователя
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('settings', 'Auth\ProfileController@showSettings')->name('profileSettings');
    Route::post('settings', 'Auth\ProfileController@changeSettings')->name('profileSettingsChange');
});

