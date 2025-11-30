<?php

namespace App\Middleware;

use App\Helpers\JWT;

class AuthGuard
{
    /** Protect any route */
    public static function check(): ?array
    {
        $headers = apache_request_headers();

        if (!isset($headers["Authorization"])) {
            return null;
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);

        return JWT::decode($token);
    }

    /** Protect routes by role */
    public static function role(string $requiredRole): ?array
    {
        $data = self::check();
        if (!$data) return null;

        return ($data["role"] === $requiredRole) ? $data : null;
    }
}
