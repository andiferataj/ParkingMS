<?php

include __DIR__ . '/../includes/db.php';
header('Content-Type: text/plain');
echo "Admin users:\n";
$res = $conn->query('SELECT id, username FROM admin_users');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo $row['id'] . ' - ' . $row['username'] . "\n";
    }
} else {
    echo "Failed to query admin_users: " . $conn->error . "\n";
}
?>
