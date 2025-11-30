<?php
header("Content-Type: application/json");

// Simulate different request URIs
$testURIs = [
    '/api/owner-login',
    '/api/admin-login',
    '/api/owner-stats',
    '/api/home-data',
    '/owner-login',
    '/public/api/owner-login',
];

$results = [];

foreach ($testURIs as $testURI) {
    // Simulate the routing logic from routes.php
    $uri = $testURI;
    $uri = str_replace('/public/index.php', '', $uri);
    $uri = str_replace('/index.php', '', $uri);
    $uri = str_replace('/api', '', $uri);
    $uri = trim($uri, '/');
    
    $parts = explode('/', $uri);
    $endpoint = end($parts) ?: 'home-data';
    
    $results[] = [
        "input" => $testURI,
        "cleaned_uri" => $uri,
        "endpoint" => $endpoint
    ];
}

echo json_encode([
    "test" => "Route Parsing Test",
    "routes_fix_applied" => true,
    "results" => $results
], JSON_PRETTY_PRINT);
