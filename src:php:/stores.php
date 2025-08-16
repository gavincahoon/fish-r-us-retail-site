<?php
$requiredRoles = ['admin'];
include 'checksession.php';
include 'db.php';
include 'navbar.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $isOnline = isset($_POST['is_online']) ? 1 : 0;
  $pdo->prepare("INSERT INTO stores(name, address, is_online) VALUES(?,?,?)")
      ->execute([$_POST['name'], $_POST['address'], $isOnline]);
  header('Location: stores.php?success=Added');
  exit;
}

if ($action === 'delete') {
  $pdo->prepare("DELETE FROM stores WHERE id=?")->execute([$_GET['id']]);
  header('Location: stores.php?success=Deleted');
  exit;
}

// FETCH store for editing
if ($action === 'edit' && isset($_GET['id'])) {
  $stmt = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
  $stmt->execute([$_GET['id']]);
  $editStore = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE store
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $isOnline = isset($_POST['is_online']) ? 1 : 0;
  $pdo->prepare("UPDATE stores SET name=?, address=?, is_online=? WHERE id=?")
      ->execute([
        $_POST['name'],
        $_POST['address'],
        $isOnline,
        $_POST['id']
      ]);
  header('Location: stores.php?success=Updated');
  exit;
}

$online = isset($_POST['online']) ? 1 : 0;

$stores = $pdo->query("SELECT * FROM stores")->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Add HTML output here similar to vendors.php -->
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Stores</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="mb-4">Stores</h1>
<?php if(!empty($_GET['success'])): ?><div class="alert alert-success"><?= $_GET['success'] ?></div><?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#addStore">Add Store</button>
<div class="collapse" id="addStore">
  <div class="card card-body mb-3">
    <form method="POST" action="stores.php?action=create" class="row g-3">
      <div class="col-md-6"><input class="form-control" name="name" placeholder="Store Name" required></div>
      <div class="col-md-6"><input class="form-control" name="address" placeholder="Address" required></div>
      <div class="col-12 form-check ms-2">
      </div>
      <div class="col-12"><button class="btn btn-success">Save</button></div>
    </form>
  </div>
</div>
<?php if (isset($editStore)): ?>
  <div class="card card-body mb-3 border-warning">
    <h5 class="text-warning">Editing Store #<?= htmlspecialchars($editStore['id']) ?></h5>
    <form method="POST" action="stores.php?action=update">
      <input type="hidden" name="id" value="<?= htmlspecialchars($editStore['id']) ?>">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input class="form-control" name="name" value="<?= htmlspecialchars($editStore['name']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input class="form-control" name="address" value="<?= htmlspecialchars($editStore['address']) ?>" required>
      </div>
      <div class="form-check mb-3">
        <input type="checkbox" name="is_online" value="1" <?php if ($editStore['is_online']) echo 'checked'; ?>>
        <label class="form-check-label" for="onlineCheck">Online Store</label>
      </div>
      <button class="btn btn-warning">Update</button>
      <a href="stores.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
<?php endif; ?>

<table class="table table-bordered">
<thead><tr><th>ID</th><th>Name</th><th>Address</th><th>Online?</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($stores as $s): ?>
<tr>
<td><?= $s['id'] ?></td>
<td><?= htmlspecialchars($s['name']) ?></td>
<td><?= htmlspecialchars($s['address']) ?></td>
<td><?= $s['is_online']?'Yes':'No' ?></td>
<td>
  <a href="stores.php?action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
  <a href="stores.php?action=delete&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete store?');">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</tbody></table>
</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>