<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class Home
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Full homepage data */
    public function homeData(): array
    {
        $sliders = [
            "https://yourdomain.com/uploads/sliders/1.jpg",
            "https://yourdomain.com/uploads/sliders/2.jpg"
        ];

        $categories = [
            ["name" => "Beach Villas", "icon" => "https://yourdomain.com/icons/beach.png"],
            ["name" => "Luxury Villas", "icon" => "https://yourdomain.com/icons/luxury.png"],
            ["name" => "Family Villas", "icon" => "https://yourdomain.com/icons/family.png"],
        ];

        $listings = $this->db->query("
            SELECT id,title,price,
            (SELECT image FROM villa_images WHERE villa_id=v.id LIMIT 1) AS image
            FROM villas v
            WHERE status='approved'
            LIMIT 10
        ")->fetch_all(MYSQLI_ASSOC);

        return [
            "sliders" => $sliders,
            "categories" => $categories,
            "listings" => $listings,
        ];
    }
}
