<?php

namespace App\Http\Middleware;

use Closure;

class Before
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

        echo '<hr>   before =-- ' . microtime()  .'<hr>';


        return $next($request);
    }
}
