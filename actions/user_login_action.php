<?php
include('../includes/db.php');
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare('SELECT id,password FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        header('Location: ../public/index.php');
        exit();
    }
}

header('Location: ../public/login.php?error=1');
exit();
?>
