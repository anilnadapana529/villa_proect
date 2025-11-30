<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$routesFile = __DIR__ . "/../routes.php";
$content = file_get_contents($routesFile);

// Get lines 54-62
$lines = explode("\n", $content);
$relevantLines = array_slice($lines, 53, 10); // lines 54-63 (0-indexed)

// What SHOULD be there
$correctCode = 'if (!$auth["status"]) {
    Response::json(["status" => false, "message" => "Unauthorized"], 401);
    exit;
}';

// What is probably there (the bug)
$buggyCode = 'if (!$auth["status"]) {
    Response::json(["status" => false, "message" => "Unauthorized"], 401);
}';

$currentCode = implode("\n", array_slice($lines, 55, 4)); // lines 56-59

echo json_encode([
    "file_size" => filesize($routesFile),
    "lines_54_to_63" => $relevantLines,
    "current_code_lines_56_59" => $currentCode,
    "has_exit" => (strpos($content, 'Response::json(["status" => false, "message" => "Unauthorized"], 401);
    exit;') !== false),
    "instruction" => "Look at lines_54_to_63 - Does line 3 have 'exit;'?",
    "problem" => "The file on your server is different from the fixed version",
    "solution" => "Re-upload routes.php from this project folder, REPLACE the existing file completely"
], JSON_PRETTY_PRINT);
