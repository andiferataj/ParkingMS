<?php
// Run this once (via browser or CLI) to create tables from parking.sql
// After successful run, delete this file for security.

include __DIR__ . '/includes/db.php';

$sqlFile = __DIR__ . '/parking.sql';
if (!file_exists($sqlFile)) {
    echo "parking.sql not found at " . htmlspecialchars($sqlFile);
    exit;
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Failed to read parking.sql";
    exit;
}

// Execute multiple queries
if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    if ($conn->errno) {
        echo "Completed with errors: (" . $conn->errno . ") " . htmlspecialchars($conn->error);
    } else {
        echo "Database schema applied successfully.";
    }
} else {
    echo "Failed to run SQL: (" . $conn->errno . ") " . htmlspecialchars($conn->error);
}

echo "\nCreated/checked tables: zones, users, admin_users, parking_spots, reservations.";

?>
