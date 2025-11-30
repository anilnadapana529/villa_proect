<?php
echo "<h1>System Diagnostic</h1>";
echo "<h2>PHP Version: " . phpversion() . "</h2>";
echo "<h2>Files Check:</h2>";
echo "<ul>";

$files = [
    'header' => __DIR__ . '/includes/header.php',
    'footer' => __DIR__ . '/includes/footer.php',
    'api' => __DIR__ . '/helpers/api.php',
    'home' => __DIR__ . '/home.php',
    'login' => __DIR__ . '/login.php',
    'register' => __DIR__ . '/register.php',
    'user-dashboard' => __DIR__ . '/user-dashboard.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $status = $exists ? '✅ EXISTS' : '❌ MISSING';
    echo "<li><strong>$name:</strong> $status - $path</li>";
}

echo "</ul>";

echo "<h2>Session Info:</h2>";
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Server Info:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "</pre>";
?>
