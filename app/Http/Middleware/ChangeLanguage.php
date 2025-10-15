<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;


class ChangeLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $value = $request->session()->get('lang');

        if (in_array($value, ['ar', 'en', 'it'])) {
            App::setLocale($value);
        } else {
            App::setLocale('ar');
        }
        return $next($request);
    }
}
