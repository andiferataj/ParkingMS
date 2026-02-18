<?php
include("../includes/db.php");
session_start();

// basic admin protection (optional)
// admin check is handled in dashboard but double-checking here is harmless
// include_once __DIR__ . '/../includes/auth.php';

$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
if (!in_array($status, ['free', 'occupied'])) {
	header('Location: ../admin/dashboard.php?error=invalid');
	exit();
}

$stmt = $conn->prepare('UPDATE parking_spots SET status = ? WHERE id = ?');
$stmt->bind_param('si', $status, $id);
$stmt->execute();

header('Location: ../admin/dashboard.php');
exit();
?>
	