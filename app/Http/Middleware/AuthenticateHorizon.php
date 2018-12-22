<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Horizon\Horizon;

class AuthenticateHorizon
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        Horizon::auth(function ($request) use ($guard) {
            return Auth::guard($guard)->check();
        });

        return $next($request);
    }
}
