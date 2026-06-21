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
        
        // Instancia del cliente Eliza gRPC sin archivo proto
        $this->client = new \App\Grpc\Eliza\ElizaServiceClient($hostname, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
    }

    public function login(string $username, string $password): array
    {
        $request = new \App\Grpc\Eliza\LoginRequest();
        $request->username = $username;
        $request->password = base64_encode($password);

        // Realizar la llamada unaria de gRPC apuntando a eliza.v1.ElizaService/Login
        $call = $this->client->Login($request);
        
        // Esperar la respuesta (retorna [Response, Status])
        list($response, $status) = $call->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            return [
                'success' => false,
                'message' => 'Error gRPC (' . $status->code . '): ' . $status->details,
            ];
        }

        $success = !empty($response->accessToken);
        $userData = null;

        if ($success) {
            // Decodificar el token JWT para extraer la información real del usuario
            $payload = $this->decodeJwt($response->accessToken);
            if ($payload) {
                // Intentar extraer el rol de la aplicación
                $role = 'user';
                if (!empty($payload['applications'][0]['roles'][0]['roleName'])) {
                    $role = $payload['applications'][0]['roles'][0]['roleName'];
                }

                $userData = [
                    'username' => $payload['email'] ?? $username,
                    'identifier' => $payload['identifier'] ?? '',
                    'userStudent' => $payload['userStudent'] ?? '',
                    'role' => $role,
                    'permissions' => $payload['applications'][0]['roles'][0]['permissions'] ?? []
                ];
            }
        }

        return [
            'success' => $success,
            'token' => $response->accessToken,
            'refreshToken' => $response->refreshToken,
            'message' => $success ? 'Inicio de sesión exitoso.' : 'Error de autenticación.',
            'user' => $userData,
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
}
