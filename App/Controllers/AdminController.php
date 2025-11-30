<?php

namespace App\Controllers;

use App\Core\Response;
use App\Middleware\AuthGuard;
use App\Models\AdminStats;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Villa;

class AdminController
{
    /** Validate admin token */
    private function requireAdmin()
    {
        $auth = AuthGuard::role("admin");
        if (!$auth) {
            Response::json(["status" => false, "message" => "Unauthorized"], 401);
            exit;
        }
        return $auth;
    }

    /** ------------------------------
     *  GET ADMIN DASHBOARD STATS
     *  ------------------------------ */
    public function stats()
    {
        $this->requireAdmin();

        $stats = (new AdminStats())->getStats();

        Response::json([
            "status" => true,
            "stats"  => $stats
        ]);
    }

    /** ------------------------------
     *  GET PENDING VILLAS
     *  ------------------------------ */
    public function pendingVillas()
    {
        $this->requireAdmin();

        $pending = (new AdminStats())->pendingVillas();

        Response::json([
            "status" => true,
            "pending_villas" => $pending
        ]);
    }

    /** ------------------------------
     *  LIST ALL OWNERS
     *  ------------------------------ */
    public function owners()
    {
        $this->requireAdmin();

        $owners = (new Admin())->owners();

        Response::json([
            "status" => true,
            "owners" => $owners
        ]);
    }

    /** ------------------------------
     *  OWNER DETAIL (PROFILE + HIS VILLAS)
     *  ------------------------------ */
    public function ownerDetail()
    {
        $this->requireAdmin();

        $id = $_GET["id"] ?? 0;
        $detail = (new Admin())->ownerDetail($id);

        Response::json([
            "status" => true,
            "owner"  => $detail["owner"],
            "villas" => $detail["villas"]
        ]);
    }

    /** ------------------------------
     *  UPDATE OWNER STATUS (APPROVE / REJECT)
     *  ------------------------------ */
    public function updateOwnerStatus()
    {
        $this->requireAdmin();

        $id = $_GET["id"] ?? 0;
        $status = $_GET["status"] ?? "pending";

        $db = \App\Core\Database::connect();
        $db->query("UPDATE owners SET status='$status' WHERE id=$id");

        Response::json(["status" => true]);
    }

    /** ------------------------------
     *  LIST ALL VILLAS
     *  ------------------------------ */
    public function villas()
    {
        $this->requireAdmin();

        $villas = (new Admin())->villas();

        Response::json([
            "status" => true,
            "villas" => $villas
        ]);
    }

    /** ------------------------------
     *  APPROVE VILLA
     *  ------------------------------ */
    public function approveVilla()
    {
        $this->requireAdmin();

        $id = $_GET["id"] ?? 0;
        $db = \App\Core\Database::connect();
        $db->query("UPDATE villas SET status='approved' WHERE id=$id");

        Response::json(["status" => true]);
    }

    /** ------------------------------
     *  REJECT VILLA
     *  ------------------------------ */
    public function rejectVilla()
    {
        $this->requireAdmin();

        $id = $_GET["id"] ?? 0;
        $db = \App\Core\Database::connect();
        $db->query("UPDATE villas SET status='rejected' WHERE id=$id");

        Response::json(["status" => true]);
    }

    /** ------------------------------
     *  LIST ALL USERS
     *  ------------------------------ */
    public function users()
    {
        $this->requireAdmin();

        $db = \App\Core\Database::connect();
        $result = $db->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $result->fetch_all(MYSQLI_ASSOC);

        Response::json([
            "status" => true,
            "users" => $users
        ]);
    }

    /** ------------------------------
     *  LIST ALL BOOKINGS
     *  ------------------------------ */
    public function bookings()
    {
        $this->requireAdmin();

        $db = \App\Core\Database::connect();
        $result = $db->query("
            SELECT b.*,
                   v.name as villa_name,
                   u.name as user_name,
                   o.name as owner_name
            FROM bookings b
            LEFT JOIN villas v ON b.villa_id = v.id
            LEFT JOIN users u ON b.user_id = u.id
            LEFT JOIN owners o ON b.owner_id = o.id
            ORDER BY b.created_at DESC
        ");
        $bookings = $result->fetch_all(MYSQLI_ASSOC);

        Response::json([
            "status" => true,
            "bookings" => $bookings
        ]);
    }

    /** ------------------------------
     *  LIST ALL PAYMENTS
     *  ------------------------------ */
    public function payments()
    {
        $this->requireAdmin();

        $db = \App\Core\Database::connect();
        $result = $db->query("
            SELECT p.*,
                   u.name as user_name,
                   u.email as user_email
            FROM payments p
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
        ");
        $payments = $result->fetch_all(MYSQLI_ASSOC);

        Response::json([
            "status" => true,
            "payments" => $payments
        ]);
    }
}

