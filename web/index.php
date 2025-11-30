<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/web', '', $uri);
$uri = trim($uri, '/');

if (empty($uri) || $uri === 'index.php') {
    $uri = 'home';
}

$uri = str_replace('.php', '', $uri);

$pagePath = __DIR__ . '/pages/' . $uri . '.php';

if (file_exists($pagePath)) {
    require_once $pagePath;
} else {
    http_response_code(404);
    echo "Page not found: " . htmlspecialchars($uri);
}
