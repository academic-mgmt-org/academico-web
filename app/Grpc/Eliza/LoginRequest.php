<?php

namespace App\Grpc\Eliza;

class LoginRequest
{
    public string $username = '';
    public string $password = '';

    /**
     * Serializa las propiedades a formato binario Protobuf
     */
    public function serializeToString(): string
    {
        $binary = '';

        // Tag 1 (username): wire type 2 (length-delimited) -> (1 << 3) | 2 = 10 (\x0a)
        if ($this->username !== '') {
            $binary .= "\x0a" . $this->encodeVarint(strlen($this->username)) . $this->username;
        }

        // Tag 2 (password): wire type 2 (length-delimited) -> (2 << 3) | 2 = 18 (\x12)
        if ($this->password !== '') {
            $binary .= "\x12" . $this->encodeVarint(strlen($this->password)) . $this->password;
        }

        return $binary;
    }

    /**
     * Codifica un entero usando codificación Varint (Base 128 Varints)
     */
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
