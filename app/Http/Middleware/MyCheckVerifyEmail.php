<?php

namespace App\Http\Middleware;

use Closure;

class MyCheckVerifyEmail
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
        //если пользователь не подтвердил Email
        if ( ! $request->user()->email_verified_at) {
            //редирект на страницу напоминания о том что надо подтвердить мыло
            return redirect()->route('verify');
        }

        return $next($request);
    }
}
