<?php
include __DIR__ . "/../helpers/api.php";

if (!API::isLoggedIn() || API::getUserRole() !== 'owner') {
    header("Location: login.php");
    exit;
}

$token = API::getToken();
$user = API::getUser();

$stats = API::get("owner-stats", [], $token);
$villas = API::get("owner-villas", [], $token);
$bookings = API::get("owner-bookings", [], $token);

include __DIR__ . "/../includes/header.php";
?>

<style>
.dashboard {
    min-height: 100vh;
    background: #f7fafc;
}

.sidebar {
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: white;
    position: fixed;
    width: 250px;
    padding: 0;
}

.sidebar-header {
    padding: 30px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.sidebar-header p {
    margin: 5px 0 0 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    color: white;
    text-decoration: none;
    transition: background 0.3s;
    font-weight: 500;
}

.sidebar-menu a:hover, .sidebar-menu a.active {
    background: rgba(255,255,255,0.1);
}

.main-content {
    margin-left: 250px;
    padding: 30px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.btn-logout {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-card h6 {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 10px;
    text-transform: uppercase;
}

.stat-card h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.stat-icon.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.stat-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.stat-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
.stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }

.content-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.content-card h5 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
}

.villa-table, .booking-table {
    width: 100%;
    border-collapse: collapse;
}

.villa-table th, .booking-table th {
    text-align: left;
    padding: 12px;
    background: #f7fafc;
    color: #4a5568;
    font-weight: 600;
    font-size: 0.9rem;
}

.villa-table td, .booking-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #e2e8f0;
}

.villa-table tr:hover, .booking-table tr:hover {
    background: #f7fafc;
}

.villa-img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-approved { background: #d1fae5; color: #065f46; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-rejected { background: #fee2e2; color: #991b1b; }
.badge-confirmed { background: #dbeafe; color: #1e40af; }
.badge-completed { background: #d1fae5; color: #065f46; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }

.btn-add-villa {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-add-villa:hover {
    color: white;
    transform: translateY(-2px);
}

.btn-action {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    margin-right: 5px;
}

.btn-edit { background: #3b82f6; color: white; }
.btn-delete { background: #dc3545; color: white; }
.btn-view { background: #10b981; color: white; }

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        min-height: auto;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="dashboard d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Owner Panel</h4>
            <p><?= htmlspecialchars($user['name'] ?? $user['email']) ?></p>
        </div>
        <div class="sidebar-menu">
            <a href="#dashboard" class="active" onclick="showSection('dashboard')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                    <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
                </svg>
                Dashboard
            </a>
            <a href="#villas" onclick="showSection('villas')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                </svg>
                My Villas
            </a>
            <a href="#bookings" onclick="showSection('bookings')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                Bookings
            </a>
        </div>
        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
            <button class="btn-logout w-100" onclick="logout()">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <!-- Dashboard Section -->
        <div id="dashboard-section">
            <div class="page-header">
                <h2>Dashboard Overview</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                        </svg>
                    </div>
                    <h6>Total Villas</h6>
                    <h3><?= $stats['stats']['total_villas'] ?? 0 ?></h3>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </div>
                    <h6>Approved Villas</h6>
                    <h3><?= $stats['stats']['approved_villas'] ?? 0 ?></h3>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                    </div>
                    <h6>Pending Bookings</h6>
                    <h3><?= $stats['stats']['pending_bookings'] ?? 0 ?></h3>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z"/>
                        </svg>
                    </div>
                    <h6>Total Bookings</h6>
                    <h3><?= count($bookings['bookings'] ?? []) ?></h3>
                </div>
            </div>

            <div class="content-card">
                <h5>Recent Bookings</h5>
                <?php if (empty($bookings['bookings'])): ?>
                    <p class="text-muted">No bookings yet.</p>
                <?php else: ?>
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>Villa</th>
                                <th>Guest</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($bookings['bookings'], 0, 5) as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['villa_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['check_in'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['check_out'])) ?></td>
                                    <td>₹<?= number_format($booking['total_amount']) ?></td>
                                    <td><span class="badge badge-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Villas Section -->
        <div id="villas-section" style="display:none;">
            <div class="page-header">
                <h2>My Villas</h2>
                <a href="add-villa.php" class="btn-add-villa">+ Add New Villa</a>
            </div>

            <div class="content-card">
                <?php if (empty($villas['villas'])): ?>
                    <p class="text-muted">No villas yet. Add your first villa!</p>
                <?php else: ?>
                    <table class="villa-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($villas['villas'] as $villa): ?>
                                <tr>
                                    <td><img src="<?= htmlspecialchars($villa['image'] ?? '/uploads/placeholder.jpg') ?>" class="villa-img"></td>
                                    <td><?= htmlspecialchars($villa['name'] ?? $villa['title'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($villa['location'] ?? 'N/A') ?></td>
                                    <td>₹<?= number_format($villa['weekday_price'] ?? 0) ?></td>
                                    <td><span class="badge badge-<?= $villa['status'] ?>"><?= ucfirst($villa['status']) ?></span></td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="viewVilla(<?= $villa['id'] ?>)">View</button>
                                        <button class="btn-action btn-edit" onclick="editVilla(<?= $villa['id'] ?>)">Edit</button>
                                        <button class="btn-action btn-delete" onclick="deleteVilla(<?= $villa['id'] ?>)">Delete</button>
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
            <div class="page-header">
                <h2>All Bookings</h2>
            </div>

            <div class="content-card">
                <?php if (empty($bookings['bookings'])): ?>
                    <p class="text-muted">No bookings yet.</p>
                <?php else: ?>
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Villa</th>
                                <th>Guest Name</th>
                                <th>Phone</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings['bookings'] as $booking): ?>
                                <tr>
                                    <td>#<?= $booking['id'] ?></td>
                                    <td><?= htmlspecialchars($booking['villa_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($booking['user_phone'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['check_in'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['check_out'])) ?></td>
                                    <td>₹<?= number_format($booking['total_amount']) ?></td>
                                    <td><span class="badge badge-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($booking['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(section) {
    document.querySelectorAll('[id$="-section"]').forEach(el => el.style.display = 'none');
    document.getElementById(section + '-section').style.display = 'block';
    
    document.querySelectorAll('.sidebar-menu a').forEach(el => el.classList.remove('active'));
    event.target.classList.add('active');
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
}

function viewVilla(id) {
    window.location.href = 'villa-detail.php?id=' + id;
}

function editVilla(id) {
    window.location.href = 'edit-villa.php?id=' + id;
}

function deleteVilla(id) {
    if (confirm('Are you sure you want to delete this villa?')) {
        alert('Delete functionality to be implemented');
    }
}
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
