<?php

namespace App\Controllers;

use App\Core\Response;
use App\Middleware\AuthGuard;
use App\Models\User;
use App\Models\UserStats;
use App\Models\Booking;
use App\Models\Villa;

class UserController
{
    /** Must be logged-in user */
    private function requireUser(): array
    {
        $auth = AuthGuard::role("user");
        if (!$auth) {
            Response::json(["status" => false, "message" => "Unauthorized"], 401);
            exit;
        }
        return $auth;
    }


    /** ------------------------------
     *  GET USER PROFILE
     *  ------------------------------ */
    public function profile()
    {
        $auth = $this->requireUser();

        $profile = (new User())->profile($auth["id"]);

        Response::json([
            "status"  => true,
            "profile" => $profile
        ]);
    }


    /** ------------------------------
     *  UPDATE USER PROFILE
     *  ------------------------------ */
    public function updateProfile()
    {
        $auth = $this->requireUser();

        $body = json_decode(file_get_contents("php://input"), true);

        $data = [
            "name"  => $body["name"] ?? "",
            "email" => $body["email"] ?? "",
            "phone" => $body["phone"] ?? ""
        ];

        $db = \App\Core\Database::connect();

        $pairs = [];
        foreach ($data as $k => $v) {
            $v = $db->real_escape_string($v);
            $pairs[] = "$k='$v'";
        }
        $set = implode(",", $pairs);

        $db->query("UPDATE users SET $set WHERE id=" . $auth["id"]);

        Response::json([
            "status" => true,
            "message" => "Profile updated"
        ]);
    }


    /** ------------------------------
     *  LIST USER BOOKINGS
     *  ------------------------------ */
    public function bookings()
    {
        $auth = $this->requireUser();

        $bookings = (new User())->bookings($auth["id"]);

        Response::json([
            "status"   => true,
            "bookings" => $bookings
        ]);
    }


    /** ------------------------------
     *  CREATE BOOKING
     *  ------------------------------ */
    public function createBooking()
    {
        $auth = $this->requireUser();

        $body = json_decode(file_get_contents("php://input"), true);

        $villaId = $body["villa_id"] ?? 0;
        $start   = $body["start_date"] ?? "";
        $end     = $body["end_date"] ?? "";

        $bookingModel = new Booking();

        // Availability check
        $available = $bookingModel->checkAvailability($villaId, $start, $end);

        if (!$available) {
            return Response::json([
                "status" => false,
                "message" => "Selected dates not available"
            ], 422);
        }

        // Create booking
        $bookingModel->create([
            "villa_id"   => $villaId,
            "user_id"    => $auth["id"],
            "start_date" => $start,
            "end_date"   => $end
        ]);

        Response::json([
            "status"  => true,
            "message" => "Booking successful"
        ]);
    }
}

