<?php

include 'navbar.php';

$role  = $_SESSION['view_as'] ?? $_SESSION['role'] ?? 'customer';

$pdo   = new PDO('mysql:host=localhost;dbname=Fish R US;charset=utf8mb4','root','root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$cart = $_SESSION['cart'] ?? [];

echo "<div class='container py-4'>";
echo "<h2 class='mb-4'>Checkout</h2>";

if (empty($cart)) {
    echo "<div class='alert alert-warning'>Your cart is empty.</div>";
    echo "<a href='products.php' class='btn btn-outline-primary'>Back to shop</a></div>";
    exit;
}

$total = 0;

echo "<ul class='list-group mb-3'>";
foreach ($cart as $product_id => $qty) {
    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $name = $product['name'];
        $price = $product['price'];
        $subtotal = $price * $qty;
        $total += $subtotal;

        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
        echo htmlspecialchars($name) . " - $qty x $" . number_format($price, 2);
        echo "<span class='badge bg-secondary'>\$" . number_format($subtotal, 2) . "</span>";
        echo "</li>";
    }
}
echo "</ul>";

echo "<h4>Total: $" . number_format($total, 2) . "</h4>";
?>

<form action="process_checkout.php" method="post" class="mt-4">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="customer_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="customer_email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Confirm Purchase</button>
    <a href="cart.php" class="btn btn-outline-secondary ms-2">Back to Cart</a>
</form>
</div>
