<?php

namespace App\Grpc\Auth;

/**
 * Representación local de la entidad User en gRPC.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class User
{
    protected string $username = '';
    protected string $name = '';
    protected string $role = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }
}
