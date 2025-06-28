<?php

use App\Models\LeaveRequest;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\View; // 1. Import View Facade

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => CheckAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->booting(function () {
        // DIUBAH: Logika diletakkan langsung di dalam closure
        View::composer(
            ['layouts.admin.navbar', 'layouts.admin.menu'],
            function ($view) {
                // Langsung ambil data di sini
                $pendingCount = LeaveRequest::where('status', 'pending')->count();
                // Kirim data ke view
                $view->with('cutiPending', $pendingCount);
            }
        );
    })->create();
