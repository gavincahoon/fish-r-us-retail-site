<?php include 'navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fish R US | Home</title>

<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Hero / Carousel -->
<header class="bg-light">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active">
        <img src="img/koi.webp" class="d-block w-100" style="height: 95vh; object-fit: cover;" alt="Tropical Fish">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h1 class="fw-bolder">Welcome to Fish R US</h1>
          <p class="lead">Your one-stop shop for fish, tanks, and aquatic supplies.</p>
          <a class="btn btn-primary btn-lg" href="products.php">Shop Now</a>
        </div>
      </div>
      <!-- Slide 2 -->
      <div class="carousel-item">
        <img src="img/coolfish2.jpg" class="d-block w-100" style="height: 95vh; object-fit: cover;" alt="Aquarium Supplies">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h1 class="fw-bolder">Freshwater or Saltwater?</h1>
          <p class="lead">Explore species and supplies to fit any tank.</p>
          <a class="btn btn-primary btn-lg" href="products.php">Browse Now</a>
        </div>
      </div>
      <!-- Slide 3 -->
      <div class="carousel-item">
        <img src="img/whitered1.jpg" class="d-block w-100" style="height: 95vh; object-fit: cover;" alt="Fish Food">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h1 class="fw-bolder">Keep Your Fish Happy</h1>
          <p class="lead">Top-quality food, filters, and care products.</p>
          <a class="btn btn-primary btn-lg" href="products.php">View Products</a>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</header>

<!-- Featured Products (static placeholders) -->
<section class="py-5">
  <div class="container px-4 px-lg-5 mt-5">
    <h2 class="fw-bold mb-4">Featured Products</h2>
    <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center">
      <div class="col mb-5">
        <div class="card h-100 shadow-sm">
          <!-- Product image-->
          <img class="card-img-top" src="img/red_goldfish.webp" alt="Product" />
          <!-- Product details-->
          <div class="card-body p-4">
            <h5 class="card-title">Fancy Goldfish</h5>
            <p class="card-text">Bright and lively freshwater companion.</p>
          </div>
        </div>
      </div>
      <div class="col mb-5">
        <div class="card h-100 shadow-sm">
          <img class="card-img-top" src="img/african_cichlid.jpg" alt="Product" />
          <div class="card-body p-4">
            <h5 class="card-title">African Cichlid</h5>
            <p class="card-text">Vibrant colors straight from Lake Malawi.</p>
          </div>
        </div>
      </div>
      <div class="col mb-5">
        <div class="card h-100 shadow-sm">
          <img class="card-img-top" src="img/nano.jpg" alt="Product" />
          <div class="card-body p-4">
            <h5 class="card-title">Nano Aquarium</h5>
            <p class="card-text">Perfect 10‑gallon starter tank.</p>
          </div>
        </div>
      </div>
      <div class="col mb-5">
        <div class="card h-100 shadow-sm">
          <img class="card-img-top" src="img/plant_food.jpg" alt="Product" />
          <div class="card-body p-4">
            <h5 class="card-title">Aquatic Plant Food</h5>
            <p class="card-text">Keep your plants lush and green.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-primary text-white">
  <div class="container text-center">
    © <?php echo date('Y'); ?> Fish R US. All rights reserved.
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
