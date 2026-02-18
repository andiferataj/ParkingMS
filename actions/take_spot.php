<?php
include('../includes/db.php');
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

$spot_id = intval($_POST['spot_id'] ?? 0);
$user_id = $_SESSION['user_id'];

// ensure spot exists and is free
$stmt = $conn->prepare('SELECT status FROM parking_spots WHERE id = ?');
$stmt->bind_param('i', $spot_id);
$stmt->execute();
$res = $stmt->get_result();
if (!($row = $res->fetch_assoc())) {
    header('Location: ../public/index.php');
    exit();
}
if ($row['status'] !== 'free') {
    header('Location: ../public/index.php?error=occupied');
    exit();
}

// mark occupied
try {
    $stmt = $conn->prepare("UPDATE parking_spots SET status='occupied', current_user_id = ? WHERE id = ?");
    $stmt->bind_param('ii', $user_id, $spot_id);
    $stmt->execute();
} catch (mysqli_sql_exception $ex) {
    // If the column is missing or other DB error, fail gracefully and instruct user/admin
    error_log('take_spot error: ' . $ex->getMessage());
    header('Location: ../public/index.php?error=db');
    exit();
}

// insert reservation
$stmt = $conn->prepare('INSERT INTO reservations (user_id, spot_id) VALUES (?, ?)');
$stmt->bind_param('ii', $user_id, $spot_id);
$stmt->execute();

header('Location: ../public/index.php');
exit();
?>
