<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Admin
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
        $sql = "SELECT * FROM admin WHERE email = ? LIMIT 1";
        $admin = $this->fetch($sql, [$email]);

        if (!$admin) return false;

        if (!password_verify($password, $admin["password"])) return false;

        unset($admin["password"]);
        return $admin;
    }

    public function stats()
    {
        return [
            "total_villas"   => $this->fetch("SELECT COUNT(*) AS n FROM villas")["n"],
            "pending_villas" => $this->fetch("SELECT COUNT(*) AS n FROM villas WHERE status='pending'")["n"],
            "total_owners"   => $this->fetch("SELECT COUNT(*) AS n FROM owners")["n"],
            "pending_owners" => $this->fetch("SELECT COUNT(*) AS n FROM owners WHERE status='pending'")["n"],
        ];
    }

    public function allOwners()
    {
        return $this->fetchAll("SELECT id, name, email, phone, status FROM owners ORDER BY id DESC");
    }

    public function owners()
    {
        return $this->allOwners();
    }

    public function allVillas()
    {
        return $this->fetchAll("
            SELECT v.*, o.name AS owner_name
            FROM villas v
            LEFT JOIN owners o ON v.owner_id = o.id
            ORDER BY v.id DESC
        ");
    }

    public function villas()
    {
        return $this->allVillas();
    }

    public function ownerDetail($id)
    {
        $owner = $this->fetch("SELECT id, name, email, phone, status FROM owners WHERE id=?", [$id]);
        if (!$owner) return null;

        $villas = $this->fetchAll("SELECT * FROM villas WHERE owner_id=?", [$id]);

        return [
            "owner" => $owner,
            "villas" => $villas
        ];
    }

    public function updateOwnerStatus($id, $status)
    {
        $this->query("UPDATE owners SET status=? WHERE id=?", [$status, $id]);
        return true;
    }

    public function updateVillaStatus($id, $status)
    {
        $this->query("UPDATE villas SET status=? WHERE id=?", [$status, $id]);
        return true;
    }

    public function pendingVillas()
    {
        return $this->fetchAll("
            SELECT v.*, o.name AS owner_name
            FROM villas v 
            LEFT JOIN owners o ON v.owner_id=o.id
            WHERE v.status='pending'
            ORDER BY v.id DESC
        ");
    }
}
