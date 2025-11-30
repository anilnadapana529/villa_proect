<?php
include __DIR__ . "/helpers/api.php";

if (!API::isLoggedIn() || API::getUserRole() !== 'owner') {
    header("Location: login.php");
    exit;
}

$villaId = $_GET['id'] ?? 0;
if (!$villaId) {
    header("Location: owner-dashboard.php");
    exit;
}

$token = API::getToken();
$user = API::getUser();

include __DIR__ . "/includes/header.php";
?>

<div class="add-villa-container">
    <div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <div style="margin-bottom: 20px;">
            <a href="owner-dashboard.php" style="color: #667eea; text-decoration: none; font-weight: 600;">
                ← Back to Dashboard
            </a>
        </div>

        <h1 style="font-size: 2.5rem; font-weight: 700; color: #2d3748; margin-bottom: 10px;">Edit Villa</h1>
        <p style="color: #718096; margin-bottom: 40px;">Update your villa details</p>

        <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <p style="text-align: center; color: #718096; font-size: 1.2rem;">
                Edit functionality coming soon!<br>
                Villa ID: <?= htmlspecialchars($villaId) ?>
            </p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="owner-dashboard.php" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
