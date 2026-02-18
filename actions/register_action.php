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

// check if username already exists
$check = $conn->prepare('SELECT id FROM users WHERE username = ?');
$check->bind_param('s', $username);
$check->execute();
$res = $check->get_result();
if ($res && $res->fetch_assoc()) {
    header('Location: ../public/register.php?error=exists');
    exit();
}

try {
    $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $hash);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header('Location: ../public/index.php');
        exit();
    }
} catch (mysqli_sql_exception $ex) {
    // handle race condition or duplicate key
}

header('Location: ../public/register.php?error=2');
exit();
?>
