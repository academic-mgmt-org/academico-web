<?php

namespace App\Grpc\Auth;

/**
 * Representación local de LoginResponse.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class LoginResponse
{
    protected bool $success = false;
    protected string $token = '';
    protected string $message = '';
    protected ?User $user = null;

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
