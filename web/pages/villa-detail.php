<?php
require_once __DIR__ . "/../helpers/api.php";

$villaId = $_GET['id'] ?? 0;
if (!$villaId) {
    header("Location: villas.php");
    exit;
}

$villa = API::get("villa-detail", ["id" => $villaId]);

if (!isset($villa['villa'])) {
    header("Location: villas.php");
    exit;
}

$v = $villa['villa'];
$images = $villa['images'] ?? [];

$isLoggedIn = API::isLoggedIn();
$user = $isLoggedIn ? API::getUser() : null;

include __DIR__ . "/../includes/header.php";
?>

<style>
.villa-detail-container {
    max-width: 1400px;
    margin: 30px auto;
    padding: 0 20px;
}

.villa-gallery {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    grid-template-rows: 300px 300px;
    gap: 10px;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 40px;
}

.gallery-main {
    grid-row: span 2;
}

.gallery-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s;
}

.gallery-img:hover {
    transform: scale(1.05);
}

.villa-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.villa-main {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.villa-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.villa-location {
    font-size: 1.1rem;
    color: #718096;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.villa-features {
    display: flex;
    gap: 30px;
    padding: 20px 0;
    border-top: 2px solid #f7fafc;
    border-bottom: 2px solid #f7fafc;
    margin-bottom: 30px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #4a5568;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
}

.villa-description {
    line-height: 1.8;
    color: #4a5568;
    margin-bottom: 30px;
}

.amenities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}

.amenity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f7fafc;
    border-radius: 8px;
}

.booking-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    position: sticky;
    top: 100px;
    height: fit-content;
}

.price-display {
    font-size: 2.5rem;
    font-weight: 700;
    color: #10b981;
    margin-bottom: 20px;
}

.price-label {
    font-size: 1rem;
    color: #718096;
    font-weight: normal;
}

.booking-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #1e3a8a;
}

.btn-book-now {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: transform 0.3s;
}

.btn-book-now:hover {
    transform: translateY(-3px);
}

.price-breakdown {
    background: #f7fafc;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.price-total {
    font-weight: 700;
    font-size: 1.2rem;
    padding-top: 15px;
    border-top: 2px solid #e2e8f0;
    margin-top: 10px;
}

