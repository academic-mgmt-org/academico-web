<?php

namespace App\Services;

use App\Services\Contracts\AuthServiceInterface;

class AuthMockService implements AuthServiceInterface
{
    public function login(string $username, string $password): array
    {
        // Simulación de respuesta gRPC exitosa o fallida
        if ($username && $password) {
            return [
                'success' => true,
                'token' => 'mock-jwt-token-grpc-987654321',
                'user' => [
                    'username' => $username,
                    'name' => 'Usuario Mock (Simulando gRPC)',
                    'role' => 'admin',
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Credenciales inválidas (Simulando gRPC).'
        ];
    }
}
