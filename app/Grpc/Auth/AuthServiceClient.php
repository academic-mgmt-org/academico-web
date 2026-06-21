<?php

// Definición fallback temporal en caso de que la extensión C de gRPC no esté instalada.
// Esto evita errores de compilación/autoloading en entornos de desarrollo local.
namespace Grpc {
    if (!extension_loaded('grpc')) {
        class BaseStub {
            public function __construct($hostname, $opts, $channel = null) {}
            protected function _simpleRequest($method, $argument, $deserialize, $metadata = [], $options = []) {
                return new class {
                    public function wait() {
                        $response = new \App\Grpc\Auth\LoginResponse();
                        $response->setSuccess(true);
                        $response->setToken('mock-gRPC-token-via-fallback-stub');
                        
                        $user = new \App\Grpc\Auth\User();
                        $user->setUsername('fallback_user');
                        $user->setName('Usuario Fallback (gRPC C-ext missing)');
                        $user->setRole('admin');
                        $response->setUser($user);

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
                '/auth.AuthService/Login',
                $argument,
                ['\App\Grpc\Auth\LoginResponse', 'decode'],
                $metadata,
                $options
            );
        }
    }
}
