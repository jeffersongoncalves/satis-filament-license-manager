<?php

use App\Http\Controllers\Composer\ArchivesController;
use App\Http\Controllers\Composer\IncludeController;
use App\Http\Controllers\Composer\PackagesController;
use App\Http\Controllers\Composer\PackagesV2Controller;
use App\Http\Middleware\EnsureUserHasLicense;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => [AuthenticateWithBasicAuth::using('token'), EnsureUserHasLicense::class]], function (): void {
    Route::get('packages.json', PackagesController::class)->withoutMiddleware(EnsureUserHasLicense::class);
    Route::get('include/{include}.json', IncludeController::class)->withoutMiddleware(EnsureUserHasLicense::class);
    Route::get('p2/{vendor}/{package}.json', PackagesV2Controller::class);
    Route::get('archives/{vendor}/{package}/{file}', ArchivesController::class)->where('file', '.*\.zip');
});
