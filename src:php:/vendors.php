<?php
$requiredRoles = ['admin'];

include 'checksession.php';
include 'db.php';
include 'navbar.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->prepare("INSERT INTO vendors(name,contact_info) VALUES(?,?)")
      ->execute([$_POST['name'], $_POST['contact_info']]);
  header('Location: vendors.php?success=Added');
  exit;
}

if ($action === 'delete') {
  $pdo->prepare("DELETE FROM vendors WHERE id=?")->execute([$_GET['id']]);
  header('Location: vendors.php?success=Deleted');
  exit;
}

// FETCH vendor for edit
if ($action === 'edit' && isset($_GET['id'])) {
  $stmt = $pdo->prepare("SELECT * FROM vendors WHERE id = ?");
  $stmt->execute([$_GET['id']]);
  $editVendor = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE vendor
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->prepare("UPDATE vendors SET name=?, contact_info=? WHERE id=?")
      ->execute([$_POST['name'], $_POST['contact_info'], $_POST['id']]);
  header('Location: vendors.php?success=Updated');
  exit;
  
}

$vendors = $pdo->query("SELECT * FROM vendors")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Vendors</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="mb-4">Vendors</h1>
<?php if(!empty($_GET['success'])): ?><div class="alert alert-success"><?= $_GET['success'] ?></div><?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#addVendor">Add Vendor</button>
<div class="collapse" id="addVendor">
  <div class="card card-body mb-3">
    <form method="POST" action="vendors.php?action=create">
      <div class="mb-3"><label class="form-label">Name</label>
        <input class="form-control" name="name" required></div>
      <div class="mb-3"><label class="form-label">Contact Info</label>
        <textarea class="form-control" name="contact_info"></textarea></div>
      <button class="btn btn-success">Save</button>
    </form>
  </div>
</div>
<?php if (isset($editVendor)): ?>
  <div class="card card-body mb-3 border-warning">
    <h5 class="text-warning">Editing Vendor #<?= htmlspecialchars($editVendor['id']) ?></h5>
    <form method="POST" action="vendors.php?action=update">
      <input type="hidden" name="id" value="<?= htmlspecialchars($editVendor['id']) ?>">
      <div class="mb-3"><label class="form-label">Name</label>
        <input class="form-control" name="name" value="<?= htmlspecialchars($editVendor['name']) ?>" required></div>
      <div class="mb-3"><label class="form-label">Contact Info</label>
        <textarea class="form-control" name="contact_info"><?= htmlspecialchars($editVendor['contact_info']) ?></textarea></div>
      <button class="btn btn-warning">Update</button>
      <a href="vendors.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
<?php endif; ?>

<table class="table table-hover">
<thead><tr><th>ID</th><th>Name</th><th>Contact Info</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($vendors as $v): ?>
<tr>
<td><?= $v['id'] ?></td>
<td><?= htmlspecialchars($v['name']) ?></td>
<td><?= nl2br(htmlspecialchars($v['contact_info'])) ?></td>
<td>
  <a href="vendors.php?action=edit&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
  <a href="vendors.php?action=delete&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete vendor?');">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</tbody></table>
</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>