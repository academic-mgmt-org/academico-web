<?php

namespace App\Grpc\Auth;

/**
 * Representación local de RefreshTokenRequest.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class RefreshTokenRequest
{
    public string $refreshToken = '';

    public function serializeToString(): string
    {
        if ($this->refreshToken === '') {
            return '';
        }

        return "\x0a" . $this->encodeVarint(strlen($this->refreshToken)) . $this->refreshToken;
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
