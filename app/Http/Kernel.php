<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            // Thêm RBAC và Audit nếu cần
        ],
    ];

    protected $routeMiddleware = [
        // ...existing code...
        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'rbac' => \App\Http\Middleware\RbacMiddleware::class,
        'audit' => \App\Http\Middleware\AuditLogMiddleware::class,
    ];
}