<?php
include('../includes/db.php');
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    header('Location: ../public/register.php?error=1');
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $hash);
if ($stmt->execute()) {
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['username'] = $username;
    header('Location: ../public/index.php');
    exit();
} else {
    header('Location: ../public/register.php?error=2');
    exit();
}
?>
