<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyChangeEmail;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mail;
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
        $userData      = [
            //Сохранение id пользователя из "соц.сети"
            "{$provider}_id" => (string)$userSocialite->id,
            //Сохранение имени пользователя из "соц.сети"
            'name'           => $userSocialite->name,
            //Сохранение Email-a пользователя из "соц.сети"
            'email'          => $userSocialite->email ??
                                $userSocialite->accessTokenResponseBody['email']
                                ?? null,
        ];

        //Получение пользователя с таким provider_id из базы
        $user = User::where("{$provider}_id", $userData["{$provider}_id"])
                    ->first();
        //Если такого пользователя нет
        if ($user === null) {
            //Если c соцсети поступил Email
            if ($userData['email'] !== null) {
                //Проверка Email-а из соцсети есть ли уже юзер с таким Email
                $user = User::where('email', $userData['email'])->first();
                //Если true то юзер с таким Email уже есть
                if ($user) {
                    switch ($provider):
                        case 'github':
                            $user->github_id = $userData['github_id'];
                            break;
                        case 'vkontakte':
                            $user->vkontakte_id = $userData['vkontakte_id'];
                            break;
                        case 'mailru':
                            $user->mailru_id = $userData['mailru_id'];
                            break;
                        case 'google':
                            $user->google_id = $userData['google_id'];
                            break;
                    endswitch;
                } else {
                    //создаем (регистрируем) нового пользователя
                    $user = new User($userData);
                    //Назначаем дату верификации Email
                    $user->email_verified_at = Carbon::now();
                }
            } else {
                $user = new User($userData);
            }
            $user->save();
        }
        //Логиним пользователя на сайте и запоминаем
        Auth::login($user, true);

        /*Редиректим на profile*/

        return redirect()->route('profileSettings');
    }
}
