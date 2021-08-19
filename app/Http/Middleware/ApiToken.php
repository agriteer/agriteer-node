<?php

namespace EmailService\Http\Middleware;

use Closure;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header("x-api-key") != env('API_KEY')) {
            return response()->json(['type' => 'error', 'message' => $request->header(), 'val' => env('API_KEY')], 401);
        }

        return $next($request);
    }
}
