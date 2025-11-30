<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Villa;
use App\Models\VillaImages;
use App\Models\Booking;

class VillaController
{
    /** ------------------------------
     *  LIST ALL APPROVED VILLAS (API endpoint)
     *  ------------------------------ */
    public function index()
    {
        $villaModel = new Villa();
        $villas = $villaModel->allApproved();

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
     *  LIST ALL APPROVED VILLAS (legacy)
     *  ------------------------------ */
    public function list()
    {
        return $this->index();
    }


    /** ------------------------------
     *  GET VILLA DETAIL (API endpoint)
     *  ------------------------------ */
    public function show($id = null)
    {
        if (!$id) {
            $id = $_GET["id"] ?? 0;
        }

        $villaModel = new Villa();
        $detail = $villaModel->getById($id);

        if (!$detail) {
            Response::json(["status" => false, "message" => "Villa not found"], 404);
            return;
        }

        // Map villa fields for Flutter compatibility
        $villa = $detail["villa"];
        $mapped = [
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
            'image' => $detail['images'][0] ?? null,
            'images' => $detail['images'] ?? [],
            'amenities' => $villa['amenities'] ?? '',
            'status' => $villa['status'] ?? 'pending',
            'rating' => $villa['average_rating'] ?? null,
            'created_at' => $villa['created_at'] ?? date('Y-m-d H:i:s'),
            'updated_at' => $villa['updated_at'] ?? date('Y-m-d H:i:s'),
        ];

        Response::json([
            "status" => true,
            "villa"  => $mapped,
            "images" => $detail["images"]
        ]);
    }

    /** ------------------------------
     *  GET VILLA DETAIL (legacy)
     *  ------------------------------ */
    public function detail()
    {
        return $this->show();
    }

    /** ------------------------------
     *  CREATE NEW VILLA
     *  ------------------------------ */
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(["status" => false, "message" => "Invalid data"], 400);
            return;
        }

        // Extract data
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $address = $data['address'] ?? '';
        $city = $data['city'] ?? '';
        $state = $data['state'] ?? '';
        $price = $data['price'] ?? 0;
        $guests = $data['guests'] ?? 1;
        $bedrooms = $data['bedrooms'] ?? 1;
        $bathrooms = $data['bathrooms'] ?? 1;

        // Get owner ID from auth (implement your auth logic)
        $owner_id = 1; // TODO: Get from authenticated user

        // Combine location
        $location = trim($city . ', ' . $state);

        // Insert villa
        $db = \App\Core\Database::connect();
        $stmt = $db->prepare("
            INSERT INTO villas
            (owner_id, name, description, address, location, price_per_night, guests, bedrooms, bathrooms, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");

        $stmt->bind_param(
            "issssdiii",
            $owner_id,
            $name,
            $description,
            $address,
            $location,
            $price,
            $guests,
            $bedrooms,
            $bathrooms
        );

        if ($stmt->execute()) {
            $villa_id = $db->insert_id;
            Response::json([
                "status" => true,
                "message" => "Villa created successfully",
                "villa_id" => $villa_id
            ]);
        } else {
            Response::json([
                "status" => false,
                "message" => "Failed to create villa"
            ], 500);
        }
    }

    /** ------------------------------
     *  UPDATE VILLA
     *  ------------------------------ */
    public function update($id = null)
    {
        if (!$id) {
            $id = $_GET["id"] ?? 0;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(["status" => false, "message" => "Invalid data"], 400);
            return;
        }

        // Build update query dynamically
        $fields = [];
        $values = [];
        $types = '';

        // Map Flutter fields to database fields
        $fieldMapping = [
            'name' => 'name',
            'description' => 'description',
            'address' => 'address',
            'price' => 'price_per_night',
            'guests' => 'guests',
            'bedrooms' => 'bedrooms',
            'bathrooms' => 'bathrooms'
        ];

        // Build location from city and state if provided
        if (isset($data['city']) && isset($data['state'])) {
            $fields[] = "location = ?";
            $values[] = trim($data['city'] . ', ' . $data['state']);
            $types .= 's';
        }

        foreach ($fieldMapping as $apiField => $dbField) {
            if (isset($data[$apiField])) {
                $fields[] = "$dbField = ?";
                $values[] = $data[$apiField];
                $types .= in_array($dbField, ['price_per_night']) ? 'd' : (in_array($dbField, ['guests', 'bedrooms', 'bathrooms']) ? 'i' : 's');
            }
        }

        if (empty($fields)) {
            Response::json(["status" => false, "message" => "No fields to update"], 400);
            return;
        }

        $values[] = $id;
        $types .= 'i';

        $db = \App\Core\Database::connect();
        $sql = "UPDATE villas SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            Response::json([
                "status" => true,
                "message" => "Villa updated successfully"
            ]);
        } else {
            Response::json([
                "status" => false,
                "message" => "Failed to update villa"
            ], 500);
        }
    }

    /** ------------------------------
     *  DELETE VILLA
     *  ------------------------------ */
    public function destroy($id = null)
    {
        if (!$id) {
            $id = $_GET["id"] ?? 0;
        }

        $db = \App\Core\Database::connect();
        $stmt = $db->prepare("DELETE FROM villas WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            Response::json([
                "status" => true,
                "message" => "Villa deleted successfully"
            ]);
        } else {
            Response::json([
                "status" => false,
                "message" => "Failed to delete villa"
            ], 500);
        }
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

