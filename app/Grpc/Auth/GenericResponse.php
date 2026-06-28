<?php

namespace App\Grpc\Auth;

/**
 * Representación local de GenericResponse.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class GenericResponse
{
    public bool $success = false;
    public string $message = '';

    public function mergeFromString(string $binary): void
    {
        $decoded = self::decode($binary);
        $this->success = $decoded->success;
        $this->message = $decoded->message;
    }

    public static function decode(string $binary): self
    {
        $response = new self();
        $offset = 0;
        $length = strlen($binary);

        while ($offset < $length) {
            $tagVarint = self::readVarint($binary, $offset);
            $wireType = $tagVarint & 0x07;
            $fieldNumber = $tagVarint >> 3;

            if ($wireType === 0) {
                $value = self::readVarint($binary, $offset);
                if ($fieldNumber === 1) {
                    $response->success = (bool) $value;
                }
            } elseif ($wireType === 2) {
                $len = self::readVarint($binary, $offset);
                $str = substr($binary, $offset, $len);
                $offset += $len;

                if ($fieldNumber === 2) {
                    $response->message = $str;
                }
            } else {
                break;
            }
        }

        return $response;
    }

    private static function readVarint(string $binary, int &$offset): int
    {
        $value = 0;
        $shift = 0;
        while ($offset < strlen($binary)) {
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
