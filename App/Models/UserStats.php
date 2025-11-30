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
        $stmt1 = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id=?");
        $stmt1->bind_param("i", $userId);
        $stmt1->execute();
        $totalBookings = $stmt1->get_result()->fetch_row()[0];

        $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id=? AND status='confirmed'");
        $stmt2->bind_param("i", $userId);
        $stmt2->execute();
        $confirmedBookings = $stmt2->get_result()->fetch_row()[0];

        return [
            "total_bookings" => $totalBookings,
            "confirmed_bookings" => $confirmedBookings,
        ];
    }
}
