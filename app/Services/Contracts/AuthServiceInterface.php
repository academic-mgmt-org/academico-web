<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * Authenticate user with username and password.
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login(string $username, string $password): array;

    /**
     * Renew the access token using a refresh token.
     *
     * @param string $refreshToken
     * @return array
     */
    public function refresh(string $refreshToken): array;

    /**
     * Close the current authenticated session through the auth service.
     *
     * @param string|null $token
     * @param string|null $refreshToken
     * @return array
     */
    public function logout(?string $token = null, ?string $refreshToken = null): array;
}
