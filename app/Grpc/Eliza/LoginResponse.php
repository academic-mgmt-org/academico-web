<?php

namespace App\Grpc\Eliza;

class LoginResponse
{
    public string $accessToken = '';
    public string $refreshToken = '';

    /**
     * Decodifica e integra los datos binarios en la instancia actual
     */
    public function mergeFromString(string $binary): void
    {
        $decoded = self::decode($binary);
        $this->accessToken = $decoded->accessToken;
        $this->refreshToken = $decoded->refreshToken;
    }

    /**
     * Decodifica la respuesta binaria de gRPC
     */
    public static function decode(string $binary): self
    {
        $response = new self();
        $offset = 0;
        $length = strlen($binary);

        while ($offset < $length) {
            // Leer el identificador de campo (tag + wire type)
            $tagVarint = self::readVarint($binary, $offset);
            $wireType = $tagVarint & 0x07;
            $fieldNumber = $tagVarint >> 3;

            if ($wireType === 2) { // Tipo delimitado por longitud (strings)
                $len = self::readVarint($binary, $offset);
                $str = substr($binary, $offset, $len);
                $offset += $len;

                if ($fieldNumber === 1) {
                    $response->accessToken = $str;
                } elseif ($fieldNumber === 2) {
                    $response->refreshToken = $str;
                }
            } else {
                // Omitir otros tipos de datos
                break;
            }
        }

        return $response;
    }

    private static function readVarint(string $binary, int &$offset): int
    {
        $value = 0;
        $shift = 0;
        while (true) {
            $byte = ord($binary[$offset++]);
            $value |= ($byte & 0x7f) << $shift;
            if (!($byte & 0x80)) {
                break;
            }
            $shift += 7;
        }
        return $value;
    }
}
