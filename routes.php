<?php

use App\Core\Response;
use App\Core\Auth;

// ------------------------
// CORRECT ENDPOINT PARSER
// ------------------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove common path components
$uri = str_replace('/public/index.php', '', $uri);
$uri = str_replace('/index.php', '', $uri);
$uri = str_replace('/api', '', $uri);
$uri = trim($uri, '/');

// Get endpoint (last part of the path)
$parts = explode('/', $uri);
$endpoint = end($parts);

// Special handling for web page routes
$webRoutes = [
    '' => __DIR__ . '/web/pages/home.php',
    'admin' => __DIR__ . '/web/pages/login.php',
    'owner' => __DIR__ . '/web/pages/login.php',
    'user' => __DIR__ . '/web/pages/login.php',
    'login' => __DIR__ . '/web/pages/login.php',
    'register' => __DIR__ . '/web/pages/register.php',
    'logout' => __DIR__ . '/web/pages/logout.php',
    'villas' => __DIR__ . '/web/pages/villas.php',
    'dashboard' => __DIR__ . '/web/pages/user-dashboard.php',
    'admin-dashboard' => __DIR__ . '/web/pages/admin-dashboard.php',
    'owner-dashboard' => __DIR__ . '/web/pages/owner-dashboard.php',
];

// Set type parameter for role-specific logins
if (in_array($endpoint, ['admin', 'owner', 'user'])) {
    $_GET['type'] = $endpoint;
}

if (isset($webRoutes[$endpoint]) && file_exists($webRoutes[$endpoint])) {
    include $webRoutes[$endpoint];
    exit;
}

// Default to home-data for API if endpoint is empty
if (empty($endpoint)) {
    $endpoint = 'home-data';
}

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
        $methodName = str_replace('-', '_', $endpoint);
        (new App\Controllers\AuthController())->$methodName();
        exit;
}

// ============================================
// PROTECTED ROUTES (JWT Required)
// ============================================
$auth = Auth::validate();

if (!$auth["status"]) {
    Response::json(["status" => false, "message" => "Unauthorized"], 401);
    exit;
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
    if ($endpoint === "admin-users") $admin->users();
    if ($endpoint === "admin-bookings") $admin->bookings();
    if ($endpoint === "admin-payments") $admin->payments();
    exit;
}

// -------------------------------------------
// OWNER ROUTES
// -------------------------------------------
if ($role === "owner") {
    controller("OwnerController");
    $owner = new App\Controllers\OwnerController();

    if ($endpoint === "owner-stats") $owner->stats();
    if ($endpoint === "owner-villas") $owner->myVillas();
    if ($endpoint === "owner-add-villa") $owner->addVilla();
    if ($endpoint === "owner-edit-villa") $owner->updateVilla();
    if ($endpoint === "owner-delete-villa") $owner->deleteVilla();
    if ($endpoint === "owner-upload-images") $owner->uploadImages();
    if ($endpoint === "owner-bookings") $owner->bookings();
    exit;
}

// -------------------------------------------
// USER ROUTES
// -------------------------------------------
if ($role === "user") {
    controller("UserController");
    $user = new App\Controllers\UserController();

    if ($endpoint === "user-profile") $user->profile();
    if ($endpoint === "update-user-profile") $user->updateProfile();
    if ($endpoint === "user-bookings") $user->bookings();
    if ($endpoint === "user-create-booking") $user->createBooking();
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
