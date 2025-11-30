<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
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
