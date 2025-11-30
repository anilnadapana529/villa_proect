<?php

class Auth {

    public static function user() {
        $headers = getallheaders();
        if (!isset($headers["Authorization"])) {
            Response::error("Authorization token missing", 401);
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $payload = JWT::decode($token);

        if (!$payload) {
            Response::error("Invalid or expired token", 401);
        }

        return $payload; // contains user_id / role / email
    }

    public static function requireRole($role) {
        $payload = self::user();

        if ($payload["role"] !== $role) {
            Response::error("Access denied for this role", 403);
        }

        return $payload;
    }
}
