<?php

$files = [
    'add-villa.php',
    'admin-dashboard.php',
    'edit-villa.php',
    'home.php',
    'logout.php',
    'owner-dashboard.php',
    'test-system.php',
    'user-dashboard.php',
    'villa-detail.php',
    'villas.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);

        $content = str_replace('__DIR__ . "/../helpers/', '__DIR__ . "/helpers/', $content);
        $content = str_replace('__DIR__ . "/../includes/', '__DIR__ . "/includes/', $content);
        $content = str_replace("__DIR__ . '/../helpers/", "__DIR__ . '/helpers/", $content);
        $content = str_replace("__DIR__ . '/../includes/", "__DIR__ . '/includes/", $content);

        file_put_contents($file, $content);
        echo "Fixed: $file\n";
    }
}

echo "All paths fixed!\n";
