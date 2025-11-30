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

        $stmt4 = $this->db->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE villa_id IN (SELECT id FROM villas WHERE owner_id=?)
        ");
        $stmt4->bind_param("i", $ownerId);
        $stmt4->execute();
        $totalBookings = $stmt4->get_result()->fetch_row()[0];

        $stmt5 = $this->db->prepare("
            SELECT COALESCE(SUM(total_amount), 0) FROM bookings
            WHERE villa_id IN (SELECT id FROM villas WHERE owner_id=?)
            AND status IN ('confirmed', 'completed')
        ");
        $stmt5->bind_param("i", $ownerId);
        $stmt5->execute();
        $totalEarnings = $stmt5->get_result()->fetch_row()[0];

        $stmt6 = $this->db->prepare("
            SELECT total_earnings, wallet_balance FROM owners WHERE id=?
        ");
        $stmt6->bind_param("i", $ownerId);
        $stmt6->execute();
        $ownerData = $stmt6->get_result()->fetch_assoc();

        return [
            "total_villas" => $totalVillas,
            "approved_villas" => $approvedVillas,
            "pending_bookings" => $pendingBookings,
            "total_bookings" => $totalBookings,
            "total_earnings" => $totalEarnings,
            "wallet_balance" => $ownerData['wallet_balance'] ?? 0,
            "lifetime_earnings" => $ownerData['total_earnings'] ?? 0,
        ];
    }
}
