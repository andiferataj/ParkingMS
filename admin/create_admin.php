<?php
// One-click admin creator. Run from browser, then delete this file.
include __DIR__ . '/../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$username || !$password) {
        $error = 'Provide username and password';
    } else {
        // check existing
        $check = $conn->prepare('SELECT id FROM admin_users WHERE username = ?');
        $check->bind_param('s', $username);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->fetch_assoc()) {
            $error = 'Username already exists';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare('INSERT INTO admin_users (username, password) VALUES (?, ?)');
            $ins->bind_param('ss', $username, $hash);
            if ($ins->execute()) {
                $success = 'Admin created. Delete this file for security.';
            } else {
                $error = 'Insert failed: ' . $conn->error;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Admin - Prishtina Parking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Create Admin</h4>
          <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
          <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3"><input class="form-control" name="username" placeholder="admin username" required></div>
            <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="password" required></div>
            <div class="d-grid"><button class="btn btn-primary" type="submit">Create Admin</button></div>
          </form>
          <p class="mt-3 small text-muted">After creating the admin, delete this file: <code>admin/create_admin.php</code></p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
