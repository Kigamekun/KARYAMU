<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\XssSanitization;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(XssSanitization::class);
        $middleware->alias(['check.school.data' => \App\Http\Middleware\CheckSchoolData::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
