<?php
include __DIR__ . "/../helpers/api.php";

if (!API::isLoggedIn() || API::getUserRole() !== 'admin') {
    header("Location: login.php");
    exit;
}

$token = API::getToken();
$user = API::getUser();

$stats = API::get("admin-stats", [], $token);
$owners = API::get("admin-owners", [], $token);
$villas = API::get("admin-villas", [], $token);
$users = API::get("admin-users", [], $token);
$bookings = API::get("admin-bookings", [], $token);
$payments = API::get("admin-payments", [], $token);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TopMost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif; font-size: 14px; margin: 0; padding: 0;">

<style>
.dashboard { min-height: 100vh; background: #f7fafc; }
.sidebar { background: linear-gradient(180deg, #1e40af 0%, #7c3aed 100%); min-height: 100vh; color: white; position: fixed; width: 250px; padding: 0; }
.sidebar-header { padding: 30px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.sidebar-header h4 { margin: 0; font-size: 1.5rem; font-weight: 700; }
.sidebar-header p { margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem; }
.sidebar-menu { padding: 20px 0; }
.sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 15px 20px; color: white; text-decoration: none; transition: background 0.3s; font-weight: 500; }
.sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,0.1); }
.main-content { margin-left: 250px; padding: 30px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.page-header h2 { font-size: 2rem; font-weight: 700; color: #2d3748; margin: 0; }
.btn-logout { background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.stat-card h6 { color: #718096; font-size: 0.9rem; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; }
.stat-card h3 { font-size: 2.5rem; font-weight: 700; color: #2d3748; margin: 0; }
.stat-card .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
.stat-icon.purple { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); color: white; }
.stat-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.stat-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; }
.content-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
.content-card h5 { font-size: 1.3rem; font-weight: 700; color: #2d3748; margin-bottom: 20px; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { text-align: left; padding: 12px; background: #f7fafc; color: #4a5568; font-weight: 600; font-size: 0.9rem; }
.data-table td { padding: 15px 12px; border-bottom: 1px solid #e2e8f0; }
.data-table tr:hover { background: #f7fafc; }
.villa-img { width: 80px; height: 60px; object-fit: cover; border-radius: 8px; }
.badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.badge-approved { background: #d1fae5; color: #065f46; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-rejected { background: #fee2e2; color: #991b1b; }
.badge-active { background: #dbeafe; color: #1e40af; }
.btn-action { padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; margin-right: 5px; }
.btn-approve { background: #10b981; color: white; }
.btn-reject { background: #dc3545; color: white; }
.btn-view { background: #3b82f6; color: white; }
</style>

<div class="dashboard d-flex">
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Admin Panel</h4>
            <p><?= htmlspecialchars($user['name'] ?? $user['email']) ?></p>
        </div>
        <div class="sidebar-menu">
            <a href="#dashboard" class="active" onclick="showSection('dashboard')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/><path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/></svg>
                Dashboard
            </a>
            <a href="#villas" onclick="showSection('villas')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/></svg>
                Villa Management
            </a>
            <a href="#owners" onclick="showSection('owners')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
                Owner Management
            </a>
            <a href="#bookings" onclick="showSection('bookings')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                Bookings
            </a>
            <a href="#users" onclick="showSection('users')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>
                Users
            </a>
            <a href="#payments" onclick="showSection('payments')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z"/></svg>
                Payments
            </a>
            <a href="#settings" onclick="showSection('settings')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/></svg>
                Settings
            </a>
        </div>
        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
            <button class="btn-logout w-100" onclick="logout()">Logout</button>
        </div>
    </div>

    <div class="main-content flex-grow-1">
        <!-- Dashboard Section -->
        <div id="dashboard-section">
            <div class="page-header"><h2>Admin Dashboard</h2></div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg></div>
                    <h6>Total Owners</h6>
                    <h3><?= $stats['stats']['total_owners'] ?? 0 ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/></svg></div>
                    <h6>Total Villas</h6>
                    <h3><?= $stats['stats']['total_villas'] ?? 0 ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg></div>
                    <h6>Pending Approvals</h6>
                    <h3><?= $stats['stats']['pending_villas'] ?? 0 ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z"/></svg></div>
                    <h6>Total Bookings</h6>
                    <h3><?= $stats['stats']['total_bookings'] ?? 0 ?></h3>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg></div>
                    <h6>Total Users</h6>
                    <h3><?= $stats['stats']['total_users'] ?? 0 ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/><path d="M8 8.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0V9a.5.5 0 0 1 .5-.5zm0-1a.5.5 0 0 0 .5-.5V5a.5.5 0 0 0-1 0v2a.5.5 0 0 0 .5.5z"/></svg></div>
                    <h6>Total Revenue</h6>
                    <h3>₹<?= number_format($stats['stats']['total_revenue'] ?? 0) ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/></svg></div>
                    <h6>Active Listings</h6>
                    <h3><?= $stats['stats']['active_villas'] ?? 0 ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/></svg></div>
                    <h6>Pending Reviews</h6>
                    <h3><?= $stats['stats']['pending_reviews'] ?? 0 ?></h3>
                </div>
            </div>
        </div>

        <!-- Owners Section -->
        <div id="owners-section" style="display:none;">
            <div class="page-header"><h2>All Owners</h2></div>
            <div class="content-card">
                <?php if (empty($owners['owners'])): ?>
                    <p class="text-muted">No owners yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Registered</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach($owners['owners'] as $owner): ?>
                                <tr>
                                    <td>#<?= $owner['id'] ?></td>
                                    <td><?= htmlspecialchars($owner['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($owner['email']) ?></td>
                                    <td><?= htmlspecialchars($owner['phone'] ?? 'N/A') ?></td>
                                    <td><span class="badge badge-<?= $owner['status'] ?>"><?= ucfirst($owner['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($owner['created_at'])) ?></td>
                                    <td><button class="btn-action btn-view" onclick="viewOwner(<?= $owner['id'] ?>)">View</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Villas Section -->
        <div id="villas-section" style="display:none;">
            <div class="page-header"><h2>All Villas</h2></div>
            <div class="content-card">
                <?php if (empty($villas['villas'])): ?>
                    <p class="text-muted">No villas yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead><tr><th>Image</th><th>Name</th><th>Location</th><th>Owner</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach($villas['villas'] as $villa): ?>
                                <tr>
                                    <td><img src="<?= htmlspecialchars($villa['image'] ?? '/uploads/placeholder.jpg') ?>" class="villa-img"></td>
                                    <td><?= htmlspecialchars($villa['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($villa['location'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($villa['owner_name'] ?? 'N/A') ?></td>
                                    <td>₹<?= number_format($villa['weekday_price'] ?? 0) ?></td>
                                    <td><span class="badge badge-<?= $villa['status'] ?>"><?= ucfirst($villa['status']) ?></span></td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="viewVilla(<?= $villa['id'] ?>)">View</button>
                                        <?php if ($villa['status'] == 'pending'): ?>
                                            <button class="btn-action btn-approve" onclick="approveVilla(<?= $villa['id'] ?>)">Approve</button>
                                            <button class="btn-action btn-reject" onclick="rejectVilla(<?= $villa['id'] ?>)">Reject</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bookings Section -->
        <div id="bookings-section" style="display:none;">
            <div class="page-header"><h2>All Bookings</h2></div>
            <div class="content-card">
                <?php if (empty($bookings['bookings'])): ?>
                    <p class="text-muted">No bookings yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Villa</th>
                                <th>User</th>
                                <th>Owner</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Booked On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings['bookings'] as $booking): ?>
                                <tr>
                                    <td>#<?= $booking['id'] ?></td>
                                    <td><?= htmlspecialchars($booking['villa_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['owner_name'] ?? 'N/A') ?></td>
                                    <td><?= $booking['check_in'] ? date('M d, Y', strtotime($booking['check_in'])) : 'N/A' ?></td>
                                    <td><?= $booking['check_out'] ? date('M d, Y', strtotime($booking['check_out'])) : 'N/A' ?></td>
                                    <td>₹<?= number_format($booking['total_amount'] ?? 0) ?></td>
                                    <td><span class="badge badge-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($booking['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users-section" style="display:none;">
            <div class="page-header"><h2>All Users</h2></div>
            <div class="content-card">
                <?php if (empty($users['users'])): ?>
                    <p class="text-muted">No users yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users['users'] as $usr): ?>
                                <tr>
                                    <td>#<?= $usr['id'] ?></td>
                                    <td><?= htmlspecialchars($usr['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($usr['email']) ?></td>
                                    <td><?= htmlspecialchars($usr['phone'] ?? 'N/A') ?></td>
                                    <td><span class="badge badge-<?= $usr['status'] ?>"><?= ucfirst($usr['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($usr['created_at'])) ?></td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="viewUser(<?= $usr['id'] ?>)">View</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payments Section -->
        <div id="payments-section" style="display:none;">
            <div class="page-header"><h2>Payments & Finance</h2></div>
            <div class="content-card">
                <?php if (empty($payments['payments'])): ?>
                    <p class="text-muted">No payments yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($payments['payments'] as $payment): ?>
                                <tr>
                                    <td>#<?= $payment['id'] ?></td>
                                    <td><?= htmlspecialchars($payment['order_id'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($payment['user_name'] ?? 'N/A') ?><br><small><?= htmlspecialchars($payment['user_email'] ?? '') ?></small></td>
                                    <td>₹<?= number_format($payment['amount'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($payment['payment_method'] ?? 'N/A') ?></td>
                                    <td><span class="badge badge-<?= strtolower($payment['status']) == 'success' ? 'approved' : 'pending' ?>"><?= ucfirst($payment['status']) ?></span></td>
                                    <td><?= date('M d, Y H:i', strtotime($payment['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Settings Section -->
        <div id="settings-section" style="display:none;">
            <div class="page-header"><h2>System Settings</h2></div>
            <div class="content-card">
                <h5 style="margin-bottom: 20px;">Commission & Tax</h5>
                <div style="max-width: 600px;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Admin Commission (%)</label>
                        <input type="number" value="15" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="15">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Tax Rate (%)</label>
                        <input type="number" value="18" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="18">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Currency</label>
                        <select style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                            <option value="INR" selected>Indian Rupee (₹)</option>
                            <option value="USD">US Dollar ($)</option>
                            <option value="EUR">Euro (€)</option>
                        </select>
                    </div>
                    <button style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(s) {
    document.querySelectorAll('[id$="-section"]').forEach(el => el.style.display = 'none');
    document.getElementById(s + '-section').style.display = 'block';
    document.querySelectorAll('.sidebar-menu a').forEach(el => el.classList.remove('active'));
    event.target.closest('a').classList.add('active');
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
}

function viewOwner(id) {
    alert('Owner details coming soon! ID: ' + id);
}

function viewUser(id) {
    alert('User details coming soon! ID: ' + id);
}

function viewVilla(id) {
    window.location.href = 'villa-detail.php?id=' + id;
}

function approveVilla(id) {
    if (confirm('Approve this villa?')) {
        fetch(`/api/admin-approve-villa?id=${id}`, {
            headers: { 'Authorization': 'Bearer <?= $token ?>' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status) {
                alert('Villa approved successfully!');
                location.reload();
            } else {
                alert('Error: ' + d.message);
            }
        });
    }
}

function rejectVilla(id) {
    if (confirm('Reject this villa?')) {
        fetch(`/api/admin-reject-villa?id=${id}`, {
            headers: { 'Authorization': 'Bearer <?= $token ?>' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status) {
                alert('Villa rejected successfully!');
                location.reload();
            } else {
                alert('Error: ' + d.message);
            }
        });
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
