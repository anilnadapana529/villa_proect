<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;
use App\Helpers\JWT;
use App\Core\Response;

class AuthController
{
    /** ------------------------------
     *  ADMIN LOGIN
     *  ------------------------------ */
    public function admin_login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $email = $body["email"] ?? "";
        $password = $body["password"] ?? "";

        $admin = (new Admin())->login($email, $password);

        if (!$admin) {
            return Response::json([
                "status"  => false,
                "message" => "Invalid admin credentials"
            ], 401);
        }

        $token = JWT::encode([
            "user_id" => $admin["id"],
            "role" => "admin",
            "email" => $admin["email"]
        ]);

        return Response::json([
            "status" => true,
            "token"  => $token,
            "admin"  => $admin
        ]);
    }


    /** ------------------------------
     *  OWNER LOGIN
     *  ------------------------------ */
    public function owner_login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $email = $body["email"] ?? "";
        $password = $body["password"] ?? "";

        $owner = (new Owner())->login($email, $password);

        if (!$owner) {
            return Response::json([
                "status"  => false,
                "message" => "Invalid owner credentials"
            ], 401);
        }

        $token = JWT::encode([
            "user_id" => $owner["id"],
            "role" => "owner",
            "email" => $owner["email"]
        ]);

        return Response::json([
            "status" => true,
            "token"  => $token,
            "owner"  => $owner
        ]);
    }


    /** ------------------------------
     *  USER LOGIN
     *  ------------------------------ */
    public function user_login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $email = $body["email"] ?? "";
        $password = $body["password"] ?? "";

        $user = (new User())->login($email, $password);

        if (!$user) {
            return Response::json([
                "status"  => false,
                "message" => "Invalid user credentials"
            ], 401);
        }

        $token = JWT::encode([
            "user_id" => $user["id"],
            "role" => "user",
            "email" => $user["email"]
        ]);

        return Response::json([
            "status" => true,
            "token"  => $token,
            "user"   => $user
        ]);
    }
}

