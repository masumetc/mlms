<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() == true && Auth::user()->id == '1') {
            return redirect('admin/dashboard');
        }
        if (Auth::check() == true && Auth::user()->id != '1') {
            return redirect('user/dashboard');
        }

        return $next($request);
    }
}
