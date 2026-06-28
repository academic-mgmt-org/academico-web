<?php

// Definición fallback temporal en caso de que la extensión C de gRPC no esté instalada.
// Esto evita errores de compilación/autoloading en entornos de desarrollo local.
namespace Grpc {
    if (!extension_loaded('grpc')) {
        class BaseStub {
            public function __construct($hostname, $opts, $channel = null) {}
            protected function _simpleRequest($method, $argument, $deserialize, $metadata = [], $options = []) {
                return new class($method) {
                    private string $method;

                    public function __construct(string $method) {
                        $this->method = $method;
                    }

                    public function wait() {
                        if (
                            $this->method === '/auth.v1.AuthService/Login' ||
                            $this->method === '/auth.v1.AuthService/RefreshToken'
                        ) {
                            $response = new \App\Grpc\Auth\LoginResponse();
                            $response->accessToken = 'mock-auth-token-via-fallback-stub';
                            $response->refreshToken = 'mock-auth-refresh-token';
                        } else {
                            $response = new \App\Grpc\Auth\GenericResponse();
                            $response->success = true;
                            $response->message = 'OK';
                        }

                        $status = new \stdClass();
                        $status->code = 0; // STATUS_OK
                        $status->details = 'OK';

                        return [$response, $status];
                    }
                };
            }
        }
    }
}

namespace App\Grpc\Auth {
    /**
     * Cliente gRPC para AuthService.
     * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc' y el plugin grpc_php_plugin).
     */
    class AuthServiceClient extends \Grpc\BaseStub
    {
        public function __construct($hostname, $opts, $channel = null)
        {
            parent::__construct($hostname, $opts, $channel);
        }

        /**
         * Llama al método RPC Login del servicio.
         *
         * @param \App\Grpc\Auth\LoginRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         * @return mixed
         */
        public function Login(\App\Grpc\Auth\LoginRequest $argument, $metadata = [], $options = [])
        {
            return $this->_simpleRequest(
                '/auth.v1.AuthService/Login',
                $argument,
                ['\App\Grpc\Auth\LoginResponse', 'decode'],
                $metadata,
                $options
            );
        }

        /**
         * Llama al método RPC RefreshToken del servicio.
         *
         * @param \App\Grpc\Auth\RefreshTokenRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         * @return mixed
         */
        public function RefreshToken(\App\Grpc\Auth\RefreshTokenRequest $argument, $metadata = [], $options = [])
        {
            return $this->_simpleRequest(
                '/auth.v1.AuthService/RefreshToken',
                $argument,
                ['\App\Grpc\Auth\LoginResponse', 'decode'],
                $metadata,
                $options
            );
        }

        /**
         * Llama al método RPC Logout del servicio.
         *
         * @param \App\Grpc\Auth\LogoutRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         * @return mixed
         */
        public function Logout(\App\Grpc\Auth\LogoutRequest $argument, $metadata = [], $options = [])
        {
            return $this->_simpleRequest(
                '/auth.v1.AuthService/Logout',
                $argument,
                ['\App\Grpc\Auth\GenericResponse', 'decode'],
                $metadata,
                $options
            );
        }
    }
}
