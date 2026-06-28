<?php

namespace App\Grpc\Auth;

/**
 * Representación local de LoginRequest.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class LoginRequest
{
    public string $username = '';
    public string $password = '';
    public string $appVersion = '';
    public string $passwordEncoding = '';

    public function serializeToString(): string
    {
        $binary = '';

        if ($this->username !== '') {
            $binary .= "\x0a" . $this->encodeVarint(strlen($this->username)) . $this->username;
        }

        if ($this->password !== '') {
            $binary .= "\x12" . $this->encodeVarint(strlen($this->password)) . $this->password;
        }

        if ($this->appVersion !== '') {
            $binary .= "\x1a" . $this->encodeVarint(strlen($this->appVersion)) . $this->appVersion;
        }

        if ($this->passwordEncoding !== '') {
            $binary .= "\x22" . $this->encodeVarint(strlen($this->passwordEncoding)) . $this->passwordEncoding;
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
