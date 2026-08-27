<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SpaAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

        try {
            $payload = json_decode(Crypt::decryptString((string) $request->bearerToken()), true, flags: JSON_THROW_ON_ERROR);
            $userId = filter_var($payload['user_id'] ?? null, FILTER_VALIDATE_INT);
            $expiresAt = filter_var($payload['expires_at'] ?? null, FILTER_VALIDATE_INT);

            if (! $userId || ! $expiresAt || $expiresAt < now()->timestamp || ! Auth::onceUsingId($userId)) {
                abort(401, 'Unauthenticated.');
            }
        } catch (Throwable) {
            abort(401, 'Unauthenticated.');
        }

        return $next($request);
    }
}
