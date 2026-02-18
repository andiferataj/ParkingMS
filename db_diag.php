<?php
// Diagnostic script - run in browser to inspect DB tables/columns.
// Remove after use.
include __DIR__ . '/includes/db.php';

header('Content-Type: text/plain');
echo "Current connection info:\n";
$res = $conn->query('SELECT DATABASE() as db');
if ($res) {
    $row = $res->fetch_assoc();
    echo "Database: " . ($row['db'] ?? 'UNKNOWN') . "\n\n";
}

echo "Check tables existence:\n";
$res = $conn->query("SHOW TABLES LIKE 'parking_spots'");
echo "parking_spots: " . ($res && $res->num_rows ? 'FOUND' : 'MISSING') . "\n";
$res = $conn->query("SHOW TABLES LIKE 'users'");
echo "users: " . ($res && $res->num_rows ? 'FOUND' : 'MISSING') . "\n";
$res = $conn->query("SHOW TABLES LIKE 'admin_users'");
echo "admin_users: " . ($res && $res->num_rows ? 'FOUND' : 'MISSING') . "\n\n";

echo "parking_spots columns:\n";
$res = $conn->query("SHOW COLUMNS FROM parking_spots");
if ($res) {
    while ($col = $res->fetch_assoc()) {
        echo $col['Field'] . " | " . $col['Type'] . " | " . $col['Null'] . " | " . ($col['Key'] ?: '-') . "\n";
    }
} else {
    echo "Cannot show columns: " . $conn->error . "\n";
}

echo "\nSample rows from parking_spots (limit 10):\n";
$res = $conn->query('SELECT id,spot_number,zone_id,status,current_user_id FROM parking_spots LIMIT 10');
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo json_encode($r) . "\n";
    }
} else {
    echo "Cannot query parking_spots: " . $conn->error . "\n";
}

echo "\nDone.\n";
?>
