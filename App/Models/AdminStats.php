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
        return [
            "total_villas"      => $this->db->query("SELECT COUNT(*) FROM villas")->fetch_row()[0],
            "pending_villas"    => $this->db->query("SELECT COUNT(*) FROM villas WHERE status='pending'")->fetch_row()[0],
            "total_owners"      => $this->db->query("SELECT COUNT(*) FROM owners")->fetch_row()[0],
            "pending_owners"    => $this->db->query("SELECT COUNT(*) FROM owners WHERE status='pending'")->fetch_row()[0],
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
