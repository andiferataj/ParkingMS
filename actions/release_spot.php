<?php
include('../includes/db.php');
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

$spot_id = intval($_POST['spot_id'] ?? 0);
$user_id = $_SESSION['user_id'];

// check ownership
$stmt = $conn->prepare('SELECT current_user_id FROM parking_spots WHERE id = ?');
$stmt->bind_param('i', $spot_id);
$stmt->execute();
$res = $stmt->get_result();
if (!($row = $res->fetch_assoc())) {
    header('Location: ../public/index.php');
    exit();
}
if ($row['current_user_id'] != $user_id) {
    header('Location: ../public/index.php?error=notowner');
    exit();
}

// release spot
$stmt = $conn->prepare("UPDATE parking_spots SET status='free', current_user_id = NULL WHERE id = ?");
$stmt->bind_param('i', $spot_id);
$stmt->execute();

// close reservation
$stmt = $conn->prepare('UPDATE reservations SET end_time = NOW() WHERE spot_id = ? AND user_id = ? AND end_time IS NULL');
$stmt->bind_param('ii', $spot_id, $user_id);
$stmt->execute();

header('Location: ../public/index.php');
exit();
?>
