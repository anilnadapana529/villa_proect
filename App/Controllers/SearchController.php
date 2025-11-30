<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Villa;

class SearchController
{
    /**
     * Search villas by keyword
     * Endpoint: /search?keyword=...
     */
    public function search()
    {
        $keyword = $_GET["keyword"] ?? "";

        if (!$keyword || strlen($keyword) < 2) {
            return Response::json([
                "status" => false,
                "message" => "Keyword too short",
                "results" => []
            ]);
        }

        $villaModel = new Villa();
        $results = $villaModel->search($keyword);

        Response::json([
            "status" => true,
            "results" => $results
        ]);
    }

    /**
     * Advanced villa search with filters
     * Endpoint: /search-villas?location=...&check_in=...&check_out=...&guests=...
     */
    public function searchVillas()
    {
        $db = \App\Core\Database::connect();

        $location = $db->real_escape_string($_GET['location'] ?? '');
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';
        $guests = intval($_GET['guests'] ?? 0);
        $minPrice = intval($_GET['min_price'] ?? 0);
        $maxPrice = intval($_GET['max_price'] ?? 0);
        $amenities = $_GET['amenities'] ?? '';

        $query = "SELECT v.*,
                  (SELECT image FROM villa_images WHERE villa_id = v.id LIMIT 1) as image,
                  o.name as owner_name
                  FROM villas v
                  LEFT JOIN owners o ON v.owner_id = o.id
                  WHERE v.status = 'approved'";

        if ($location) {
            $query .= " AND (v.location LIKE '%$location%' OR v.address LIKE '%$location%' OR v.name LIKE '%$location%')";
        }

        if ($guests) {
            $query .= " AND v.guests >= $guests";
        }

        if ($minPrice) {
            $query .= " AND v.weekday_price >= $minPrice";
        }

        if ($maxPrice) {
            $query .= " AND v.weekday_price <= $maxPrice";
        }

        if ($amenities) {
            $amenityList = explode(',', $amenities);
            foreach ($amenityList as $amenity) {
                $amenity = $db->real_escape_string(trim($amenity));
                $query .= " AND v.amenities LIKE '%$amenity%'";
            }
        }

        if ($checkIn && $checkOut) {
            $query .= " AND v.id NOT IN (
                SELECT DISTINCT villa_id FROM bookings
                WHERE status IN ('confirmed', 'pending')
                AND (
                    (check_in <= '$checkIn' AND check_out > '$checkIn')
                    OR (check_in < '$checkOut' AND check_out >= '$checkOut')
                    OR (check_in >= '$checkIn' AND check_out <= '$checkOut')
                )
            )";
        }

        $query .= " ORDER BY v.created_at DESC";

        $result = $db->query($query);
        $villas = $result->fetch_all(MYSQLI_ASSOC);

        Response::json([
            "status" => true,
            "villas" => $villas,
            "count" => count($villas)
        ]);
    }
}
