<?php
$conn = new mysqli("localhost", "root", "", "ParkingMS");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
