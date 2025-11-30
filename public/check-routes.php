<?php
header("Content-Type: application/json");

// Show what route is being processed
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

echo json_encode([
    "raw_uri" => $_SERVER['REQUEST_URI'],
    "parsed_uri" => $uri,
    "document_root" => $_SERVER['DOCUMENT_ROOT'],
    "script_filename" => $_SERVER['SCRIPT_FILENAME'],
    "php_self" => $_SERVER['PHP_SELF'],
    "request_method" => $_SERVER['REQUEST_METHOD'],
    "test" => "This file is in /public/check-routes.php"
], JSON_PRETTY_PRINT);
