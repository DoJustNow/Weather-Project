<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyChangeEmail;
use App\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class ProfileController extends Controller
{
    //Метод отправки токена для верификации почты
    protected function sendToken()
    {
        try {
            //Текущий авторизованный пользователь
            $user = Auth::user();
            //Формируем токен для того чтобы подтвердить Email
            $user->verify_token = str_random(64);
            //Сохраняем измененную запись в БД
            $user->save();
            //Отправляем письмо на почту для подтверждение Email-а
            $sendMailResult = $this->sendMail($user, $user->verify_token);

        } catch (Exception $exception) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');
            return view('infoBoard');
        }
        //Записываем в сессию результат
        if ($sendMailResult) {
            session()->put('resent', 'Письмо удачно отправлено. Проверьте свою почту.');
        } else {
            session()->put('resent', 'Не удалось отправить письмо.');
        }

        return redirect()->route('verify');
    }

    //Показ страницы с информацией о том что надо подтвердить email
    protected function verify()
    {
        return view('auth.verify');
    }

    //Показать настройки профиля т.е показ "личного кабинета"
    protected function showSettings()
    {
        try {
            $user                 = Auth::user();
            $userEmail            = $user->email;
            $userUnconfirmedEmail = $user->unconfirmed_email;
            return view('auth.profile', compact(['userEmail', 'userUnconfirmedEmail']));
        } catch (Exception $exception) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');
            return view('infoBoard');
        }
    }

    //Метод изменения Email-a пользователя
    protected function changeSettings(Request $request)
    {   /*запись в сесию присланного Email-а чтобы потом закинуть в поле.
        просто по приколу*/
        $request->flashOnly('email');
        //Проверка полученных данных из формы
        $this->validate($request, ['email' => 'required|email|unique:users,email'],
                [
                        'email'    => 'Поле должно быть email-ом',
                        'unique'   => 'Пользователь с таким email-ом уже существует',
                        'required' => 'Поле обязательно для заполнения',
                ]);


        try {
            //Текущий авторизованный пользователь
            $user = Auth::user();
            //Ставим ему новый неподтвержденный email
            $user->unconfirmed_email = $request->email;
            //Формируем токен для того чтобы подтвердить Email
            $user->verify_token = str_random(64);
            //Сохраняем измененную запись в БД
            $user->save();
        } catch (Exception $exception) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');
            return view('infoBoard');
        }
        //Отправляем письмо на почту для подтверждение Email-а
        $sendMailResult = $this->sendMail($user, $user->verify_token);

        //Записываем в сессию результат отправки письма
        if ($sendMailResult) {
            session()->put('send_email_status', true);
            session()->put('send_email_message', 'Письмо удачно отправлено. Проверьте свою почту.');
        } else {
            session()->put('send_email_status', false);
            session()->put('send_email_message', 'Не удалось отправить письмо.');
        }

        //Редирект на страницу настроек чтобы отобразить результат
        return redirect()->route('profileSettings');
    }

//Функция отправки письма на почту
    private function sendMail(User $user, string $token)
    {
        try {
            //Отправка письма на непотвержденную почту используя свой шаблон письма VerifyChangeEmail
            Mail::to($user->unconfirmed_email)
                    ->send(new VerifyChangeEmail(env('APP_URL') . '/verification/' . $user->id . '/' . $token));

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

//Функция проверки ссылки из письма
    protected function verificationChangeEmail(int $id, string $token)
    {
        try {
            //Получаем юзера с такой связкой ID и confirm_token
            $user = User::where('id', $id)->where('verify_token', $token)->first();
        } catch (Exception $exception) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');
            return view('infoBoard');
        }

        //Если такого юзера нет
        if (is_null($user)) {
            //Записываем результат в сессию
            session()->put('infoMessage', 'Токен не подходит для данного пользователя!');
            return view('infoBoard');
        }
        //Записываем в переменную неподтвержденный адрес пользователя
        $unconfirmedEmail = $user->unconfirmed_email;
        //Проверяем не успел ли кто-то заюзать этот Email пока письмо лежало в ящике
        $validator = validator(['unc_email' => $unconfirmedEmail],
                ['unc_email' => 'unique:users,email'],
                ['unique' => 'Пользователь с таким Email-ом уже зарегистрирован.']);
        //Если есть ошибка значить пользователь с таким Email уже есть
        if ($validator->fails()) {
            //Обнуляем неподтвержденный Email
            $user->unconfirmed_email = null;
            //И обнуляем токен подтверждения Email
            $user->verify_token      = null;
            //Записываем результат в сессию
            session()->put('infoMessage',$validator->errors()->first());
            return view('infoBoard');
        }
        //Если тут значит юзера с таким Email нет и все ок
        //Назначаем пользователю новый Email
        $user->email = $unconfirmedEmail;
        //Обнуление неподтвержденного адреса
        $user->unconfirmed_email = null;
        //Обнуление токена
        $user->verify_token = null;
        //Устанавливаем дату верификации
        $user->email_verified_at = Carbon::now();
        //Сохранение в БД обновленных данных
        $user->save();
        //Логиним пользователя на сайт
        Auth::login($user, true);
        //Записываем результат в сессию
        session()->put('infoMessage', 'Email удачно подтвержден!');
        return view('infoBoard');
    }
}
