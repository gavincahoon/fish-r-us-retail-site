<?php
$allowedRoles = ['admin','employee','customer'];  
include 'checksession.php';                     
include 'db.php';                                
include 'navbar.php';

$role  = $_SESSION['view_as'] ?? $_SESSION['role'] ?? 'customer';
$pdo   = new PDO('mysql:host=localhost;dbname=Fish R US;charset=utf8mb4','root','root',
                 [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

// --------- ADD-TO-CART HANDLER ----------
if (isset($_GET['add'])) {
    $pid = (int)$_GET['add'];
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    header("Location: products.php?success=Added%20to%20cart");
    exit;
}

// Get list of categories
$categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Check for filter
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($categoryFilter > 0) {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category
                           FROM products p
                           LEFT JOIN categories c ON p.category_id = c.id
                           WHERE p.category_id = ?");
    $stmt->execute([$categoryFilter]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT p.*, c.name AS category
                             FROM products p
                             LEFT JOIN categories c ON p.category_id = c.id")
                    ->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html><head></head>
<body class="bg-light">
<div class="container py-4">
<h1 class="mb-4">Products</h1>

<form method="GET" class="mb-3">
  <div class="row g-2 align-items-center">
    <div class="col-auto">
      <label for="category" class="col-form-label">Filter by Category:</label>
    </div>
    <div class="col-auto">
      <select name="category" id="category" class="form-select" onchange="this.form.submit()">
        <option value="0">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $categoryFilter == $c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</form>

<?php if (!empty($_GET['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<a href="cart.php" class="btn btn-outline-primary mb-3">View Cart (<?= array_sum($_SESSION['cart'] ?? []) ?>)</a>

<!-- PRODUCT GRID -->
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php foreach ($products as $p): ?>
  <div class="col">
    <div class="card h-100 shadow-sm">
 <img src="<?= htmlspecialchars($p['image_url'] ?: 'images/placeholder.jpg') ?>"
     class="card-img-top"
     alt="<?= htmlspecialchars($p['name']) ?>"
     style="height:400px;object-fit:cover">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
        <p class="card-text"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        <p class="card-text">$<?= number_format($p['price'],2) ?></p>
        <a href="?add=<?= $p['id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
      </div>
      <?php if ($role==='admin'): ?>
        <div class="card-footer text-end">
          <a href="products.php?action=delete&id=<?= $p['id'] ?>"
             class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Delete product?');">Delete</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>