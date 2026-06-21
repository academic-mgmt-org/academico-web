<?php

namespace App\Grpc\Eliza;

class LoginResponse
{
    public bool $success = false;
    public string $token = '';
    public string $message = '';

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

            if ($wireType === 0) { // Tipo Varint (ej. booleanos o números)
                $val = self::readVarint($binary, $offset);
                if ($fieldNumber === 1) {
                    $response->success = (bool) $val;
                }
            } elseif ($wireType === 2) { // Tipo delimitado por longitud (strings)
                $len = self::readVarint($binary, $offset);
                $str = substr($binary, $offset, $len);
                $offset += $len;

                if ($fieldNumber === 2) {
                    $response->token = $str;
                } elseif ($fieldNumber === 3) {
                    $response->message = $str;
                }
            } else {
                // Omitir otros tipos de datos no soportados
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
