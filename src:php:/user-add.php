<?php

require_once 'sanitize.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php'; // Assumes this defines $pdo, not $conn

    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        try {
            $stmt->execute([$username, $hash, $role]);
            header('Location: home.php');
            exit();
        } catch (PDOException $e) {
         $message = 'Error: ' . $e->getMessage(); // Show actual SQL error
            // Optionally: $message .= ' ' . $e->getMessage();
        }
    } else {
        $message = 'Username and password are required.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Add User</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post" action="user-add.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password (plaintext)</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
         <select id="role" name="role" class="form-select">
         <option value="customer">Customer</option>
         <option value="employee">Employee</option>
         <option value="admin">Admin</option>
         </select>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
        <a href="login-form.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>