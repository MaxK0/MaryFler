<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, авторизован ли пользователь и является ли он администратором
        if (Auth::check() && Auth::user()->email === 'admin@admin.com') {
            return $next($request);
        }

        // Если не администратор, перенаправляем на главную страницу с сообщением об ошибке
        return redirect()->route('home')->with('error', 'У вас нет прав доступа к этой странице');
    }
}
