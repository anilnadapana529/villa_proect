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
}

