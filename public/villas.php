<?php
require_once __DIR__ . "/../helpers/api.php";
include __DIR__ . "/../includes/header.php";

$location = trim($_GET['location'] ?? '');
$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$guests = intval($_GET['guests'] ?? 0);
$minPrice = intval($_GET['min_price'] ?? 0);
$maxPrice = intval($_GET['max_price'] ?? 0);
$amenities = $_GET['amenities'] ?? [];

$params = [];
if ($location) $params['location'] = $location;
if ($checkIn) $params['check_in'] = $checkIn;
if ($checkOut) $params['check_out'] = $checkOut;
if ($guests) $params['guests'] = $guests;
if ($minPrice) $params['min_price'] = $minPrice;
if ($maxPrice) $params['max_price'] = $maxPrice;
if (!empty($amenities)) $params['amenities'] = implode(',', $amenities);

if (!empty($params)) {
    $res = API::get("search-villas", $params);
    $villas = $res['villas'] ?? [];
} else {
    $res = API::get("villas");
    $villas = $res['villas'] ?? [];
}
?>

<style>
.search-container {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    padding: 60px 0;
    margin-bottom: 40px;
}

.search-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.search-title {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    text-align: center;
}

.search-subtitle {
    color: rgba(255,255,255,0.9);
    text-align: center;
    margin-bottom: 30px;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #2d3748;
}

.villa-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}

.villa-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.villa-img {
    height: 240px;
    object-fit: cover;
    width: 100%;
}

.villa-card-body {
    padding: 20px;
}

.villa-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.villa-location {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 10px;
}

.villa-features {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    color: #4a5568;
    font-size: 0.9rem;
}

.villa-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #10b981;
    margin-bottom: 15px;
}

.btn-view {
    background: white;
    border: 2px solid #1e3a8a;
    color: #1e3a8a;
    font-weight: 600;
}

.btn-view:hover {
    background: #1e3a8a;
    color: white;
}

.btn-book {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border: none;
    color: white;
    font-weight: 600;
}

.btn-book:hover {
    transform: translateY(-2px);
}

.amenity-checkbox {
    margin-right: 15px;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
}

.form-control:focus, .form-select:focus {
    border-color: #1e3a8a;
    box-shadow: none;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.results-count {
    color: #718096;
}
</style>

<div class="search-container">
    <div class="container">
        <h1 class="search-title">Find Your Perfect Villa</h1>
        <p class="search-subtitle">Search from thousands of luxury villas across India</p>

        <div class="search-card">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Where to?" value="<?= htmlspecialchars($location) ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Check-in</label>
                        <input type="date" name="check_in" class="form-control" value="<?= htmlspecialchars($checkIn) ?>" min="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Check-out</label>
                        <input type="date" name="check_out" class="form-control" value="<?= htmlspecialchars($checkOut) ?>" min="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Guests</label>
                        <select name="guests" class="form-select">
                            <option value="">Any</option>
                            <?php for($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>" <?= $guests == $i ? 'selected' : '' ?>><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <button type="submit" class="btn btn-book w-100 d-block" style="padding: 12px;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            Search Villas
                        </button>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-link text-decoration-none" onclick="document.getElementById('advanced-filters').style.display = document.getElementById('advanced-filters').style.display === 'none' ? 'block' : 'none'">
                            Advanced Filters
                        </button>

                        <div id="advanced-filters" style="display: none; margin-top: 15px;">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Min Price</label>
                                    <input type="number" name="min_price" class="form-control" placeholder="₹0" value="<?= $minPrice ?: '' ?>">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Max Price</label>
                                    <input type="number" name="max_price" class="form-control" placeholder="₹50000" value="<?= $maxPrice ?: '' ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Amenities</label>
                                    <div class="d-flex flex-wrap">
                                        <?php
                                        $amenitiesList = ['Pool', 'AC', 'WiFi', 'Parking', 'Kitchen', 'Pet Friendly'];
                                        foreach($amenitiesList as $amenity): ?>
                                            <div class="amenity-checkbox">
                                                <input type="checkbox" name="amenities[]" value="<?= $amenity ?>" id="amenity-<?= strtolower(str_replace(' ', '-', $amenity)) ?>" <?= in_array($amenity, $amenities) ? 'checked' : '' ?>>
                                                <label for="amenity-<?= strtolower(str_replace(' ', '-', $amenity)) ?>"><?= $amenity ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="results-header">
        <div>
            <h3>Available Villas</h3>
            <p class="results-count"><?= count($villas) ?> properties found</p>
        </div>

        <?php if (!empty($params)): ?>
            <a href="villas.php" class="btn btn-outline-secondary">Clear Filters</a>
        <?php endif; ?>
    </div>

    <?php if (empty($villas)): ?>
        <div class="alert alert-info text-center" style="padding: 60px;">
            <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.5; margin-bottom: 20px;">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
            </svg>
            <h4>No villas found</h4>
            <p class="text-muted">Try adjusting your search filters</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($villas as $v):
                $id = $v['id'] ?? '';
                $name = $v['name'] ?? $v['title'] ?? 'Untitled Villa';
                $location = $v['location'] ?? '';
                $image = $v['image'] ?? '';
                $weekdayPrice = $v['weekday_price'] ?? $v['price'] ?? 0;
                $guests = $v['guests'] ?? 0;
                $bedrooms = $v['bedrooms'] ?? 0;
                $bathrooms = $v['bathrooms'] ?? 0;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="villa-card">
                        <?php if ($image): ?>
                            <img src="<?= htmlspecialchars($image) ?>" class="villa-img" alt="<?= htmlspecialchars($name) ?>">
                        <?php else: ?>
                            <div class="villa-img bg-light d-flex align-items-center justify-content-center">
                                <svg width="64" height="64" fill="#ccc" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="villa-card-body">
                            <h5 class="villa-title"><?= htmlspecialchars($name) ?></h5>
                            <p class="villa-location">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                <?= htmlspecialchars($location) ?>
                            </p>

                            <?php if ($guests || $bedrooms || $bathrooms): ?>
                                <div class="villa-features">
                                    <?php if ($guests): ?>
                                        <span>
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                            </svg>
                                            <?= $guests ?> guests
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($bedrooms): ?>
                                        <span>
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 5a2 2 0 0 0-2 2v2h2V7a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v2h2V7a2 2 0 0 0-2-2H3z"/>
                                                <path d="M1 11v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1z"/>
                                            </svg>
                                            <?= $bedrooms ?> beds
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($bathrooms): ?>
                                        <span>
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 6v3c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V6H0z"/>
                                            </svg>
                                            <?= $bathrooms ?> baths
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <p class="villa-price">₹<?= number_format($weekdayPrice) ?> <span style="font-size: 0.9rem; font-weight: normal; color: #718096;">/night</span></p>

                            <div class="d-flex gap-2">
                                <a href="villa-detail.php?id=<?= $id ?>" class="btn btn-view flex-fill">View Details</a>
                                <a href="villa-detail.php?id=<?= $id ?>#book" class="btn btn-book flex-fill">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
