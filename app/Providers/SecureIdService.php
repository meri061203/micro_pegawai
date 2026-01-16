<?php

namespace App\Providers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class SecureIdService
{
    public static function make(string $purpose, int $id, int $userId, int $minutes = 10): string
    {
        $payload = [
            'sub'     => $id,
            'purpose' => $purpose,
            'uid'     => $userId,
            'exp'     => now()->addMinutes($minutes)->timestamp,
            'nonce'   => Str::random(10),
        ];

        return Crypt::encryptString(json_encode($payload));
    }

    public static function decode(string $token, string $purpose, int $userId): int
    {
        $json = Crypt::decryptString($token);
        $data = json_decode($json, true);

        // validasi
        if ($data['purpose'] !== $purpose) {
            abort(403, 'Purpose mismatch');
        }

        if ($data['uid'] !== $userId) {
            abort(403, 'Not your data');
        }

        if ($data['exp'] < now()->timestamp) {
            abort(403, 'Token expired');
        }

        return $data['sub']; // ID asli
    }
}
