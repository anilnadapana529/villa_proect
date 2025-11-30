<?php

namespace App\Controllers;

use App\Core\Response;
use App\Middleware\AuthGuard;
use App\Models\Owner;
use App\Models\OwnerStats;
use App\Models\Villa;
use App\Models\VillaImages;
use App\Models\Booking;

class OwnerController
{
    /** Ensure only logged owner can access */
    private function requireOwner(): array
    {
        $auth = AuthGuard::role("owner");
        if (!$auth) {
            Response::json(["status" => false, "message" => "Unauthorized"], 401);
            exit;
        }
        return $auth;
    }


    /** ------------------------------
     *  OWNER DASHBOARD STATS
     *  ------------------------------ */
    public function stats()
    {
        $auth = $this->requireOwner();

        $stats = (new OwnerStats())->getStats($auth["id"]);

        Response::json([
            "status" => true,
            "stats"  => $stats
        ]);
    }


    /** ------------------------------
     *  LIST OWNER'S VILLAS
     *  ------------------------------ */
    public function myVillas()
    {
        $auth = $this->requireOwner();

        $villas = (new Owner())->myVillas($auth["id"]);

        Response::json([
            "status" => true,
            "villas" => $villas
        ]);
    }


    /** ------------------------------
     *  ADD A NEW VILLA
     *  ------------------------------ */
    public function addVilla()
    {
        $auth = $this->requireOwner();

        $body = json_decode(file_get_contents("php://input"), true);

        $data = [
            "title"     => $body["title"] ?? "",
            "price"     => $body["price"] ?? 0,
            "location"  => $body["location"] ?? "",
            "owner_id"  => $auth["id"],
        ];

        $villaId = (new Villa())->create($data);

        Response::json([
            "status" => true,
            "villa_id" => $villaId
        ]);
    }


    /** ------------------------------
     *  UPDATE VILLA
     *  ------------------------------ */
    public function updateVilla()
    {
        $auth = $this->requireOwner();

        $id = $_GET["id"] ?? 0;
        $body = json_decode(file_get_contents("php://input"), true);

        $data = [
            "title"     => $body["title"] ?? "",
            "price"     => $body["price"] ?? 0,
            "location"  => $body["location"] ?? ""
        ];

        (new Villa())->update($id, $data);

        Response::json(["status" => true]);
    }


    /** ------------------------------
     *  DELETE A VILLA
     *  ------------------------------ */
    public function deleteVilla()
    {
        $auth = $this->requireOwner();

        $id = $_GET["id"] ?? 0;

        (new Villa())->delete($id);

        Response::json(["status" => true]);
    }


    /** ------------------------------
     *  UPLOAD IMAGES FOR A VILLA
     *  ------------------------------ */
    public function uploadImages()
    {
        $auth = $this->requireOwner();

        $villaId = $_POST["villa_id"] ?? 0;

        if (!isset($_FILES["images"])) {
            Response::json(["status" => false, "message" => "No files"], 400);
            return;
        }

        (new VillaImages())->addImages($villaId, $_FILES["images"]);

        Response::json(["status" => true]);
    }


    /** ------------------------------
     *  OWNER BOOKING LIST
     *  (Bookings for all villas owned by the owner)
     *  ------------------------------ */
    public function bookings()
    {
        $auth = $this->requireOwner();

        $bookingModel = new Booking();

        $bookings = $bookingModel->getByOwnerId($auth["id"]);

        Response::json([
            "status" => true,
            "bookings" => $bookings
        ]);
    }
}

