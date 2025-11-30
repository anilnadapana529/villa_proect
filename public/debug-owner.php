<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

echo json_encode([
    "step" => "1 - Basic PHP working",
    "php_version" => phpversion()
]);

// Test autoloader
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $path = __DIR__ . "/../" . $class . ".php";

    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
});

try {
    require_once __DIR__ . "/../App/Core/Database.php";
    require_once __DIR__ . "/../App/Helpers/JWT.php";

    echo json_encode([
        "step" => "2 - Core files loaded"
    ]);

    // Test JWT decode
    $headers = apache_request_headers();
    if (!isset($headers["Authorization"])) {
        echo json_encode([
            "error" => "No Authorization header",
            "headers" => $headers
        ]);
        exit;
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);

    echo json_encode([
        "step" => "3 - Token extracted",
        "token_preview" => substr($token, 0, 20) . "..."
    ]);

    $payload = \App\Helpers\JWT::decode($token);

    if (!$payload) {
        echo json_encode([
            "error" => "Invalid token"
        ]);
        exit;
    }

    echo json_encode([
        "step" => "4 - Token decoded",
        "payload" => $payload
    ]);

    // Test database connection
    $db = \App\Core\Database::instance();

    echo json_encode([
        "step" => "5 - Database connected"
    ]);

    // Test Owner model
    require_once __DIR__ . "/../App/Models/Owner.php";
    $owner = new \App\Models\Owner();

    echo json_encode([
        "step" => "6 - Owner model loaded"
    ]);

    // Test query
    $villas = $owner->myVillas($payload["user_id"]);

    echo json_encode([
        "success" => true,
        "step" => "7 - Query successful",
        "villas_count" => count($villas),
        "villas" => $villas
    ]);

} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "trace" => $e->getTraceAsString()
    ]);
}
