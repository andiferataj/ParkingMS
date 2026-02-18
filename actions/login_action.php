<?php
include("../includes/db.php");
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT id,password FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $hash = $row['password'];
    if (password_verify($password, $hash) || $password === $hash) {
        $_SESSION['admin_id'] = $row['id'];
        header('Location: ../admin/dashboard.php');
        exit();
    }
}

header('Location: ../admin/login.php?error=1');
exit();
?>
