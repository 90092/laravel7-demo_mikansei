<?php

namespace App\Http\Middleware;

use Closure;

class BackAuth
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
        if (! auth('admin')->check()) {
            session()->put('after_login', url()->current());
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
