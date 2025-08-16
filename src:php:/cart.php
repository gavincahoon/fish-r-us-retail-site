<?php
session_start();

// Handle removal of item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = (int)$_POST['remove_id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    // Redirect to avoid form resubmission on refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

include 'navbar.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    echo "<div class='container py-4'><h3>Your cart is empty.</h3></div>";
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=Fish R US;charset=utf8mb4', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Fetch product info for the items in cart
$ids = implode(',', array_keys($cart));
$stmt = $pdo->query("SELECT id, name, price FROM products WHERE id IN ($ids)");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Your Cart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1 class="mb-4">Shopping Cart</h1>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $it):
        $qty = $cart[$it['id']];
        $sub = $qty * $it['price'];
        $total += $sub; ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td><?= $qty ?></td>
        <td>$<?= number_format($it['price'], 2) ?></td>
        <td>$<?= number_format($sub, 2) ?></td>
        <td>
          <form method="post" style="margin:0;">
            <input type="hidden" name="remove_id" value="<?= $it['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr class="table-light fw-bold">
      <td colspan="3" class="text-end">Total</td>
      <td>$<?= number_format($total, 2) ?></td>
      <td></td>
    </tr>
    </tbody>
  </table>
  <div class="d-flex justify-content-between">
    <a href="products.php" class="btn btn-outline-secondary">&larr; Continue Shopping</a>
    <a href="checkout.php" class="btn btn-primary">Checkout</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>