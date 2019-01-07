<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        /* 在访问网站之前先执行代码 */

        // 如果未登录就跳转到登录页面
        if( ! session('id') )
        {

            // 如果是AJAX的请求，返回JSON数据
            if($request->ajax())
            {
                // 中间件中返回时必须写全： return response( 返回的数据 )
                return response([
                    'errno' => 1001,
                    'errmsg' => '必须先登录！',
                ]);
            }

            // 如果是网页直接访问的就跳转
            return redirect()->route('login');
        }


        return $next($request);
    }
}
