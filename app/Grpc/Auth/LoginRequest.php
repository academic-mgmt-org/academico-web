<?php

namespace App\Grpc\Auth;

/**
 * Representación local de LoginRequest.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class LoginRequest
{
    protected string $username = '';
    protected string $password = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
