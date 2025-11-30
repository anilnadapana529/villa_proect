<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = preg_replace('#^/public/#', '/', $uri);

if (strpos($uri, '/api/') === 0) {
    require_once __DIR__ . '/api.php';
    exit;
}

$uri = trim($uri, '/');

if (empty($uri)) {
    $uri = 'home';
}

$phpFile = __DIR__ . '/' . $uri . '.php';

if (strpos($uri, '.php') !== false) {
    $phpFile = __DIR__ . '/' . $uri;
}

if (file_exists($phpFile)) {
    require_once $phpFile;
} else {
    http_response_code(404);
    echo "Page not found";
}
