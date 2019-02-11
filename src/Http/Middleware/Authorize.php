<?php

namespace Gwd\LaravelNovaConfigs\Http\Middleware;

use Gwd\LaravelNovaConfigs\LaravelNovaConfigs;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(LaravelNovaConfigs::class)->authorize($request) ? $next($request) : abort(403);
    }
}
