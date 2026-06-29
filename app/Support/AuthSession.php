<?php

namespace App\Support;

use App\Services\Contracts\AuthServiceInterface;

class AuthSession
{
    public static function store(array $result): void
    {
        $expiresIn = (int) ($result['expiresIn'] ?? 0);

        session([
            'user_token' => $result['token'],
            'user_refresh_token' => $result['refreshToken'] ?? session('user_refresh_token'),
            'user_token_expires_at' => $expiresIn > 0 ? time() + $expiresIn : null,
            'user' => $result['user'] ?? session('user'),
        ]);
    }

    public static function clear(): void
    {
        session()->forget([
            'user',
            'user_token',
            'user_refresh_token',
            'user_token_expires_at',
        ]);
    }

    public static function renew(AuthServiceInterface $authService): bool
    {
        $hasToken = (bool) session('user_token');
        $refreshToken = session('user_refresh_token');

        if ($hasToken && ! self::tokenNeedsRefresh()) {
            return true;
        }

        if (! $refreshToken) {
            return $hasToken;
        }

        $result = $authService->refresh($refreshToken);

        if ($result['success'] ?? false) {
            self::store($result);

            return true;
        }

        self::clear();

        return false;
    }

    private static function tokenNeedsRefresh(): bool
    {
        $expiresAt = session('user_token_expires_at');

        if (! $expiresAt) {
            return true;
        }

        return ((int) $expiresAt - time()) <= 300;
    }
}
