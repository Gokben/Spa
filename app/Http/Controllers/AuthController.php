<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        if (! config('spa.login_required')) {
            $user = User::query()->orderBy('id')->first()
                ?? User::query()->create([
                    'name' => 'SPA Kullanıcısı',
                    'email' => 'spa@localhost.invalid',
                    'password' => Hash::make(Str::random(64)),
                ]);
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json(['user' => $user->only(['id', 'name', 'email'])]);
            }

            return redirect()->to(url('/'));
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json(['message' => 'E-posta veya şifre hatalı.'], 422);
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json(['user' => $request->user()->only(['id', 'name', 'email'])]);
        }

        return redirect()->to(url('/'));
    }

    public function destroy(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Oturum kapatıldı.',
            'csrfToken' => $request->session()->token(),
        ]);
    }
}
