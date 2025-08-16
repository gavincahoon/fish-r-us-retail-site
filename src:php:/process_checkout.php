<?php

include 'navbar.php';

$customerId = $_SESSION['user_id'] ?? null;
$cart       = $_SESSION['cart'] ?? [];

// Connect with PDO

$pdo = new PDO('mysql:host=localhost;dbname=Fish R US;charset=utf8mb4','root','root',[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);


// Fetch product prices in a single query

$ids = implode(',', array_keys($cart));   
$query = $pdo->query("SELECT id, price FROM products WHERE id IN ($ids)");
$prices = $query->fetchAll(PDO::FETCH_KEY_PAIR); 

$total = 0;
foreach ($cart as $pid => $qty) {
    if (!isset($prices[$pid])) continue;    
    $total += $prices[$pid] * $qty;
}


// Insert into orders table

$stmt = $pdo->prepare("INSERT INTO orders
            (customer_id, total_price, discount, status, created_at)
            VALUES (:cust, :total, 0, 'Processing', NOW())");
$stmt->execute([
    ':cust'  => $customerId,
    ':total' => $total
]);
$orderId = $pdo->lastInsertId();

//Insert line-items in one prepared statement

$itemStmt = $pdo->prepare("INSERT INTO order_items
            (order_id, product_id, quantity, price)
            VALUES (:oid, :pid, :qty, :price)");

foreach ($cart as $pid => $qty) {
    if (!isset($prices[$pid])) continue;
    $itemStmt->execute([
        ':oid'   => $orderId,
        ':pid'   => $pid,
        ':qty'   => $qty,
        ':price' => $prices[$pid]
    ]);
}

// Clear cart and show confirmation

unset($_SESSION['cart']);

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Checkout Success</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container py-5 text-center">
    <h2 class="mb-4">Thank you for your purchase!</h2>
    <p class="lead">Your order number is <strong>#<?= $orderId ?></strong>.</p>
    <p>Total charged: <strong>$<?= number_format($total, 2) ?></strong></p>
    <a href="products.php" class="btn btn-primary mt-3">Continue Shopping</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>