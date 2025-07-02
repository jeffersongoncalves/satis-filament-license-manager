<?php

namespace App\Http\Controllers\Composer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArchivesController
{
    public function __invoke(Request $request, string $vendor, string $package, string $file)
    {
        if (! File::exists($path = storage_path("app/private/satis/{$request->user('token')->id}/archives/{$vendor}/{$package}/{$file}"))) {
            return response()->noContent(404);
        }

        return response()->file($path);
    }
}
