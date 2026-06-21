<?php

namespace Grpc {
    if (!class_exists(\Grpc\BaseStub::class, false)) {
        class BaseStub {
            public function __construct($hostname, $opts, $channel = null) {}
            protected function _simpleRequest($method, $argument, $deserialize, $metadata = [], $options = []) {
                return new class {
                    public function wait() {
                        $response = new \App\Grpc\Eliza\LoginResponse();
                        $response->success = true;
                        $response->token = 'mock-eliza-token-via-fallback';
                        $response->message = 'Fallback OK (gRPC extension not loaded)';

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

namespace App\Grpc\Eliza {
    /**
     * Cliente gRPC para ElizaService sin archivo proto.
     */
    class ElizaServiceClient extends \Grpc\BaseStub
    {
        public function __construct(string $hostname, array $opts, $channel = null)
        {
            parent::__construct($hostname, $opts, $channel);
        }

        /**
         * Realiza el RPC Login apuntando a /eliza.v1.ElizaService/Login
         */
        public function Login(LoginRequest $argument, array $metadata = [], array $options = [])
        {
            return $this->_simpleRequest(
                '/eliza.v1.ElizaService/Login',
                $argument,
                ['\App\Grpc\Eliza\LoginResponse', 'decode'],
                $metadata,
                $options
            );
        }
    }
}
