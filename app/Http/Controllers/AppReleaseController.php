<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class AppReleaseController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $path = public_path('release.json');
        $release = is_file($path) ? json_decode(file_get_contents($path), true) : [];
        $version = $release['version'] ?? null;

        return response()->json([
            'version' => is_string($version) && preg_match('/^\d{5}\.\d{2,}$/', $version) ? $version : null,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
