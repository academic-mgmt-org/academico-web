<?php

namespace App\Services;

use App\Services\Contracts\AuthServiceInterface;
use RuntimeException;

class AuthGrpcService implements AuthServiceInterface
{
    protected $client;

    public function __construct()
    {
        // Lanzar excepción amigable si no se cumple el requisito de la extensión de C
        if (!extension_loaded('grpc')) {
            throw new RuntimeException("La extensión C de gRPC no está instalada en este entorno PHP. Debes instalarla ejecutando 'pecl install grpc' y agregando 'extension=grpc.so' a tu php.ini.");
        }

        $hostname = config('services.grpc.host', 'localhost:50051');
        
        // Instancia del cliente Auth gRPC sin archivo proto generado.
        $this->client = new \App\Grpc\Auth\AuthServiceClient($hostname, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
    }

    public function login(string $username, string $password): array
    {
        $request = new \App\Grpc\Auth\LoginRequest();
        $request->username = $username;
        $request->password = base64_encode($password);
        $request->passwordEncoding = 'base64';

        // Realizar la llamada unaria de gRPC apuntando a auth.v1.AuthService/Login
        $call = $this->client->Login($request);
        
        // Esperar la respuesta (retorna [Response, Status])
        list($response, $status) = $call->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            return [
                'success' => false,
                'message' => 'Error gRPC (' . $status->code . '): ' . $status->details,
            ];
        }

        return $this->loginResponseToArray(
            $response,
            'Inicio de sesión exitoso.',
            'Error de autenticación.',
            $username
        );
    }

    public function refresh(string $refreshToken): array
    {
        if (!$refreshToken) {
            return [
                'success' => false,
                'message' => 'Refresh token no disponible.',
            ];
        }

        $request = new \App\Grpc\Auth\RefreshTokenRequest();
        $request->refreshToken = $refreshToken;

        $call = $this->client->RefreshToken($request);
        list($response, $status) = $call->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            return [
                'success' => false,
                'message' => 'Error gRPC (' . $status->code . '): ' . $status->details,
            ];
        }

        return $this->loginResponseToArray(
            $response,
            'Sesión renovada correctamente.',
            'No se pudo renovar la sesión.'
        );
    }

    public function logout(?string $token = null, ?string $refreshToken = null): array
    {
        if (!$token && !$refreshToken) {
            return [
                'success' => false,
                'revoked' => false,
                'message' => 'Token de sesión no disponible.',
            ];
        }

        $request = new \App\Grpc\Auth\LogoutRequest();
        $request->token = $token ?? '';
        $request->refreshToken = $refreshToken ?? '';

        $call = $this->client->Logout($request);
        list($response, $status) = $call->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            return [
                'success' => false,
                'revoked' => false,
                'message' => 'Error gRPC (' . $status->code . '): ' . $status->details,
            ];
        }

        return [
            'success' => (bool) $response->success,
            'revoked' => (bool) $response->success,
            'message' => $response->message ?: 'Sesión cerrada correctamente.',
        ];
    }

    /**
     * Decodifica la sección payload de un JWT
     */
    private function decodeJwt(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        $payload = $parts[1];
        
        // Convertir de Base64Url a Base64 estándar
        $remainder = strlen($payload) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $payload .= str_repeat('=', $padlen);
        }
        $payload = strtr($payload, '-_', '+/');

        $decoded = base64_decode($payload);
        if (!$decoded) {
            return null;
        }

        return json_decode($decoded, true);
    }

    private function loginResponseToArray(
        \App\Grpc\Auth\LoginResponse $response,
        string $successMessage,
        string $failureMessage,
        ?string $fallbackUsername = null
    ): array {
        $success = !empty($response->accessToken);
        $userData = null;

        if ($success) {
            $userData = $this->userDataFromToken($response->accessToken, $fallbackUsername);
        }

        return [
            'success' => $success,
            'token' => $response->accessToken,
            'refreshToken' => $response->refreshToken,
            'expiresIn' => $response->expiresIn,
            'sessionId' => $response->sessionId,
            'message' => $success ? $successMessage : $failureMessage,
            'user' => $userData,
        ];
    }

    private function userDataFromToken(string $token, ?string $fallbackUsername = null): ?array
    {
        $payload = $this->decodeJwt($token);
        if (!$payload) {
            return null;
        }

        $role = 'user';
        if (!empty($payload['applications'][0]['roles'][0]['roleName'])) {
            $role = $payload['applications'][0]['roles'][0]['roleName'];
        } elseif (!empty($payload['role'])) {
            $role = $payload['role'];
        }

        return [
            'username' => $payload['email'] ?? $fallbackUsername ?? '',
            'identifier' => $payload['identifier'] ?? '',
            'userStudent' => $payload['userStudent'] ?? '',
            'userName' => $payload['userName'] ?? '',
            'role' => $role,
            'permissions' => $payload['applications'][0]['roles'][0]['permissions'] ?? []
        ];
    }
}
