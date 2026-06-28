<?php

namespace App\Grpc\Auth;

/**
 * Representación local de LogoutRequest.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class LogoutRequest
{
    public string $token = '';
    public string $refreshToken = '';

    public function serializeToString(): string
    {
        $binary = '';

        if ($this->token !== '') {
            $binary .= "\x0a" . $this->encodeVarint(strlen($this->token)) . $this->token;
        }

        if ($this->refreshToken !== '') {
            $binary .= "\x12" . $this->encodeVarint(strlen($this->refreshToken)) . $this->refreshToken;
        }

        return $binary;
    }

    private function encodeVarint(int $value): string
    {
        $bytes = '';
        while ($value > 0x7f) {
            $bytes .= chr(($value & 0x7f) | 0x80);
            $value >>= 7;
        }
        $bytes .= chr($value);
        return $bytes;
    }
}
