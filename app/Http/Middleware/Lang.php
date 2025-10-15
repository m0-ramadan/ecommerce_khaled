<?php

namespace App\Http\Middleware;

use Closure;

class Lang
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
        if ($request->lang || session()->has('lang')) {
            app()->setLocale($request->lang ?? session('lang'));
        } else {
            app()->setLocale('ar');
        }
        return $next($request);
    }
}
