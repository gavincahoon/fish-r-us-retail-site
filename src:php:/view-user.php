<?php
/* ----------  ACCESS & SET-UP  ---------- */
$requiredRoles = ['admin'];          // only admins can manage users
include 'checksession.php';         // starts session + role check
include 'db.php';                   // provides $pdo
include 'navbar.php';               // top nav

$action = $_GET['action'] ?? 'list';

/* ----------  CREATE  ---------- */
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role']     ?? 'customer';

    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, password, role) VALUES (?,?,?)"
            );
            $stmt->execute([$username, $hash, $role]);
            header('Location: view-user.php?success=Added');
            exit;
        } catch (PDOException $e) {
            $msg = 'Username already exists.';
        }
    } else {
        $msg = 'Username & password required.';
    }
}

/* ----------  DELETE  ---------- */
if ($action === 'delete' && isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM orders WHERE customer_id = ?")->execute([$_GET['id']]);
	$pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['id']]);
    header('Location: view-user.php?success=Deleted');
    exit;
}

/* ----------  FETCH FOR EDIT  ---------- */
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ----------  UPDATE  ---------- */
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'];
    $username = trim($_POST['username']);
    $role     = $_POST['role'];
    $setPwd   = !empty($_POST['password']);

    $sql  = "UPDATE users SET username=?, role=?";
    $args = [$username, $role];

    if ($setPwd) {                     // only change password if provided
        $sql .= ", password=?";
        $args[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    $sql .= " WHERE id=?";
    $args[] = $id;

    $pdo->prepare($sql)->execute($args);
    header('Location: view-user.php?success=Updated');
    exit;
}

/* ----------  LOAD USER LIST  ---------- */
$users = $pdo->query("SELECT id, username, role FROM users WHERE is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
function h($s){ return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
<h1 class="mb-4">Users</h1>

<?php if (!empty($_GET['success'])): ?>
  <div class="alert alert-success"><?= h($_GET['success']) ?></div>
<?php elseif (!empty($msg)): ?>
  <div class="alert alert-danger"><?= h($msg) ?></div>
<?php endif; ?>

<!-- ADD USER (collapse) -->
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#addUser">Add User</button>
<div class="collapse" id="addUser">
  <div class="card card-body mb-3">
    <form method="POST" action="view-user.php?action=create" class="row g-3">
      <div class="col-md-4">
        <input class="form-control" name="username" placeholder="Username" required>
      </div>
      <div class="col-md-4">
        <input class="form-control" type="password" name="password" placeholder="Password" required>
      </div>
      <div class="col-md-4">
        <select name="role" class="form-select">
          <option value="customer">Customer</option>
          <option value="employee">Employee</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-success">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT USER -->
<?php if (isset($editUser)): ?>
<div class="card card-body mb-3 border-warning">
  <h5 class="text-warning">Editing User #<?= $editUser['id'] ?></h5>
  <form method="POST" action="view-user.php?action=update" class="row g-3">
    <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
    <div class="col-md-4">
      <input class="form-control" name="username" value="<?= h($editUser['username']) ?>" required>
    </div>
    <div class="col-md-4">
      <input class="form-control" type="password" name="password" placeholder="New password (leave blank to keep)">
    </div>
    <div class="col-md-4">
      <select name="role" class="form-select">
        <?php foreach (['customer','employee','admin'] as $r): ?>
          <option value="<?= $r ?>" <?= $editUser['role']===$r?'selected':'' ?>><?= ucfirst($r) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-warning">Update</button>
      <a href="view-user.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- USER TABLE -->
<table class="table table-hover">
  <thead class="table-light">
    <tr><th>ID</th><th>Username</th><th>Role</th><th>Action</th></tr>
  </thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= h($u['username']) ?></td>
      <td><?= ucfirst($u['role']) ?></td>
      <td>
        <a href="view-user.php?action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
        <a href="view-user.php?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Delete user?');">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>