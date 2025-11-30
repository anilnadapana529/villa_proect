<?php
include __DIR__ . "/../helpers/api.php";
include __DIR__ . "/../includes/header.php";

$data = API::get("home-data");
$sliders = $data["sliders"] ?? [];
$listings = $data["listings"] ?? [];
?>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
    animation: fadeIn 0.8s ease;
}

.hero-section h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    animation: fadeInUp 1s ease 0.2s both;
}

.hero-section p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    animation: fadeInUp 1s ease 0.4s both;
}

.search-box {
    background: white;
    border-radius: 50px;
    padding: 8px;
    max-width: 600px;
    margin: 0 auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    animation: scaleIn 1s ease 0.6s both;
}

.search-box input {
    border: none;
    padding: 12px 20px;
    width: 100%;
    border-radius: 50px;
    font-size: 1rem;
}

.search-box input:focus {
    outline: none;
}

.search-box button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 60px 0 40px;
    text-align: center;
    color: #2d3748;
}

.villa-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    animation: fadeInUp 0.6s ease both;
    animation-delay: calc(var(--card-index) * 0.1s);
}

.villa-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.villa-card img {
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s;
}

.villa-card:hover img {
    transform: scale(1.05);
}

.villa-card .card-body {
    padding: 24px;
}

.villa-card .card-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
}

.villa-card .location {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.villa-card .price {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 16px;
}

.villa-card .price small {
    font-size: 0.9rem;
    font-weight: 400;
    color: #718096;
}

.villa-card .amenities {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: #4a5568;
}

.villa-card .amenity-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-view-villa {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    width: 100%;
    transition: transform 0.2s;
}

.btn-view-villa:hover {
    transform: translateY(-2px);
    color: white;
}

.badge-featured {
    position: absolute;
    top: 16px;
    right: 16px;
    background: #f59e0b;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 10;
}

.badge-verified {
    position: absolute;
    top: 16px;
    left: 16px;
    background: #10b981;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 10;
}

.stats-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    margin: 80px 0;
}

.stat-item {
    text-align: center;
    animation: fadeInUp 0.8s ease both;
    animation-delay: calc(var(--stat-index) * 0.15s);
}

.stat-item h3 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.stat-item p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.section-title {
    animation: fadeInUp 0.8s ease;
}

@media (max-width: 992px) {
    .hero-section {
        padding: 60px 0;
    }

    .hero-section h1 {
        font-size: 2.8rem;
    }

    .hero-section p {
        font-size: 1.1rem;
    }

    .villa-card img {
        height: 220px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 50px 20px;
    }

    .hero-section h1 {
        font-size: 2.2rem;
    }

    .hero-section p {
        font-size: 1rem;
    }

    .section-title {
        font-size: 1.8rem;
    }

    .stat-item h3 {
        font-size: 2.5rem;
    }

    .stat-item p {
        font-size: 1rem;
    }

    .villa-card img {
        height: 200px;
    }

    .search-box {
        flex-direction: column;
        padding: 15px;
    }

    .search-box button {
        width: 100%;
        margin-top: 10px;
    }
}

@media (max-width: 576px) {
    .hero-section h1 {
        font-size: 1.8rem;
    }

    .stats-section {
        padding: 40px 0;
        margin: 50px 0;
    }

    .amenities {
        flex-wrap: wrap;
    }
}
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1>Find Your Perfect Villa</h1>
        <p>Discover luxury villas in stunning locations worldwide</p>
        <div class="search-box d-flex">
            <input type="text" placeholder="Search by location, name, or amenities..." id="searchInput">
            <button onclick="searchVillas()">Search</button>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item" style="--stat-index: 0;">
                    <h3>500+</h3>
                    <p>Premium Villas</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" style="--stat-index: 1;">
                    <h3>50+</h3>
                    <p>Locations</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" style="--stat-index: 2;">
                    <h3>10k+</h3>
                    <p>Happy Guests</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item" style="--stat-index: 3;">
                    <h3>4.9★</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Villas -->
<div class="container">
    <h2 class="section-title">Featured Villas</h2>
    <div class="row" id="villasContainer">
        <?php if (empty($listings)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">No villas available at the moment.</p>
            </div>
        <?php else: ?>
            <?php foreach($listings as $index => $villa): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card villa-card position-relative" style="--card-index: <?= $index ?>">
                        <?php if (!empty($villa["featured"])): ?>
                            <span class="badge-featured">Featured</span>
                        <?php endif; ?>
                        <?php if (!empty($villa["verified"])): ?>
                            <span class="badge-verified">✓ Verified</span>
                        <?php endif; ?>
                        
                        <img src="<?= htmlspecialchars($villa["image"] ?? '/uploads/placeholder.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($villa["title"] ?? 'Villa') ?>">
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($villa["title"] ?? $villa["name"] ?? 'Luxury Villa') ?></h5>
                            
                            <div class="location">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                <?= htmlspecialchars($villa["location"] ?? 'Location not specified') ?>
                            </div>
                            
                            <div class="amenities">
                                <?php if (!empty($villa["guests"])): ?>
                                    <div class="amenity-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                        </svg>
                                        <?= $villa["guests"] ?> Guests
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($villa["bedrooms"])): ?>
                                    <div class="amenity-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 5a2 2 0 0 0-2 2v2h2V7a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v2h2V7a2 2 0 0 0-2-2H3z"/>
                                            <path d="M1 10.5A1.5 1.5 0 0 0 2.5 12h11a1.5 1.5 0 0 0 1.5-1.5V10H1v.5z"/>
                                        </svg>
                                        <?= $villa["bedrooms"] ?> Beds
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($villa["bathrooms"])): ?>
                                    <div class="amenity-item">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M9 1.5A1.5 1.5 0 0 0 7.5 0h-1A1.5 1.5 0 0 0 5 1.5v1h-.5A1.5 1.5 0 0 0 3 4v8.5A1.5 1.5 0 0 0 4.5 14h7a1.5 1.5 0 0 0 1.5-1.5V4a1.5 1.5 0 0 0-1.5-1.5H11v-1A1.5 1.5 0 0 0 9.5 0h-1A1.5 1.5 0 0 0 7 1.5v1h2v-1z"/>
                                        </svg>
                                        <?= $villa["bathrooms"] ?> Baths
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="price">
                                ₹<?= number_format($villa["price"] ?? $villa["weekday_price"] ?? 0) ?>
                                <small>/night</small>
                            </div>
                            
                            <a href="villa-detail.php?id=<?= $villa['id'] ?>" class="btn btn-view-villa">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function searchVillas() {
    const searchTerm = document.getElementById('searchInput').value;
    if (searchTerm.trim()) {
        window.location.href = 'villas.php?search=' + encodeURIComponent(searchTerm);
    }
}

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchVillas();
    }
});
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
