<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * Authenticate user with username and password.
     */
    public function login(string $username, string $password): array;

    /**
     * Renew the access token using a refresh token.
     */
    public function refresh(string $refreshToken): array;

    /**
     * Request a password recovery email.
     */
    public function forgotPassword(string $email): array;

    /**
     * Close the current authenticated session through the auth service.
     */
    public function logout(?string $token = null, ?string $refreshToken = null): array;
}
