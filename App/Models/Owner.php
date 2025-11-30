<?php
require_once "core/Model.php";

class Owner extends Model
{
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
