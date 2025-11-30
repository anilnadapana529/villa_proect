<?php
require_once "core/Model.php";

class User extends Model
{
    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
        $row = $this->fetch($sql, [$email]);

        if (!$row) return false;
        if (!password_verify($password, $row["password"])) return false;

        unset($row["password"]);
        return $row;
    }

    public function profile($userId)
    {
        return $this->fetch("
            SELECT id, name, email, phone 
            FROM users 
            WHERE id=?
        ", [$userId]);
    }

    public function updateProfile($id, $name, $email, $phone)
    {
        $this->query("
            UPDATE users 
            SET name=?, email=?, phone=? 
            WHERE id=?
        ", [$name, $email, $phone, $id]);

        return true;
    }

    public function bookings($userId)
    {
        return $this->fetchAll("
            SELECT b.*, v.title AS villa_name, v.image
            FROM bookings b
            LEFT JOIN villas v ON b.villa_id=v.id
            WHERE b.user_id=?
            ORDER BY b.id DESC
        ", [$userId]);
    }
}
