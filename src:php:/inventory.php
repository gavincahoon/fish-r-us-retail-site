<?php
$requiredRoles = ['admin', 'employee'];
include 'checksession.php';
include 'db.php';
$role = $_SESSION['role'] ?? '';
include 'navbar.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->prepare("INSERT INTO store_inventory(store_id, product_id, quantity) VALUES(?,?,?)")
      ->execute([$_POST['store_id'], $_POST['product_id'], $_POST['quantity']]);
  header('Location: inventory.php?success=Added');
  exit;
}

if ($action === 'delete' && isset($_GET['store_id'], $_GET['product_id'])) {
  $pdo->prepare("DELETE FROM store_inventory WHERE store_id=? AND product_id=?")
      ->execute([$_GET['store_id'], $_GET['product_id']]);
  header('Location: inventory.php?success=Deleted');
  exit;
}

if ($action === 'edit' && isset($_GET['store_id'], $_GET['product_id'])) {
  $stmt = $pdo->prepare("SELECT * FROM store_inventory WHERE store_id = ? AND product_id = ?");
  $stmt->execute([$_GET['store_id'], $_GET['product_id']]);
  $editInventory = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE inventory
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("UPDATE store_inventory SET quantity=? WHERE store_id=? AND product_id=?");
  $stmt->execute([
    $_POST['quantity'],
    $_POST['store_id'],
    $_POST['product_id']
  ]);
  header('Location: inventory.php?success=Updated');
  exit;
}

$inventory = $pdo->query("SELECT i.*, s.name as store, p.name as product
                          FROM store_inventory i
                          JOIN stores s ON i.store_id = s.id
                          JOIN products p ON i.product_id = p.id")
                 ->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Add HTML table here for inventory display -->
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inventory</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="mb-4">Store Inventory</h1>
<?php if (!empty($_GET['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?>

<?php if (in_array($role, ['admin', 'employee'])): ?>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#addInv">Add Stock</button>
<div class="collapse" id="addInv">
  <div class="card card-body mb-3">
    <form method="POST" action="inventory.php?action=create" class="row g-3">
      <div class="col-md-4"><input class="form-control" name="store_id" placeholder="Store ID" required></div>
      <div class="col-md-4"><input class="form-control" name="product_id" placeholder="Product ID" required></div>
      <div class="col-md-4"><input class="form-control" name="quantity" type="number" placeholder="Qty" required></div>
      <div class="col-12"><button class="btn btn-success">Save</button></div>
    </form>
  </div>
</div>
<?php if (isset($editInventory)): ?>
  <div class="card card-body mb-3 border-warning">
   <h5 class="text-warning">Editing Inventory for Store #<?= htmlspecialchars($editInventory['store_id']) ?> and Product #<?= htmlspecialchars($editInventory['product_id']) ?></h5>    
   <form method="POST" action="inventory.php?action=update" class="row g-3">
     <input type="hidden" name="store_id" value="<?= htmlspecialchars($editInventory['store_id']) ?>">
     <input type="hidden" name="product_id" value="<?= htmlspecialchars($editInventory['product_id']) ?>">
      <div class="col-md-4"><input class="form-control" name="store_id" value="<?= htmlspecialchars($editInventory['store_id']) ?>" required></div>
      <div class="col-md-4"><input class="form-control" name="product_id" value="<?= htmlspecialchars($editInventory['product_id']) ?>" required></div>
      <div class="col-md-4"><input class="form-control" type="number" name="quantity" value="<?= htmlspecialchars($editInventory['quantity']) ?>" required></div>
      <div class="col-12">
        <button class="btn btn-warning">Update</button>
        <a href="inventory.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
<?php endif; ?>

<table class="table table-striped">
<thead>
  <tr>
    <th>Store</th>
    <th>Product</th>
    <th>Qty</th>
    <?php if (in_array($role, ['admin', 'employee'])): ?>
      <th>Action</th>
    <?php endif; ?>
  </tr>
</thead>
<tbody>
<?php foreach($inventory as $row): ?>
<tr>
  <td><?= htmlspecialchars($row['store']) ?></td>
  <td><?= htmlspecialchars($row['product']) ?></td>
  <td><?= $row['quantity'] ?></td>
<?php if (in_array($role, ['admin', 'employee'])): ?>
    <td>
     <a href="inventory.php?action=edit&store_id=<?= $row['store_id'] ?>&product_id=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
     <a href="inventory.php?action=delete&store_id=<?= $row['store_id'] ?>&product_id=<?= $row['product_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove item?');">Delete</a>
    </td>
  <?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div> <!-- container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>