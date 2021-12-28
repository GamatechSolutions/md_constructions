<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
		if (! auth()->user()->hasRole([ $roles ])) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
