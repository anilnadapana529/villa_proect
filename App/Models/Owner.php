<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Owner
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::instance();
    }

    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function login($email, $password)
    {
        $sql = "SELECT * FROM owners WHERE email=? LIMIT 1";
        $row = $this->fetch($sql, [$email]);

        if (!$row) return false;
        if (!password_verify($password, $row["password"])) return false;

        unset($row["password"]);
        return $row;
    }

    public function stats($ownerId)
    {
        return [
            "total_villas"      => $this->fetch("SELECT COUNT(*) AS n FROM villas WHERE owner_id=?", [$ownerId])["n"],
            "approved_villas"   => $this->fetch("SELECT COUNT(*) AS n FROM villas WHERE owner_id=? AND status='approved'", [$ownerId])["n"],
            "pending_bookings"  => $this->fetch("SELECT COUNT(*) AS n FROM bookings WHERE owner_id=? AND status='pending'", [$ownerId])["n"],
        ];
    }

    public function villas($ownerId)
    {
        return $this->fetchAll("SELECT * FROM villas WHERE owner_id=? ORDER BY id DESC", [$ownerId]);
    }

    public function myVillas($ownerId)
    {
        return $this->villas($ownerId);
    }

    public function bookings($ownerId)
    {
        return $this->fetchAll("
            SELECT b.*, v.title AS villa_name, v.image 
            FROM bookings b
            LEFT JOIN villas v ON b.villa_id=v.id
            WHERE b.owner_id=?
            ORDER BY b.id DESC
        ", [$ownerId]);
    }
}