@media (max-width: 968px) {
    .villa-content {
        grid-template-columns: 1fr;
    }

    .booking-card {
        position: relative;
        top: 0;
    }

    .villa-gallery {
        grid-template-columns: 1fr;
        grid-template-rows: 300px;
    }

    .gallery-main {
        grid-row: span 1;
    }

    .amenities-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="villa-detail-container">
    <!-- Image Gallery -->
    <?php if (!empty($images)): ?>
        <div class="villa-gallery">
            <div class="gallery-main">
                <img src="<?= htmlspecialchars($images[0]['image']) ?>" alt="<?= htmlspecialchars($v['name']) ?>" class="gallery-img">
            </div>
            <?php for($i = 1; $i < min(4, count($images)); $i++): ?>
                <div>
                    <img src="<?= htmlspecialchars($images[$i]['image']) ?>" alt="<?= htmlspecialchars($v['name']) ?>" class="gallery-img">
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <div class="villa-content">
        <!-- Main Content -->
        <div class="villa-main">
            <h1 class="villa-title"><?= htmlspecialchars($v['name'] ?? 'Untitled Villa') ?></h1>

            <p class="villa-location">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <?= htmlspecialchars($v['location'] ?? 'Location not specified') ?>
            </p>

            <div class="villa-features">
                <?php if ($v['guests'] ?? 0): ?>
                    <div class="feature-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        </svg>
                        <strong><?= $v['guests'] ?></strong> Guests
                    </div>
                <?php endif; ?>

                <?php if ($v['bedrooms'] ?? 0): ?>
                    <div class="feature-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3 5a2 2 0 0 0-2 2v2h2V7a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v2h2V7a2 2 0 0 0-2-2H3z"/>
                            <path d="M1 11v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1z"/>
                        </svg>
                        <strong><?= $v['bedrooms'] ?></strong> Bedrooms
                    </div>
                <?php endif; ?>

                <?php if ($v['beds'] ?? 0): ?>
                    <div class="feature-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 6h16v3H0z"/>
                        </svg>
                        <strong><?= $v['beds'] ?></strong> Beds
                    </div>
                <?php endif; ?>

                <?php if ($v['bathrooms'] ?? 0): ?>
                    <div class="feature-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 6v3c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V6H0z"/>
                        </svg>
                        <strong><?= $v['bathrooms'] ?></strong> Bathrooms
                    </div>
                <?php endif; ?>
            </div>

            <h3 class="section-title">About this place</h3>
            <p class="villa-description">
                <?= nl2br(htmlspecialchars($v['description'] ?? 'No description available')) ?>
            </p>

            <?php if (!empty($v['amenities'])): ?>
                <h3 class="section-title">Amenities</h3>
                <div class="amenities-grid">
                    <?php
                    $amenities = explode(',', $v['amenities']);
                    foreach($amenities as $amenity):
                        $amenity = trim($amenity);
                        if ($amenity):
                    ?>
                        <div class="amenity-item">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                            <?= htmlspecialchars($amenity) ?>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Booking Card -->
        <div class="booking-card" id="book">
            <div class="price-display">
                ₹<?= number_format($v['weekday_price'] ?? 0) ?>
                <span class="price-label">/night</span>
            </div>

            <?php if ($isLoggedIn && API::getUserRole() === 'user'): ?>
                <form id="bookingForm" class="booking-form">
                    <input type="hidden" name="villa_id" value="<?= $villaId ?>">

                    <div class="form-group">
                        <label>Check-in</label>
                        <input type="date" name="check_in" id="check_in" class="form-control" required min="<?= date('Y-m-d') ?>" onchange="calculatePrice()">
                    </div>

                    <div class="form-group">
                        <label>Check-out</label>
                        <input type="date" name="check_out" id="check_out" class="form-control" required min="<?= date('Y-m-d') ?>" onchange="calculatePrice()">
                    </div>

                    <div class="form-group">
                        <label>Number of Guests</label>
                        <select name="guests" class="form-control" required>
                            <?php for($i = 1; $i <= ($v['guests'] ?? 10); $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div id="price-breakdown" style="display: none;">
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span id="nights-label">1 night</span>
                                <span id="base-price">₹0</span>
                            </div>
                            <div class="price-row">
                                <span>Service fee</span>
                                <span id="service-fee">₹0</span>
                            </div>
                            <div class="price-row price-total">
                                <span>Total</span>
                                <span id="total-price">₹0</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-book-now">Book Now</button>

                    <p style="text-align: center; color: #718096; font-size: 0.9rem; margin-top: 15px;">
                        You won't be charged yet
                    </p>
                </form>
            <?php else: ?>
                <div style="text-align: center; padding: 20px;">
                    <p style="color: #718096; margin-bottom: 20px;">Please login to book this villa</p>
                    <a href="login.php?redirect=villa-detail.php?id=<?= $villaId ?>" class="btn-book-now" style="display: inline-block; text-decoration: none;">Login to Book</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const weekdayPrice = <?= $v['weekday_price'] ?? 0 ?>;
const weekendPrice = <?= $v['weekend_price'] ?? ($v['weekday_price'] ?? 0) ?>;

function calculatePrice() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;

    if (!checkIn || !checkOut) return;

    const start = new Date(checkIn);
    const end = new Date(checkOut);
    const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

    if (nights < 1) {
        alert('Check-out must be after check-in');
        return;
    }

    // Simple calculation (you can make this more sophisticated)
    const basePrice = nights * weekdayPrice;
    const serviceFee = basePrice * 0.05; // 5% service fee
    const totalPrice = basePrice + serviceFee;

    document.getElementById('nights-label').textContent = `${nights} night${nights > 1 ? 's' : ''}`;
    document.getElementById('base-price').textContent = `₹${basePrice.toLocaleString('en-IN')}`;
    document.getElementById('service-fee').textContent = `₹${serviceFee.toLocaleString('en-IN')}`;
    document.getElementById('total-price').textContent = `₹${totalPrice.toLocaleString('en-IN')}`;
    document.getElementById('price-breakdown').style.display = 'block';
}

<?php if ($isLoggedIn && API::getUserRole() === 'user'): ?>
document.getElementById('bookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/create-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer <?= API::getToken() ?>'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.status) {
            alert('Booking created successfully!');
            window.location.href = 'user-dashboard.php';
        } else {
            alert('Error: ' + (result.message || 'Failed to create booking'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while creating the booking');
    }
});
<?php endif; ?>
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
