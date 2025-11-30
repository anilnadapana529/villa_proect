<?php
// Setup admin table and user
require_once 'App/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::instance();

    // Create admins table
    $createTable = "
    CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $db->exec($createTable);
    echo "✓ Admins table created successfully\n";

    // Check if admin exists
    $stmt = $db->prepare("SELECT COUNT(*) FROM admins WHERE email = ?");
    $stmt->execute(['admin@villa.com']);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insert default admin (password: admin123)
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("
            INSERT INTO admins (name, email, password, phone)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute(['Admin User', 'admin@villa.com', $hashedPassword, '1234567890']);
        echo "✓ Default admin created: admin@villa.com / admin123\n";
    } else {
        echo "✓ Admin already exists\n";
    }

    echo "\n=== Setup Complete ===\n";
    echo "You can now login with:\n";
    echo "Email: admin@villa.com\n";
    echo "Password: admin123\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
