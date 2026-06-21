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
        
        // Instancia del cliente autogenerado
        $this->client = new \App\Grpc\Auth\AuthServiceClient($hostname, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
    }

    public function login(string $username, string $password): array
    {
        $request = new \App\Grpc\Auth\LoginRequest();
        $request->setUsername($username);
        $request->setPassword($password);

        // Realizar la llamada unaria de gRPC
        $call = $this->client->Login($request);
        
        // Esperar la respuesta (retorna [Response, Status])
        list($response, $status) = $call->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            return [
                'success' => false,
                'message' => 'Error gRPC (' . $status->code . '): ' . $status->details,
            ];
        }

        $user = $response->getUser();

        return [
            'success' => $response->getSuccess(),
            'token' => $response->getToken(),
            'message' => $response->getMessage(),
            'user' => $user ? [
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'role' => $user->getRole(),
            ] : null,
        ];
    }
}
