<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Check if routes.php has the exit fix
$routesFile = __DIR__ . "/../routes.php";
$content = file_get_contents($routesFile);

// Check for the bug
$hasBug = strpos($content, 'Response::json(["status" => false, "message" => "Unauthorized"], 401);
}

$role') !== false;

// Check for the fix
$hasExit = strpos($content, 'Response::json(["status" => false, "message" => "Unauthorized"], 401);
    exit;
}

$role') !== false;

echo json_encode([
    "routes_file_exists" => file_exists($routesFile),
    "routes_file_size" => filesize($routesFile),
    "has_bug_missing_exit" => $hasBug,
    "has_exit_fix" => $hasExit,
    "status" => $hasExit ? "FIXED" : "NOT FIXED - UPLOAD routes.php!",
    "owner_model_exists" => file_exists(__DIR__ . "/../App/Models/Owner.php"),
    "admin_model_exists" => file_exists(__DIR__ . "/../App/Models/Admin.php")
]);
