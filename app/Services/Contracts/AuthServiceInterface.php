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
}
