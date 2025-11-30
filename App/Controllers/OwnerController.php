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
     *  OWNER DASHBOARD (Alias for stats)
     *  ------------------------------ */
    public function dashboard()
    {
        return $this->stats();
    }


    /** ------------------------------
     *  LIST OWNER'S VILLAS
     *  ------------------------------ */
    public function myVillas()
    {
        $auth = $this->requireOwner();

        $villas = (new Owner())->myVillas($auth["id"]);

        // Map fields for Flutter app compatibility
        $mapped = array_map(function($villa) {
            return [
                'id' => $villa['id'],
                'owner_id' => $villa['owner_id'],
                'title' => $villa['name'],
                'name' => $villa['name'],
                'description' => $villa['description'] ?? '',
                'location' => $villa['location'] ?? $villa['address'] ?? '',
                'city' => $villa['location'] ?? '',
                'state' => $villa['location'] ?? '',
                'address' => $villa['address'] ?? '',
                'price_per_night' => $villa['price_per_night'] ?? '0',
                'price' => $villa['price_per_night'] ?? '0',
                'bedrooms' => $villa['bedrooms'] ?? 0,
                'bathrooms' => $villa['bathrooms'] ?? 0,
                'max_guests' => $villa['guests'] ?? 1,
                'guests' => $villa['guests'] ?? 1,
                'image' => $villa['image'] ?? null,
                'images' => $villa['image'] ? [$villa['image']] : [],
                'amenities' => $villa['amenities'] ?? '',
                'status' => $villa['status'] ?? 'pending',
                'rating' => $villa['average_rating'] ?? null,
                'created_at' => $villa['created_at'] ?? date('Y-m-d H:i:s'),
                'updated_at' => $villa['updated_at'] ?? date('Y-m-d H:i:s'),
            ];
        }, $villas);

        Response::json([
            "status" => true,
            "villas" => $mapped
        ]);
    }

    /** ------------------------------
     *  LIST OWNER'S VILLAS (Alias)
     *  ------------------------------ */
    public function villas()
    {
        return $this->myVillas();
    }


    /** ------------------------------
     *  ADD A NEW VILLA
     *  ------------------------------ */
    public function addVilla()
    {
        $auth = $this->requireOwner();

        $db = \App\Core\Database::connect();

        $name = $db->real_escape_string($_POST['name'] ?? '');
        $location = $db->real_escape_string($_POST['location'] ?? '');
        $address = $db->real_escape_string($_POST['address'] ?? '');
        $description = $db->real_escape_string($_POST['description'] ?? '');
        $guests = intval($_POST['guests'] ?? 0);
        $bedrooms = intval($_POST['bedrooms'] ?? 0);
        $beds = intval($_POST['beds'] ?? 0);
        $bathrooms = intval($_POST['bathrooms'] ?? 0);
        $weekdayPrice = floatval($_POST['weekday_price'] ?? 0);
        $weekendPrice = floatval($_POST['weekend_price'] ?? 0);
        $amenities = $db->real_escape_string($_POST['amenities'] ?? '');

        $ownerId = $auth['id'];

        $query = "INSERT INTO villas (owner_id, name, location, address, description, amenities, guests, bedrooms, beds, bathrooms, weekday_price, weekend_price, status, created_at)
                  VALUES ($ownerId, '$name', '$location', '$address', '$description', '$amenities', $guests, $bedrooms, $beds, $bathrooms, $weekdayPrice, $weekendPrice, 'pending', NOW())";

        $db->query($query);
        $villaId = $db->insert_id;

        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/villas/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($_FILES['images']['name'] as $key => $filename) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$key];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $newFilename = time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $destination = $uploadDir . $newFilename;

                    if (move_uploaded_file($tmpName, $destination)) {
                        $db->query("INSERT INTO villa_images (villa_id, image) VALUES ($villaId, '$newFilename')");
                    }
                }
            }
        }

        Response::json([
            "status" => true,
            "villa_id" => $villaId,
            "message" => "Villa submitted for approval"
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

