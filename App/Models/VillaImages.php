<?php

namespace App\Models;

use App\Core\Database;
use mysqli;

class VillaImages
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Get images for a villa */
    public function getByVilla(int $villaId): array
    {
        $q = $this->db->query("
            SELECT id, image 
            FROM villa_images
            WHERE villa_id = $villaId
        ");

        return $q->fetch_all(MYSQLI_ASSOC);
    }

    /** Add multiple images */
    public function addImages(int $villaId, array $files): bool
    {
        foreach ($files['tmp_name'] as $i => $tmp) {
            $name = time() . "_{$files['name'][$i]}";
            move_uploaded_file($tmp, "uploads/villas/$name");

            $this->db->query("
                INSERT INTO villa_images(villa_id,image)
                VALUES($villaId, '$name')
            ");
        }

        return true;
    }

    /** Delete a single image */
    public function deleteImage(int $id): bool
    {
        return $this->db->query("DELETE FROM villa_images WHERE id=$id");
    }
}
