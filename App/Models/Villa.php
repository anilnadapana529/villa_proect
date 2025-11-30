<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class Villa
{
    public int $id;
    public string $title;
    public string $location;
    public float $price;
    public int $owner_id;
    public string $status;
    public string $description;
    public array $images = [];

    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Fetch All Approved Villas */
    public function allApproved(): array
    {
        $q = $this->db->query("
            SELECT v.*, 
            (SELECT image FROM villa_images WHERE villa_id=v.id LIMIT 1) AS image
            FROM villas v
            WHERE v.status='approved'
            ORDER BY v.id DESC
        ");

        return $q->fetch_all(MYSQLI_ASSOC);
    }

    /** Fetch Villa Detail with Images */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM villas WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) return null;

        $villa = $result->fetch_assoc();

        $stmt2 = $this->db->prepare("SELECT image FROM villa_images WHERE villa_id=?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $images = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        return [
            "villa" => $villa,
            "images" => $images
        ];
    }

    /** Create a Villa (Owner Panel) */
    public function create(array $data): int
    {
        $title = $this->db->real_escape_string($data['title']);
        $location = $this->db->real_escape_string($data['location']);
        $price = $data['price'];
        $owner = $data['owner_id'];

        $this->db->query("
            INSERT INTO villas(title,location,price,owner_id,status)
            VALUES('$title','$location',$price,$owner,'pending')
        ");

        return $this->db->insert_id;
    }

    /** Update a villa */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE villas SET
            title=?,
            location=?,
            price=?
            WHERE id=?
        ");
        $stmt->bind_param("ssdi", $data['title'], $data['location'], $data['price'], $id);
        return $stmt->execute();
    }

    /** Delete villa */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM villas WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function search($keyword)
    {
        $keyword = $this->db->real_escape_string($keyword);

        $sql = "
            SELECT id, name, location
            FROM villas
            WHERE
                name LIKE '%{$keyword}%'
                OR location LIKE '%{$keyword}%'
                OR address LIKE '%{$keyword}%'
            AND status = 'approved'
        ";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
