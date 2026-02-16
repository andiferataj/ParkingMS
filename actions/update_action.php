<?php
include("../includes/db.php");

$id = $_POST['id'];
$status = $_POST['status'];

$conn->query("UPDATE parking_spots SET status='$status' WHERE id=$id");

header("Location: ../admin/dashboard.php");
exit();
?>
