<?php

declare(strict_types=1);

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TwitchVerificationMiddleware
{
    /**
     * @param mixed $guard
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if ($request->has('hub_challenge')) {
            return new Response($request->input('hub_challenge'), 200);
        }

        return $next($request);
    }
}
