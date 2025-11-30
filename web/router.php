<?php
/**
 * Web Router - Clean URL Routing
 * Handles all web page requests with clean URLs
 */

// Get the requested path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Route mapping
$routes = [
    '' => 'pages/home.php',
    'admin' => 'pages/login.php?type=admin',
    'owner' => 'pages/login.php?type=owner',
    'user' => 'pages/login.php?type=user',
    'login' => 'pages/login.php',
    'register' => 'pages/register.php',
    'logout' => 'pages/logout.php',
    'villas' => 'pages/villas.php',
    'villa' => 'pages/villa-detail.php',
    'booking' => 'pages/booking.php',
    'dashboard' => 'pages/user-dashboard.php',
    'admin-dashboard' => 'pages/admin-dashboard.php',
    'owner-dashboard' => 'pages/owner-dashboard.php',
];

// Set type in $_GET if it's in the route
if ($uri === 'admin' || $uri === 'owner' || $uri === 'user') {
    $_GET['type'] = $uri;
}

// Check if route exists
if (array_key_exists($uri, $routes)) {
    $file = __DIR__ . '/' . $routes[$uri];

    // Remove query string from file path
    $file = explode('?', $file)[0];

    if (file_exists($file)) {
        include $file;
        exit;
    }
}

// 404 - Page Not Found
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .error-container {
            text-align: center;
            color: white;
        }
        .error-container h1 {
            font-size: 8rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .error-container h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .btn-home {
            background: white;
            color: #667eea;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist.</p>
        <a href="/" class="btn-home mt-4">Go Home</a>
    </div>
</body>
</html>
