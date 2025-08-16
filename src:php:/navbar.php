<?php
if (session_status() === PHP_SESSION_NONE) session_start();
//echo '<pre>'; print_r($_SESSION); echo '</pre>'; // 👈 For debugging only!
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">Fish R Us</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="stores.php">Stores</a></li>
        <li class="nav-item"><a class="nav-link" href="vendors.php">Vendors</a></li>
        <li class="nav-item"><a class="nav-link" href="kpis.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="view-user.php">Users</a></li>
        <!-- Add more nav items here -->
      </ul>
      
   <!-- Optional: User dropdown on right -->
<ul class="navbar-nav ms-auto">
  <?php if (isset($_SESSION['username'])): ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
        <?= htmlspecialchars($_SESSION['username']) ?>
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      </ul>
    </li>
  <?php else: ?>
    <li class="nav-item">
      <a class="nav-link" href="login-form.php">Login</a>
    </li>
  <?php endif; ?>
</ul>
    </div>
  </div>
</nav>