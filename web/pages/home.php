<?php 
include "../helpers/api.php"; 
include "../includes/header.php";

$data = API::get("home-data");
?>

<div class="container mt-4">

    <!-- SLIDER -->
    <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <?php foreach($data["sliders"] as $i => $img): ?>
            <div class="carousel-item <?= $i==0 ? "active" : "" ?>">
                <img src="<?= $img ?>" class="d-block w-100" style="height:380px;object-fit:cover;">
            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <h3 class="mt-4">Popular Villas</h3>
    <div class="row">
        <?php foreach($data["listings"] as $v): ?>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <img src="<?= $v["image"] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $v["title"] ?></h5>
                        <p class="text-success fw-bold">₹<?= $v["price"] ?></p>
                        <a href="villa-detail.php?id=<?= $v['id'] ?>" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include "../includes/footer.php"; ?>
