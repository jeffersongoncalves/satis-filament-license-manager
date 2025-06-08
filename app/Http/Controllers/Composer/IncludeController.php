<?php

namespace App\Http\Controllers\Composer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IncludeController
{
    public function __invoke(Request $request, string $include)
    {
        if (! File::exists($path = storage_path("app/private/satis/{$request->user('token')->id}/include/{$include}.json"))) {
            return response()->noContent(404);
        }

        $packages = File::json($path);

        return response()->json($packages, 200);
    }
}
