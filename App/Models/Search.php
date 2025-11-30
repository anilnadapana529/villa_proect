<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class Search
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Keyword search */
    public function search(string $keyword): array
    {
        $keyword = $this->db->real_escape_string($keyword);

        $query = "
            SELECT 
                id,
                title AS name,
                location AS city,
                price,
                (SELECT image FROM villa_images WHERE villa_id=v.id LIMIT 1) AS image
            FROM villas v
            WHERE 
                title LIKE '%$keyword%' 
                OR location LIKE '%$keyword%' 
                OR description LIKE '%$keyword%'
                AND status='approved'
        ";

        $q = $this->db->query($query);
        return $q->fetch_all(MYSQLI_ASSOC);
    }
}
