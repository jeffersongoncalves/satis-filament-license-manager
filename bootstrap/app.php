<?php

use App\Http\Controllers\Composer\ArchivesController;
use App\Http\Controllers\Composer\IncludeController;
use App\Http\Controllers\Composer\PackagesController;
use App\Http\Controllers\Composer\PackagesV2Controller;
use App\Http\Middleware\EnsureUserHasLicense;
use App\Support\FaviconSupport;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            FaviconSupport::routes();

            Route::group(['middleware' => [AuthenticateWithBasicAuth::using('token'), EnsureUserHasLicense::class]], function (): void {
                Route::get('packages.json', PackagesController::class)->withoutMiddleware(EnsureUserHasLicense::class);
                Route::get('include/{include}.json', IncludeController::class)->withoutMiddleware(EnsureUserHasLicense::class);
                Route::get('p2/{vendor}/{package}.json', PackagesV2Controller::class);
                Route::get('archives/{vendor}/{package}/{file}', ArchivesController::class)->where('file', '.*\.zip');
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('satis:build')->weekly();
        $schedule->command('dependency:packages')->weekly();
    })
    ->create();
