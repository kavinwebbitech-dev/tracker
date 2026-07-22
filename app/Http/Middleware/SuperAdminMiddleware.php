<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class SuperAdminMiddleware
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
        if (Auth::check() && Auth::user()->user_type == 'super_admin' && Auth::user()->status == 'active') {
            return $next($request);
        }
        elseif (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login your Account');
        }
        else{
            Auth::logout();
            return redirect()->route('login')->with('success', 'Please Login your Account');
        }
    }
}
