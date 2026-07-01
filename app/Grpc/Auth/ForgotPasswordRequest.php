<?php

namespace App\Grpc\Auth;

/**
 * Representación local de ForgotPasswordRequest.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class ForgotPasswordRequest
{
    public string $email = '';

    public function serializeToString(): string
    {
        if ($this->email === '') {
            return '';
        }

        return "\x0a".$this->encodeVarint(strlen($this->email)).$this->email;
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
