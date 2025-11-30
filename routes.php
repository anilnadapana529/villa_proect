<?php

use App\Core\Response;
use App\Core\Auth;

// REMOVE WRONG CODE
// $uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
// $endpoint = $uri[count($uri) - 1];

// ------------------------
// CORRECT ENDPOINT PARSER
// ------------------------
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");

// Remove domain and base folder
$path = str_replace("index.php", "", $path);
$path = str_replace("api.php", "", $path);

// endpoint = last part
$endpoint = basename($path);

// -------------------------------------------
// Helper: Require Controller
// -------------------------------------------
function controller($name) {
    require_once __DIR__ . "/App/Controllers/{$name}.php";
}

// -------------------------------------------
// PUBLIC ENDPOINTS (No Auth Needed)
// -------------------------------------------
switch ($endpoint) {

    case "home-data":
        controller("HomeController");
        (new App\Controllers\HomeController())->homeData();
        exit;

    case "search":
        controller("SearchController");
        (new App\Controllers\SearchController())->search();
        exit;

    case "admin-login":
    case "owner-login":
    case "user-login":
        controller("AuthController");
        (new App\Controllers\AuthController())->$endpoint();
        exit;
}

// ============================================
// PROTECTED ROUTES (JWT Required)
// ============================================
$auth = Auth::validate();

if (!$auth["status"]) {
    Response::json(["status" => false, "message" => "Unauthorized"], 401);
}

$role = $auth["role"];
$userId = $auth["user_id"];

// -------------------------------------------
// ADMIN ROUTES
// -------------------------------------------
if ($role === "admin") {
    controller("AdminController");
    $admin = new App\Controllers\AdminController();

    if ($endpoint === "admin-stats") $admin->stats();
    if ($endpoint === "admin-owners") $admin->owners();
    if ($endpoint === "admin-owner-detail") $admin->ownerDetail();
    if ($endpoint === "admin-villas") $admin->villas();
    if ($endpoint === "admin-approve-villa") $admin->approveVilla();
    if ($endpoint === "admin-reject-villa") $admin->rejectVilla();
    exit;
}

// -------------------------------------------
// OWNER ROUTES
// -------------------------------------------
if ($role === "owner") {
    controller("OwnerController");
    $owner = new App\Controllers\OwnerController();

    if ($endpoint === "owner-stats") $owner->stats($userId);
    if ($endpoint === "owner-villas") $owner->myVillas($userId);
    if ($endpoint === "owner-add-villa") $owner->addVilla($userId);
    if ($endpoint === "owner-edit-villa") $owner->editVilla($userId);
    if ($endpoint === "owner-delete-villa") $owner->deleteVilla($userId);
    if ($endpoint === "owner-upload-images") $owner->uploadImages($userId);
    exit;
}

// -------------------------------------------
// USER ROUTES
// -------------------------------------------
if ($role === "user") {
    controller("UserController");
    $user = new App\Controllers\UserController();

    if ($endpoint === "user-profile") $user->profile($userId);
    if ($endpoint === "update-user-profile") $user->updateProfile($userId);
    if ($endpoint === "user-bookings") $user->bookings($userId);
    if ($endpoint === "user-create-booking") $user->createBooking($userId);
    exit;
}

// -------------------------------------------
// VILLA ROUTES (public for auth users)
// -------------------------------------------
controller("VillaController");
controller("BookingController");

$villa = new App\Controllers\VillaController();
$booking = new App\Controllers\BookingController();

if ($endpoint === "villa-detail") $villa->detail();
if ($endpoint === "villa-list") $villa->list();
if ($endpoint === "villa-calendar") $booking->calendar();
if ($endpoint === "check-availability") $booking->check();

Response::json(["status" => false, "message" => "Invalid endpoint"], 404);
