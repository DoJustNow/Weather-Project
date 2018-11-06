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

Route::get('/', function (Request $request) {
    //var_dump(session()->all());
    //echo session('test');
    return redirect()->route('home');
});

Route::prefix('weather')->middleware(['auth'])->group(
        function () {
            Route::get('form', 'WeatherController@addWeatherForm')->name('formWeather');
            Route::post('form', 'WeatherController@addWeather')->name('formAddWeather');
            Route::get('show/{city?}', 'WeatherController@showWeather')->name('showWeather');
            Route::post('show', 'WeatherController@targetCity')->name('cityWeather');
        });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//TODO
Route::prefix('socialite')->group(
        function () {
            Route::get('{provider}', function ($provider) {
                return Socialite::driver($provider)->redirect();
            });
            Route::get('{provider}/callback',
                    function ($provider) {
                        $user = Socialite::driver($provider)->user();
                        var_dump($user);
                    }
            );
        }
);
/*
Route::get('/socialite/github',
        function () {
            return Socialite::driver('github')->redirect();
        }
        );
Route::get('/socialite/github/callback',
        function (Request $request){
            $user = Socialite::driver('github')->user();
            session()->put('userIdGit',$user->nickname);
            var_dump($user);

        });
Route::get('/test',
        function () {
    session()->flush();
    //session()->put('test','1234567');
    return redirect('/');
        }
        );*/