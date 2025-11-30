<?php 
$api = "https://topmost.in/api/home-data";

$data = json_decode(file_get_contents($api), true);

$sliders = $data["sliders"] ?? [];
$categories = $data["categories"] ?? [];
$listings = $data["listings"] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>TopMost Villas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="home.php">TopMost</a>
</nav>

<div class="container mt-4">

    <!-- SLIDER -->
    <?php if (!empty($sliders)) : ?>
        <div id="slider" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($sliders as $i => $s): ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                        <img src="<?= $s ?>" class="d-block w-100" height="300" style="object-fit: cover;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- CATEGORIES -->
    <h3>Categories</h3>
    <div class="row mb-4">
        <?php foreach ($categories as $c): ?>
            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm">
                    <?= $c ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- LISTINGS -->
    <h3>Popular Villas</h3>
    <div class="row">
        <?php foreach ($listings as $v): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="<?= $v['image'] ?>" class="card-img-top" height="180" style="object-fit: cover;">
                    <div class="card-body">
                        <h5><?= $v['title'] ?></h5>
                        <p class="text-muted">₹<?= $v['price'] ?></p>
                        <a href="villa.php?id=<?= $v['id'] ?>" class="btn btn-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
