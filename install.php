<?php
/**
 * Database Installer Script
 * Run this file once to set up all database tables
 * Access: https://topmost.in/install.php
 */

// Database credentials
$host = "localhost";
$db   = "u200283558_villa";
$user = "u200283558_villa";
$pass = "Ansi@2023";

// Security: Delete this file after installation
$installerFile = __FILE__;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Booking System - Database Installer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .info-box ul {
            margin-left: 20px;
            color: #555;
        }
        .info-box li {
            margin-bottom: 8px;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        .result.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            display: block;
        }
        .result.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            display: block;
        }
        .result h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }
        .result ul {
            margin-left: 20px;
        }
        .result li {
            margin-bottom: 5px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #856404;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏡 Villa Booking System</h1>
        <p class="subtitle">Database Installation Wizard</p>

        <?php if (!isset($_POST['install'])): ?>

            <div class="warning">
                <strong>⚠️ Important:</strong> This will create all necessary database tables. Make sure you have a backup of your database before proceeding.
            </div>

            <div class="info-box">
                <h3>📦 What will be installed:</h3>
                <ul>
                    <li><strong>32 Tables</strong> covering complete villa booking system</li>
                    <li><strong>Indexes</strong> for optimal performance</li>
                    <li><strong>Default Settings</strong> (currency, tax rate, commission)</li>
                    <li><strong>Email Templates</strong> (booking, payment, notifications)</li>
                    <li><strong>SMS Templates</strong> (reminders, alerts)</li>
                </ul>
            </div>

            <div class="info-box">
                <h3>✨ Features Enabled:</h3>
                <ul>
                    <li>Admin Dashboard with Analytics</li>
                    <li>Villa Management (Images, Amenities, Pricing)</li>
                    <li>Owner Management & Verification</li>
                    <li>Booking System with Approval Workflow</li>
                    <li>Payment & Commission Tracking</li>
                    <li>Reviews & Ratings</li>
                    <li>Chat & Support System</li>
                    <li>Notification System</li>
                    <li>CMS (Banners, Blogs, Testimonials)</li>
                </ul>
            </div>

            <form method="POST" id="installForm">
                <button type="submit" name="install" value="1" class="btn" id="installBtn">
                    🚀 Install Database
                </button>
            </form>

            <div class="loader" id="loader"></div>

        <?php else:
            // Installation process
            $results = [];
            $errors = [];

            try {
                // Connect to database
                $pdo = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

                // Read SQL file
                $sqlFile = __DIR__ . '/complete_system_schema.sql';
                if (!file_exists($sqlFile)) {
                    throw new Exception("SQL file not found: complete_system_schema.sql");
                }

                $sql = file_get_contents($sqlFile);

                // Split into individual statements
                $statements = array_filter(
                    array_map('trim', preg_split('/;[\r\n]+/', $sql)),
                    function($stmt) {
                        return !empty($stmt) &&
                               !preg_match('/^--/', $stmt) &&
                               !preg_match('/^\/\*/', $stmt);
                    }
                );

                // Execute each statement
                $successCount = 0;
                foreach ($statements as $statement) {
                    try {
                        $pdo->exec($statement);
                        $successCount++;
                    } catch (PDOException $e) {
                        // Skip if table already exists
                        if (strpos($e->getMessage(), 'already exists') === false) {
                            $errors[] = "Error: " . $e->getMessage();
                        }
                    }
                }

                $results[] = "✅ Successfully executed $successCount SQL statements";

                // Verify table creation
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $results[] = "✅ Total tables in database: " . count($tables);

                // Check for key tables
                $keyTables = ['users', 'owners', 'admins', 'villas', 'bookings', 'payments'];
                $missingTables = array_diff($keyTables, $tables);

                if (empty($missingTables)) {
                    $results[] = "✅ All core tables created successfully";
                } else {
                    $errors[] = "❌ Missing tables: " . implode(', ', $missingTables);
                }

            } catch (Exception $e) {
                $errors[] = "❌ Installation failed: " . $e->getMessage();
            }
        ?>

            <?php if (empty($errors)): ?>
                <div class="result success">
                    <h3>🎉 Installation Completed Successfully!</h3>
                    <ul>
                        <?php foreach ($results as $result): ?>
                            <li><?php echo htmlspecialchars($result); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <br>
                    <p><strong>Next Steps:</strong></p>
                    <ol>
                        <li>Delete this install.php file for security</li>
                        <li>Create your admin account</li>
                        <li>Start using the system!</li>
                    </ol>
                    <br>
                    <a href="/" class="btn">Go to Homepage</a>
                    <a href="/admin" class="btn" style="background: #28a745; margin-left: 10px;">Admin Login</a>
                </div>
            <?php else: ?>
                <div class="result error">
                    <h3>❌ Installation Errors</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (!empty($results)): ?>
                        <br>
                        <p><strong>Partial Success:</strong></p>
                        <ul>
                            <?php foreach ($results as $result): ?>
                                <li><?php echo htmlspecialchars($result); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <br>
                    <a href="install.php" class="btn">Try Again</a>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <script>
        document.getElementById('installForm')?.addEventListener('submit', function() {
            document.getElementById('installBtn').disabled = true;
            document.getElementById('installBtn').textContent = 'Installing...';
            document.getElementById('loader').style.display = 'block';
        });
    </script>
</body>
</html>
