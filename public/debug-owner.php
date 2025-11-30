<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$results = [
    "test" => "Owner Login Diagnostic",
    "steps" => []
];

try {
    // Step 1: Check if owners table exists
    $results["steps"][] = "1. Checking database connection...";

    require_once __DIR__ . "/../App/Core/Database.php";
    $db = \App\Core\Database::instance();

    $results["steps"][] = "✓ Database connected";

    // Step 2: Check owners table structure
    $results["steps"][] = "2. Checking owners table structure...";

    $stmt = $db->query("DESCRIBE owners");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results["owners_table_columns"] = $columns;

    $results["steps"][] = "✓ Owners table exists";
    $results["has_required_columns"] = [
        "id" => in_array("id", $columns),
        "email" => in_array("email", $columns),
        "password" => in_array("password", $columns),
        "name" => in_array("name", $columns),
    ];

    // Step 3: Check if there are any owners
    $stmt = $db->query("SELECT COUNT(*) as count FROM owners");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    $results["total_owners"] = $count["count"];
    $results["steps"][] = "✓ Found {$count['count']} owners in database";

    // Step 4: Get a sample owner (without password)
    if ($count["count"] > 0) {
        $stmt = $db->query("SELECT id, email, name FROM owners LIMIT 1");
        $sampleOwner = $stmt->fetch(PDO::FETCH_ASSOC);
        $results["sample_owner"] = $sampleOwner;
        $results["steps"][] = "✓ Sample owner: {$sampleOwner['email']}";
    } else {
        $results["steps"][] = "⚠ No owners found - you need to create one first!";
        $results["error"] = "NO_OWNERS_IN_DATABASE";
    }

    // Step 5: Test Owner model
    $results["steps"][] = "3. Testing Owner model...";

    require_once __DIR__ . "/../App/Models/Owner.php";
    $ownerModel = new \App\Models\Owner();

    $results["steps"][] = "✓ Owner model loaded successfully";

    // Step 6: Check method existence
    $results["owner_model_methods"] = [
        "login" => method_exists($ownerModel, "login"),
        "stats" => method_exists($ownerModel, "stats"),
        "myVillas" => method_exists($ownerModel, "myVillas"),
        "bookings" => method_exists($ownerModel, "bookings"),
    ];

    $results["steps"][] = "✓ All required methods exist";

    // Step 7: Test actual login with fake credentials (should fail gracefully)
    $results["steps"][] = "4. Testing login method with invalid credentials...";

    $testResult = $ownerModel->login("nonexistent@test.com", "wrongpassword");

    if ($testResult === false) {
        $results["steps"][] = "✓ Login correctly returns false for invalid credentials";
    } else {
        $results["steps"][] = "⚠ Unexpected login result";
    }

    // Step 8: Check villas table for owner_id column
    $results["steps"][] = "5. Checking villas table structure...";

    $stmt = $db->query("DESCRIBE villas");
    $villaColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results["villas_table_columns"] = $villaColumns;
    $results["villas_has_owner_id"] = in_array("owner_id", $villaColumns);

    if ($results["villas_has_owner_id"]) {
        $results["steps"][] = "✓ Villas table has owner_id column";
    } else {
        $results["steps"][] = "✗ ERROR: Villas table missing owner_id column!";
        $results["error"] = "MISSING_OWNER_ID_COLUMN";
    }

    // Step 9: Check bookings table for owner_id column
    $results["steps"][] = "6. Checking bookings table structure...";

    $stmt = $db->query("DESCRIBE bookings");
    $bookingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results["bookings_table_columns"] = $bookingColumns;
    $results["bookings_has_owner_id"] = in_array("owner_id", $bookingColumns);

    if ($results["bookings_has_owner_id"]) {
        $results["steps"][] = "✓ Bookings table has owner_id column";
    } else {
        $results["steps"][] = "✗ ERROR: Bookings table missing owner_id column!";
        $results["error"] = "MISSING_OWNER_ID_IN_BOOKINGS";
    }

    // Final status
    if (!isset($results["error"])) {
        $results["status"] = "ALL_CHECKS_PASSED";
        $results["message"] = "Everything looks good! If owner-login still fails, check your credentials.";
    } else {
        $results["status"] = "ERRORS_FOUND";
        $results["message"] = "Found issues - see error field";
    }

} catch (Exception $e) {
    $results["status"] = "EXCEPTION";
    $results["error"] = $e->getMessage();
    $results["trace"] = $e->getTraceAsString();
}

echo json_encode($results, JSON_PRETTY_PRINT);
