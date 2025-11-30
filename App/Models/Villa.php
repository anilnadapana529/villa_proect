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
        $villa = $this->db->query("SELECT * FROM villas WHERE id=$id LIMIT 1");

        if ($villa->num_rows === 0) return null;

        $villa = $villa->fetch_assoc();

        $images = $this->db->query("
            SELECT image FROM villa_images WHERE villa_id=$id
        ")->fetch_all(MYSQLI_ASSOC);

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
        $title = $this->db->real_escape_string($data['title']);
        $location = $this->db->real_escape_string($data['location']);
        $price = $data['price'];

        return $this->db->query("
            UPDATE villas SET 
            title='$title',
            location='$location',
            price=$price
            WHERE id=$id
        ");
    }

    /** Delete villa */
    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM villas WHERE id=$id");
    }
    public function search($keyword)
{
    $sql = "
        SELECT id, title AS name, city, country, image
        FROM villas
        WHERE 
            title LIKE :kw
            OR city LIKE :kw
            OR country LIKE :kw
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ":kw" => "%$keyword%"
    ]);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}
