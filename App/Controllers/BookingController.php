<?php

namespace App\Controllers;

use App\Core\Response;
use App\Middleware\AuthGuard;
use App\Models\Booking;

class BookingController
{
    /** ------------------------------
     *  CHECK AVAILABILITY
     *  ------------------------------ */
    public function check()
    {
        $villaId = $_GET["villa_id"] ?? 0;
        $start   = $_GET["start"] ?? "";
        $end     = $_GET["end"] ?? "";

        $bookingModel = new Booking();

        $available = $bookingModel->checkAvailability($villaId, $start, $end);

        Response::json([
            "status"     => true,
            "available"  => $available
        ]);
    }


    /** ------------------------------
     *  CREATE BOOKING (User Only)
     *  ------------------------------ */
    public function create()
    {
        $auth = AuthGuard::role("user");
        if (!$auth) {
            return Response::json(["status" => false, "message" => "Unauthorized"], 401);
        }

        $body = json_decode(file_get_contents("php://input"), true);

        $villaId = $body["villa_id"] ?? 0;
        $start   = $body["start_date"] ?? "";
        $end     = $body["end_date"] ?? "";

        $bookingModel = new Booking();

        // Check availability first
        if (!$bookingModel->checkAvailability($villaId, $start, $end)) {
            return Response::json([
                "status"  => false,
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
            "message" => "Booking created successfully"
        ]);
    }


    /** ------------------------------
     *  CALENDAR (Booked dates)
     *  ------------------------------ */
    public function calendar()
    {
        $villaId = $_GET["villa_id"] ?? 0;

        $bookingModel = new Booking();

        $booked = $bookingModel->calendar($villaId);

        Response::json([
            "status" => true,
            "dates"  => $booked
        ]);
    }


    /** ------------------------------
     *  OWNER BOOKING LIST
     *  ------------------------------ */
    public function ownerBookings()
    {
        $auth = AuthGuard::role("owner");

        if (!$auth) {
            return Response::json(["status" => false, "message" => "Unauthorized"], 401);
        }

        $bookingModel = new Booking();
        $bookings = $bookingModel->getByOwnerId($auth["id"]);

        Response::json([
            "status"   => true,
            "bookings" => $bookings
        ]);
    }
}

