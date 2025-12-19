<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Hiển thị trang đẹp khi link xác thực hết hạn hoặc sai chữ ký
        $exceptions->render(function (InvalidSignatureException $e, $request) {
            $user_id = $request->route('user_id');
            return response()->view('pages.auth.verification-expired', [
                'user_id' => $user_id,
            ], 403);
        });
    })->create();
