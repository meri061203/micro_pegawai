<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class LoginRateLimiter
{
    public static function attemptKey(string $username, string $ip): string
    {
        return 'login_attempts:' . sha1($username . '|' . $ip);
    }

    public static function lockKey(string $username, string $ip): string
    {
        return 'login_lock:' . sha1($username . '|' . $ip);
    }
}
