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
                'refreshToken' => 'mock-refresh-token-grpc-987654321',
                'expiresIn' => 7200,
                'user' => [
                    'username' => $username,
                    'name' => 'Usuario Mock (Simulando gRPC)',
                    'role' => 'admin',
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Credenciales inválidas (Simulando gRPC).',
        ];
    }

    public function refresh(string $refreshToken): array
    {
        return [
            'success' => true,
            'token' => 'mock-jwt-token-grpc-987654321-refreshed',
            'refreshToken' => $refreshToken,
            'expiresIn' => 7200,
            'user' => [
                'username' => 'estudiante@utn.edu.ec',
                'name' => 'Usuario Mock (Simulando gRPC)',
                'role' => 'admin',
            ],
            'message' => 'Sesión renovada correctamente (Simulando gRPC).',
        ];
    }

    public function forgotPassword(string $email): array
    {
        return [
            'success' => true,
            'message' => 'Si hay una cuenta asociada a ese correo, enviaremos instrucciones en los próximos minutos. Revisa también spam o correo no deseado. Si no recibes nada, verifica que escribiste el correo correcto o contacta soporte académico. (Simulando gRPC).',
        ];
    }

    public function logout(?string $token = null, ?string $refreshToken = null): array
    {
        return [
            'success' => true,
            'revoked' => true,
            'message' => 'Sesión cerrada correctamente (Simulando gRPC).',
        ];
    }
}
