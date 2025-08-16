<?php
$allowedRoles = ['admin','employee','customer'];  
include 'checksession.php';                     
include 'db.php';                                
include 'navbar.php';

$role   = $_SESSION['role']  ?? 'customer';
$userId = $_SESSION['user_id'] ?? 0;        
$action = $_GET['action']    ?? 'list';

/*  ----------  CREATE  ---------- */
if ($action === 'create' && $role === 'customer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $totalPrice = floatval($_POST['total_price'] ?? 0);
    if (isset($_POST['has_discount'])) $totalPrice *= 0.9;

    $stmt = $pdo->prepare(
        "INSERT INTO orders (customer_id, total_price, status, created_at)
         VALUES (:cid, :tot, 'Processing', NOW())"
    );
    $stmt->execute([':cid'=>$userId, ':tot'=>$totalPrice]);
    header('Location: orders.php?success=Order%20placed');
    exit;
}

/*  ----------  UPDATE (status)  ---------- */
if ($action === 'update' && in_array($role, ['admin', 'employee']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE orders SET status = :st WHERE id = :id");
    $stmt->execute([':st'=>$_POST['status'], ':id'=>$_POST['order_id']]);
    header('Location: orders.php?success=Status%20updated');
    exit;
}

$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if ($action === 'delete' && $order_id > 0) {
    try {
        // First delete the order_items for this order
        $stmt1 = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt1->execute([$order_id]);

        // Then delete the order itself
        $stmt2 = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt2->execute([$order_id]);

        echo "<p>Order deleted successfully.</p>";
    } catch (PDOException $e) {
        echo "<p>Error deleting order: " . $e->getMessage() . "</p>";
    }
}

/*  ----------  READ (detail or list)  ---------- */
if ($action === 'detail') {
    $stmt  = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([ (int)$_GET['id'] ]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    if ($role === 'customer') {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    }
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*  ----------  HTML OUTPUT  ---------- */
function h($s){ return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
<h1 class="mb-4">Order Processing</h1>

<?php if (!empty($_GET['success'])): ?>
  <div class="alert alert-success"><?= h($_GET['success']) ?></div>
<?php endif; ?>

<?php if ($action === 'detail' && $order): ?>
   <div class="card mb-3">
     <div class="card-header d-flex justify-content-between">
       <span>Order #<?= h($order['id']) ?></span>
       <span class="badge bg-secondary"><?= h($order['status']) ?></span>
     </div>
     <div class="card-body">
       <p><strong>Customer ID:</strong> <?= h($order['customer_id']) ?></p>
       <p><strong>Total:</strong> $<?= number_format($order['total_price'],2) ?></p>
       <p><strong>Date:</strong> <?= h($order['created_at']) ?></p>
     </div>
   </div>
   <a href="orders.php" class="btn btn-outline-primary">Back to list</a>

<?php else: /* -------- LIST + CREATE FORM -------- */ ?>

<?php if ($role === 'customer'): ?>
<div class="card mb-4">
  <div class="card-header">Place New Order</div>
  <div class="card-body">
    <form method="POST" action="orders.php?action=create" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Total Price ($)</label>
        <input type="number" step="0.01" name="total_price" class="form-control" required>
      </div>
      <div class="col-md-6 align-self-end">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="has_discount" id="has_discount">
          <label class="form-check-label" for="has_discount">Apply 10% Discount</label>
        </div>
      </div>
      <div class="col-12"><button class="btn btn-primary">Place Order</button></div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">Order History</div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Customer</th><th>Total ($)</th><th>Status</th><th>Date</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= h($o['id']) ?></td>
          <td><?= h($o['customer_id']) ?></td>
          <td><?= number_format($o['total_price'],2) ?></td>
          <td>
            <?php if (in_array($role, ['admin', 'employee'])): ?>
              <form class="d-flex gap-2" method="POST" action="orders.php?action=update">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <select name="status" class="form-select form-select-sm">
                  <?php foreach (['Processing','Shipped','Delivered','Cancelled'] as $st): ?>
                    <option <?= $o['status']===$st?'selected':'' ?>><?= $st ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-outline-primary">Save</button>
              </form>
            <?php else: ?>
              <span class="badge bg-secondary"><?= h($o['status']) ?></span>
            <?php endif; ?>
          </td>
          <td><?= h($o['created_at']) ?></td>
          <td>
            <a href="orders.php?action=detail&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
            <?php if ($role === 'admin'): ?>
              <a href="orders.php?action=delete&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Delete order?');">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php endif; /* end list/detail branching */ ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>