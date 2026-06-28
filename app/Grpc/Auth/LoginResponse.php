<?php

namespace App\Grpc\Auth;

/**
 * Representación local de LoginResponse.
 * (Nota: Este archivo será sobrescrito cuando generes las clases reales usando 'protoc').
 */
class LoginResponse
{
    public string $accessToken = '';
    public string $refreshToken = '';
    public bool $mfaRequired = false;
    public bool $requiresAppUpdate = false;
    public string $tokenType = '';
    public int $expiresIn = 0;
    public string $sessionId = '';

    public function mergeFromString(string $binary): void
    {
        $decoded = self::decode($binary);
        $this->accessToken = $decoded->accessToken;
        $this->refreshToken = $decoded->refreshToken;
        $this->mfaRequired = $decoded->mfaRequired;
        $this->requiresAppUpdate = $decoded->requiresAppUpdate;
        $this->tokenType = $decoded->tokenType;
        $this->expiresIn = $decoded->expiresIn;
        $this->sessionId = $decoded->sessionId;
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

            if ($wireType === 2) {
                $len = self::readVarint($binary, $offset);
                $str = substr($binary, $offset, $len);
                $offset += $len;

                if ($fieldNumber === 1) {
                    $response->accessToken = $str;
                } elseif ($fieldNumber === 2) {
                    $response->refreshToken = $str;
                } elseif ($fieldNumber === 5) {
                    $response->tokenType = $str;
                } elseif ($fieldNumber === 7) {
                    $response->sessionId = $str;
                }
            } elseif ($wireType === 0) {
                $value = self::readVarint($binary, $offset);
                if ($fieldNumber === 3) {
                    $response->mfaRequired = (bool) $value;
                } elseif ($fieldNumber === 4) {
                    $response->requiresAppUpdate = (bool) $value;
                } elseif ($fieldNumber === 6) {
                    $response->expiresIn = $value;
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
