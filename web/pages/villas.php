<?php
// public_html/web/pages/villas.php

require_once __DIR__ . "/../helpers/api.php";
include __DIR__ . "/../includes/header.php";

// get search query if any
$q = trim($_GET['q'] ?? '');

// fetch data
if ($q !== '') {
    // use search endpoint
    $res = API::get("search", ["keyword" => $q]);
    // Some APIs return { status: true, results: [...] } or just an array.
    if (isset($res['status']) && $res['status'] === true && isset($res['results'])) {
        $villas = $res['results'];
    } elseif (isset($res['results'])) {
        $villas = $res['results'];
    } elseif (isset($res['data'])) {
        $villas = $res['data'];
    } else {
        // fallback if API returns array directly
        $villas = is_array($res) ? $res : [];
    }
} else {
    $res = API::get("villas");
    // expected: { status: true, villas: [...] }
    if (isset($res['status']) && $res['status'] === true && isset($res['villas'])) {
        $villas = $res['villas'];
    } elseif (isset($res['villas'])) {
        $villas = $res['villas'];
    } else {
        $villas = is_array($res) ? $res : [];
    }
}
?>

<div class="container mt-4">
  <div class="row mb-3 align-items-center">
    <div class="col-md-6">
      <h3>All Villas</h3>
      <p class="text-muted">Browse villas — search, filter and book your stay.</p>
    </div>

    <div class="col-md-6">
      <form class="d-flex" method="get" action="">
        <input name="q" value="<?= htmlspecialchars($q) ?>" class="form-control me-2" placeholder="Search villas, city, or location">
        <button class="btn btn-primary">Search</button>
      </form>
    </div>
  </div>

  <?php if (empty($villas)): ?>
    <div class="alert alert-info">No villas found.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($villas as $v): 
          // normalize fields (different endpoints may return slightly different keys)
          $id = $v['id'] ?? $v['villa_id'] ?? '';
          $title = $v['title'] ?? $v['name'] ?? 'Untitled Villa';
          $price = $v['price'] ?? ($v['amount'] ?? '0');
          $image = $v['image'] ?? ($v['photo'] ?? '');
          $location = $v['location'] ?? ($v['city'] ?? '');
          $owner = $v['owner_name'] ?? ($v['owner'] ?? '');
      ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if ($image): ?>
              <img src="<?= htmlspecialchars($image) ?>" class="card-img-top" style="height:220px; object-fit:cover;">
            <?php else: ?>
              <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
                <i class="bi bi-house" style="font-size:48px;color:#ccc;"></i>
              </div>
            <?php endif; ?>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>

              <p class="mb-1 text-muted" style="font-size:0.95rem;">
                <?= htmlspecialchars($location) ?>
                <?php if ($owner): ?> • Owner: <strong><?= htmlspecialchars($owner) ?></strong><?php endif; ?>
              </p>

              <p class="mt-auto mb-2 fw-bold text-success">₹<?= number_format((float)$price) ?>/night</p>

              <div class="d-flex justify-content-between">
                <a href="villa-detail.php?id=<?= urlencode($id) ?>" class="btn btn-outline-primary btn-sm">View</a>
                <a href="booking.php?villa_id=<?= urlencode($id) ?>" class="btn btn-primary btn-sm">Book Now</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
