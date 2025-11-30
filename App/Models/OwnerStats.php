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
        $stmt1 = $this->db->prepare("SELECT COUNT(*) FROM villas WHERE owner_id=?");
        $stmt1->bind_param("i", $ownerId);
        $stmt1->execute();
        $totalVillas = $stmt1->get_result()->fetch_row()[0];

        $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM villas WHERE owner_id=? AND status='approved'");
        $stmt2->bind_param("i", $ownerId);
        $stmt2->execute();
        $approvedVillas = $stmt2->get_result()->fetch_row()[0];

        $stmt3 = $this->db->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE villa_id IN (SELECT id FROM villas WHERE owner_id=?)
            AND status='pending'
        ");
        $stmt3->bind_param("i", $ownerId);
        $stmt3->execute();
        $pendingBookings = $stmt3->get_result()->fetch_row()[0];

        return [
            "total_villas" => $totalVillas,
            "approved_villas" => $approvedVillas,
            "pending_bookings" => $pendingBookings,
        ];
    }
}
