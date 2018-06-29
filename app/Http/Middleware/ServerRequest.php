<?php

namespace App\Http\Middleware;

use Closure;

class ServerRequest
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
        if (config('app.server_ip') !== $request->ip()) {
            return response('Invalid Request, hacker!', 401);
        }

        return $next($request);
    }
}
