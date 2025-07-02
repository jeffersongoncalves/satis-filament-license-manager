<?php

use App\Http\Controllers\Api\DownloadComposerController;
use Illuminate\Support\Facades\Route;

Route::post('/composer/downloads', DownloadComposerController::class)->name('composer.downloads');
