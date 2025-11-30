<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = preg_replace('#^/public/#', '/', $uri);
$uri = trim($uri, '/');

if (strpos($uri, 'api/') === 0) {
    require_once __DIR__ . '/api.php';
    exit;
}

$routes = [
    '' => 'home.php',
    'home' => 'home.php',
    'login' => 'login.php',
    'register' => 'register.php',
    'logout' => 'logout.php',
    'villas' => 'villas.php',
    'villa-detail' => 'villa-detail.php',
    'booking' => 'booking.php',
    'user' => 'user-dashboard.php',
    'user-dashboard' => 'user-dashboard.php',
    'owner' => 'owner-dashboard.php',
    'owner-dashboard' => 'owner-dashboard.php',
    'add-villa' => 'add-villa.php',
    'edit-villa' => 'edit-villa.php',
    'admin' => 'admin-dashboard.php',
    'admin-dashboard' => 'admin-dashboard.php',
];

if (isset($routes[$uri])) {
    $file = __DIR__ . '/' . $routes[$uri];
    if (file_exists($file)) {
        require_once $file;
    } else {
        http_response_code(404);
        echo "Page not found: File does not exist";
    }
} else {
    $file = __DIR__ . '/' . $uri . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        http_response_code(404);
        echo "Page not found: " . htmlspecialchars($uri);
    }
}
