<?php

namespace App\Http\Middleware;

use Closure;

class After
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
        $res = $next($request);


        // 请求完之后执行的代码


        echo '<hr>  after ==== ' . microtime() . '<hr>';


        return $res;
    }
}
