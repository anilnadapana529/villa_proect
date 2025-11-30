<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class AdminStats
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Dashboard totals */
    public function getStats(): array
    {
        $totalVillas = $this->db->query("SELECT COUNT(*) FROM villas")->fetch_row()[0];
        $pendingVillas = $this->db->query("SELECT COUNT(*) FROM villas WHERE status='pending'")->fetch_row()[0];
        $activeVillas = $this->db->query("SELECT COUNT(*) FROM villas WHERE status='approved'")->fetch_row()[0];

        $totalOwners = $this->db->query("SELECT COUNT(*) FROM owners")->fetch_row()[0];
        $pendingOwners = $this->db->query("SELECT COUNT(*) FROM owners WHERE status='pending'")->fetch_row()[0];

        $totalUsers = $this->db->query("SELECT COUNT(*) FROM users")->fetch_row()[0];

        $totalBookings = $this->db->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0];
        $totalRevenue = $this->db->query("SELECT SUM(total_amount) FROM bookings WHERE status IN ('confirmed', 'completed')")->fetch_row()[0] ?? 0;

        $pendingReviews = $this->db->query("SELECT COUNT(*) FROM reviews WHERE status='pending'")->fetch_row()[0];

        return [
            "total_villas"      => $totalVillas,
            "pending_villas"    => $pendingVillas,
            "active_villas"     => $activeVillas,
            "total_owners"      => $totalOwners,
            "pending_owners"    => $pendingOwners,
            "total_users"       => $totalUsers,
            "total_bookings"    => $totalBookings,
            "total_revenue"     => $totalRevenue,
            "pending_reviews"   => $pendingReviews,
        ];
    }

    /** Pending villas */
    public function pendingVillas(): array
    {
        $q = $this->db->query("
            SELECT v.*, 
            (SELECT name FROM owners WHERE id=v.owner_id) AS owner_name,
            (SELECT image FROM villa_images WHERE villa_id=v.id LIMIT 1) AS image
            FROM villas v
            WHERE v.status='pending'
        ");

        return $q->fetch_all(MYSQLI_ASSOC);
    }
}
