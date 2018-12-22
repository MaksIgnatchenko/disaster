<?php

namespace App\Http\Middleware;

use App\Helpers\ApiCode;
use App\User;
use Closure;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ApiAuthenticate
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
        $deviceId = $request->header('deviceId');
        $user = User::with(['settings', 'locations'])->where('deviceId', $deviceId)->first();
        if (!$user) {
            return ResponseBuilder::error(ApiCode::DEVICE_NOT_FOUND);
        }
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        return $next($request);
    }
}
