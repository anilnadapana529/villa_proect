<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class UserStats
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getStats(int $userId): array
    {
        return [
            "total_bookings" => $this->db->query("
                SELECT COUNT(*) FROM bookings WHERE user_id=$userId
            ")->fetch_row()[0],

            "confirmed_bookings" => $this->db->query("
                SELECT COUNT(*) FROM bookings 
                WHERE user_id=$userId AND status='confirmed'
            ")->fetch_row()[0],
        ];
    }
}
