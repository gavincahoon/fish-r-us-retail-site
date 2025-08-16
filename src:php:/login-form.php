<?php
// login-form.php
session_start();
require_once 'db.php';         // Make sure this sets up $pdo
require_once 'sanitize.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Use PDO
    $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Login success
        $_SESSION['user'] = $user['username'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id']; // Optional if you want to track it

        header('Location: home.php');
        exit;
    } else {
        $message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Login</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post" action="login-form.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
      <p class="mt-3 mb-0 text-muted text-center">
      <a href="user-add.php" class="link-primary">← Don't have an account?</a>
    </p>
</div>
</body>
</html>