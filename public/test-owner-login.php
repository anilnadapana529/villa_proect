<?php
// Simulate the owner-login endpoint
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off to catch clean output

ob_start(); // Capture any accidental output

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try {
    // Load dependencies
    require_once __DIR__ . "/../App/Core/Database.php";
    require_once __DIR__ . "/../App/Core/Response.php";
    require_once __DIR__ . "/../App/Helpers/JWT.php";
    require_once __DIR__ . "/../App/Models/Owner.php";

    // Get POST data
    $rawInput = file_get_contents("php://input");
    $body = json_decode($rawInput, true);

    // For testing, allow GET params too
    $email = $body["email"] ?? $_GET["email"] ?? "owner@example.com";
    $password = $body["password"] ?? $_GET["password"] ?? "owner123";

    $ownerModel = new \App\Models\Owner();
    $owner = $ownerModel->login($email, $password);

    if (!$owner) {
        $output = ob_get_clean();
        echo json_encode([
            "status" => false,
            "message" => "Invalid credentials",
            "debug" => [
                "captured_output" => $output,
                "test_email" => $email
            ]
        ], JSON_PRETTY_PRINT);
        exit;
    }

    // Generate token
    $token = \App\Helpers\JWT::encode([
        "user_id" => $owner["id"],
        "email"   => $owner["email"],
        "role"    => "owner"
    ]);

    $output = ob_get_clean();

    echo json_encode([
        "status" => true,
        "message" => "Login successful",
        "token" => $token,
        "owner" => [
            "id" => $owner["id"],
            "email" => $owner["email"],
            "name" => $owner["name"] ?? ""
        ],
        "debug" => [
            "captured_output" => $output,
            "output_length" => strlen($output)
        ]
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $output = ob_get_clean();
    
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage(),
        "trace" => explode("\n", $e->getTraceAsString()),
        "captured_output" => $output
    ], JSON_PRETTY_PRINT);
}
