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
        $stmt = $this->db->prepare("
            SELECT id, image
            FROM villa_images
            WHERE villa_id = ?
        ");
        $stmt->bind_param("i", $villaId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /** Add multiple images */
    public function addImages(int $villaId, array $files): bool
    {
        $stmt = $this->db->prepare("INSERT INTO villa_images(villa_id, image) VALUES(?, ?)");

        foreach ($files['tmp_name'] as $i => $tmp) {
            $name = time() . "_{$files['name'][$i]}";
            move_uploaded_file($tmp, "uploads/villas/$name");

            $stmt->bind_param("is", $villaId, $name);
            $stmt->execute();
        }

        return true;
    }

    /** Delete a single image */
    public function deleteImage(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM villa_images WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
