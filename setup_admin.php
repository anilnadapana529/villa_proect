<?php
/**
 * Admin Account Setup Script
 * Run this after database installation to create your first admin account
 * Access: https://topmost.in/setup_admin.php
 */

$host = "localhost";
$db   = "u200283558_villa";
$user = "u200283558_villa";
$pass = "Ansi@2023";

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Check if admin already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'An admin with this email already exists';
            } else {
                // Create admin account
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("
                    INSERT INTO admins (name, email, password, role, is_active, created_at)
                    VALUES (?, ?, ?, 'super_admin', 1, NOW())
                ");
                $stmt->execute([$name, $email, $hashedPassword]);
                $success = true;
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .success-box {
            text-align: center;
        }
        .success-box h2 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .success-box p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .btn-secondary {
            background: #28a745;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <div class="success-box">
                <h2>🎉 Admin Account Created!</h2>
                <p>Your admin account has been created successfully.</p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p>You can now login to the admin dashboard.</p>
                <a href="/admin" class="btn">Go to Admin Login</a>
                <p style="margin-top: 20px; font-size: 13px; color: #999;">
                    Don't forget to delete setup_admin.php for security!
                </p>
            </div>
        <?php else: ?>
            <h1>🔐 Create Admin Account</h1>
            <p class="subtitle">Set up your first admin account</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Password (min 6 characters)</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn">Create Admin Account</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
