<?php

namespace App\Http\Controllers\Api;

use App\Jobs\DownloadComposerJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadComposerController
{
    public function __invoke(Request $request): JsonResponse
    {
        $downloads = $request->get('downloads', []);

        foreach ($downloads as $download) {
            DownloadComposerJob::dispatch($download['name'], $download['version']);
        }

        return response()->json(status: 201, options: JSON_UNESCAPED_UNICODE);
    }
}
