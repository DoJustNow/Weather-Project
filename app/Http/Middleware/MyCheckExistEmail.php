<?php

namespace App\Http\Middleware;

use Closure;

class MyCheckExistEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Если у пользователя нет Email-a редиректить его в настройки профиля чтобы он его указал
        if ( ! $request->user()->unconfirmed_email) {
            return redirect()->route('profileSettings');
        }

        return $next($request);
    }
}
