<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * ------------------------------------------
 *  VILLA BOOKING API – MAIN ENTRY FILE
 * ------------------------------------------
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// -----------------------------------------------------
// AUTOLOADER
// -----------------------------------------------------
spl_autoload_register(function ($class) {
    // Convert namespace → folder path
    $class = str_replace("\\", "/", $class);

    $path1 = __DIR__ . "/../App/" . $class . ".php";   // Correct new folder
    $path2 = __DIR__ . "/../" . $class . ".php";       // Fallback

    if (file_exists($path1)) {
        require_once $path1;
        return;
    }

    if (file_exists($path2)) {
        require_once $path2;
        return;
    }
});



// -----------------------------------------------------
// LOAD CORE FILES
// -----------------------------------------------------
require_once __DIR__ . "/App/Core/Database.php";
require_once __DIR__ . "/App/Core/Response.php";
require_once __DIR__ . "/App/Core/Auth.php";

// -----------------------------------------------------
// LOAD ROUTER
// -----------------------------------------------------
$routes = __DIR__ . "routes.php";

if (!file_exists($routes)) {
    \App\Core\Response::json([
        "status" => false,
        "message" => "Router not found"
    ], 500);
    exit;
}

require_once $routes;
