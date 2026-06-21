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

        return [
            'success' => $response->success,
            'token' => $response->token,
            'message' => $response->message,
            'user' => $response->success ? [
                'username' => $username,
                'name' => 'Usuario Autenticado (Eliza)',
                'role' => 'user',
            ] : null,
        ];
    }
}
