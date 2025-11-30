<?php
header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/public/index.php', '', $uri);
$uri = str_replace('/index.php', '', $uri);
$uri = str_replace('/api', '', $uri);
$uri = trim($uri, '/');

$parts = explode('/', $uri);
$endpoint = end($parts);

echo json_encode([
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'parsed_uri' => $uri,
    'parts' => $parts,
    'endpoint' => $endpoint,
    'is_user_login' => $endpoint === 'user-login',
    'is_user_register' => $endpoint === 'user-register'
]);
?>
