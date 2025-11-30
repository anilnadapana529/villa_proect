<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class OwnerStats
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getStats(int $ownerId): array
    {
        return [
            "total_villas" => $this->db->query("
                SELECT COUNT(*) FROM villas WHERE owner_id=$ownerId
            ")->fetch_row()[0],

            "approved_villas" => $this->db->query("
                SELECT COUNT(*) FROM villas 
                WHERE owner_id=$ownerId AND status='approved'
            ")->fetch_row()[0],

            "pending_bookings" => $this->db->query("
                SELECT COUNT(*) FROM bookings 
                WHERE villa_id IN (SELECT id FROM villas WHERE owner_id=$ownerId)
                AND status='pending'
            ")->fetch_row()[0],
        ];
    }
}
