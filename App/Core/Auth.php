<?php

namespace App\Core;

use App\Core\Response;
use App\Helpers\JWT;

class Auth {

    public static function validate() {
        $headers = getallheaders();

        if (!isset($headers["Authorization"])) {
            return ["status" => false, "message" => "Authorization token missing"];
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $payload = JWT::decode($token);

        if (!$payload) {
            return ["status" => false, "message" => "Invalid or expired token"];
        }

        return [
            "status" => true,
            "user_id" => $payload["user_id"],
            "role" => $payload["role"],
            "email" => $payload["email"] ?? null
        ];
    }

    public static function user() {
        $result = self::validate();

        if (!$result["status"]) {
            Response::json(["status" => false, "message" => $result["message"]], 401);
            exit;
        }

        return $result;
    }

    public static function requireRole($role) {
        $payload = self::user();

        if ($payload["role"] !== $role) {
            Response::json(["status" => false, "message" => "Access denied for this role"], 403);
            exit;
        }

        return $payload;
    }
}
