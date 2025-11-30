<?php
include __DIR__ . "/helpers/api.php";

if (!API::isLoggedIn() || API::getUserRole() !== 'user') {
    header("Location: login.php");
    exit;
}

$token = API::getToken();
$user = API::getUser();

$profile = API::get("user-profile", [], $token);
$bookings = API::get("user-bookings", [], $token);

include __DIR__ . "/includes/header.php";
?>

<style>
body {
    background: #f8f9fa;
}

.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.profile-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.profile-card h2 {
    color: #1e3a8a;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
}

.info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-label {
    font-weight: 600;
    color: #1e3a8a;
    width: 150px;
}

.info-value {
    color: #4a5568;
}

.bookings-section {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.bookings-section h2 {
    color: #1e3a8a;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
}

.booking-card {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.booking-card:hover {
    border-color: #1e3a8a;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.1);
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.villa-name {
    font-size: 18px;
    font-weight: 600;
    color: #1e3a8a;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-confirmed {
    background: #d1fae5;
    color: #065f46;
}

.status-completed {
    background: #dbeafe;
    color: #1e40af;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.booking-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    color: #4a5568;
    font-size: 14px;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-weight: 600;
    color: #1e3a8a;
    margin-bottom: 4px;
}

.btn-cancel {
    background: #dc2626;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    margin-top: 10px;
}

.btn-cancel:hover {
    background: #b91c1c;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.btn-browse {
    background: #1e3a8a;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    margin-top: 20px;
}

.btn-browse:hover {
    background: #1e40af;
    color: white;
}
</style>

<div class="dashboard-container">
    <div class="profile-card">
        <h2>My Profile</h2>
        <?php if ($profile && $profile['status']): ?>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?= htmlspecialchars($profile['user']['name'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?= htmlspecialchars($profile['user']['email'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value"><?= htmlspecialchars($profile['user']['phone'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Member Since:</div>
                <div class="info-value"><?= date('F j, Y', strtotime($profile['user']['created_at'] ?? 'now')) ?></div>
            </div>
        <?php else: ?>
            <p style="color: #718096;">Unable to load profile information.</p>
        <?php endif; ?>
    </div>

    <div class="bookings-section">
        <h2>My Bookings</h2>

        <?php if ($bookings && $bookings['status'] && !empty($bookings['bookings'])): ?>
            <?php foreach ($bookings['bookings'] as $booking): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="villa-name"><?= htmlspecialchars($booking['villa_name'] ?? 'Villa') ?></div>
                        <span class="status-badge status-<?= strtolower($booking['status'] ?? 'pending') ?>">
                            <?= ucfirst($booking['status'] ?? 'Pending') ?>
                        </span>
                    </div>

                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Booking ID</span>
                            <span>#<?= $booking['id'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-in</span>
                            <span><?= date('M j, Y', strtotime($booking['check_in'] ?? 'now')) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-out</span>
                            <span><?= date('M j, Y', strtotime($booking['check_out'] ?? 'now')) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Guests</span>
                            <span><?= $booking['guests'] ?? 0 ?> guests</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Amount</span>
                            <span>₹<?= number_format($booking['total_amount'] ?? 0, 2) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Payment Status</span>
                            <span><?= ucfirst($booking['payment_status'] ?? 'pending') ?></span>
                        </div>
                    </div>

                    <?php if (strtolower($booking['status']) === 'pending' || strtolower($booking['status']) === 'confirmed'): ?>
                        <button class="btn-cancel" onclick="cancelBooking(<?= $booking['id'] ?>)">Cancel Booking</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📅</div>
                <h3 style="color: #1e3a8a; margin-bottom: 10px;">No Bookings Yet</h3>
                <p>Start planning your next vacation by browsing our luxury villas.</p>
                <a href="villas.php" class="btn-browse">Browse Villas</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    fetch('/api/cancel-booking', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer <?= $token ?>'
        },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            alert('Booking cancelled successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Could not cancel booking'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
