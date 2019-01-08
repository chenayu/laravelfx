<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    // 全局中间件：把中间件的类注册到这个数组中，网站中所有的请求都会执行这个中间件中的代码
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    // 路由组中间件
    protected $middlewareGroups = [
        // 这个数组中的中间件，都会被应用到 routes/web.php 中的路由
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,  // 加密COOKIE
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,  // 开启SESSION
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,   // CSRF保护
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Access::class,
        ],
        // 应用到 routes/api.php  （API开发时不用使用SESSION、cookie、也没有CSRF保护）
        'api' => [
            'throttle:60,1',     //    频繁限制： 1分钟最多被访问 60 次
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    // 路由中间件
    // 在路由上设置middlware来使用
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'login' => \App\Http\Middleware\CheckLogin::class,
        'before' => \App\Http\Middleware\Before::class,
        'after' => \App\Http\Middleware\After::class,
    ];
}
