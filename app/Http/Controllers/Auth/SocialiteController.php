<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Validator;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        //Если провайдер существует редиректим к нему, иначе на страницу логина
        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect()->route('login');
        }
    }

    public function handleProviderCallback($provider)
    {

        //Получение данных о юзере
        $userSocialite = Socialite::driver($provider)->user();
// dd($userSocialite);
        $userData = [
            //Сохранение id пользователя из "соц.сети"
                "{$provider}_id"    => (string)$userSocialite->id,
            //Сохранение имени пользователя из "соц.сети"
                'name'              => $userSocialite->name,
            //Сохранение Email-a пользователя из "соц.сети"
                'unconfirmed_email' => $userSocialite->email ?? $userSocialite->accessTokenResponseBody['email'] ?? null,
        ];

        //Получение пользователя с таким provider_id из базы
        $user = User::where("{$provider}_id", $userData["{$provider}_id"])->first();

        //Если такого пользователя нет
        if (is_null($user)) {
            //создаем (регистрируем) нового пользователя
            $user = new User($userData);
            $user->save();
        }

        //Логиним пользователя на сайте и запоминаем
        Auth::login($user, true);

        //Редиректим на автоотправку письма по Email
        return redirect()->route('verify_resend');

    }
}
