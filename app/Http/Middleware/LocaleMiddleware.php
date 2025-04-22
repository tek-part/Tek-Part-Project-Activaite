<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::has('locale') ? Session::get('locale') : 'ar';

        // تعيين اللغة
        app()->setLocale($locale);

        // تعيين اتجاه الصفحة بناءً على اللغة
        $direction = $locale == 'ar' ? 'rtl' : 'ltr';
        app()->singleton('direction', function () use ($direction) {
            return $direction; 
        });

        // حفظ اللغة في الجلسة
        Session::put('locale', $locale);

        return $next($request);
    }
}
