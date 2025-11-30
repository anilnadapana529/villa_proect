<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Villa;
use App\Models\VillaImages;
use App\Models\Booking;

class VillaController
{
    /** ------------------------------
     *  LIST ALL APPROVED VILLAS
     *  ------------------------------ */
    public function list()
    {
        $villaModel = new Villa();
        $villas = $villaModel->allApproved();

        Response::json([
            "status" => true,
            "villas" => $villas
        ]);
    }


    /** ------------------------------
     *  GET VILLA DETAIL
     *  ------------------------------ */
    public function detail()
    {
        $id = $_GET["id"] ?? 0;

        $villaModel = new Villa();
        $detail = $villaModel->getById($id);

        if (!$detail) {
            Response::json(["status" => false, "message" => "Villa not found"], 404);
            return;
        }

        Response::json([
            "status" => true,
            "villa"  => $detail["villa"],
            "images" => $detail["images"]
        ]);
    }


    /** ------------------------------
     *  VILLA CALENDAR
     *  Returns booked + blocked dates
     *  ------------------------------ */
    public function calendar()
    {
        $id = $_GET["id"] ?? 0;

        $bookingModel = new Booking();
        $booked = $bookingModel->calendar($id);

        // (Optional) Blocked dates can be added later based on your db structure
        $blocked = []; 

        Response::json([
            "status"  => true,
            "booked"  => $booked,
            "blocked" => $blocked
        ]);
    }
}

