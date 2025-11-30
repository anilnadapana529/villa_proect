<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopMost - Luxury Villa Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #ffffff;
            color: #1e3a8a;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .main-header {
            background: #1e3a8a;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-menu a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: opacity 0.3s;
        }

        .nav-menu a:hover {
            opacity: 0.8;
        }

        .btn-primary-custom {
            background: #ffffff;
            color: #1e3a8a;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            color: #1e3a8a;
        }

        .btn-secondary-custom {
            background: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-secondary-custom:hover {
            background: #ffffff;
            color: #1e3a8a;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #1e3a8a;
        }

        .container {
            max-width: 1400px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="home.php" class="logo">TopMost</a>

            <nav>
                <ul class="nav-menu">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="villas.php">Villas</a></li>
                    <?php
                    require_once __DIR__ . '/../helpers/api.php';
                    if (API::isLoggedIn()):
                        $role = API::getUserRole();
                        if ($role === 'user'):
                    ?>
                        <li><a href="user-dashboard.php">My Dashboard</a></li>
                        <li><a href="logout.php" class="btn-secondary-custom">Logout</a></li>
                    <?php
                        elseif ($role === 'owner'):
                    ?>
                        <li><a href="owner-dashboard.php">Owner Dashboard</a></li>
                        <li><a href="logout.php" class="btn-secondary-custom">Logout</a></li>
                    <?php
                        elseif ($role === 'admin'):
                    ?>
                        <li><a href="admin-dashboard.php">Admin Dashboard</a></li>
                        <li><a href="logout.php" class="btn-secondary-custom">Logout</a></li>
                    <?php
                        endif;
                    else:
                    ?>
                        <li><a href="login.php" class="btn-secondary-custom">Login</a></li>
                        <li><a href="register.php" class="btn-primary-custom">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
