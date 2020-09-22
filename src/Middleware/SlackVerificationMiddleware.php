<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SlackVerificationMiddleware
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($request->has('challenge')) {
            return new Response($request->input('challenge'), 200);
        }

        return $next($request);
    }
}
