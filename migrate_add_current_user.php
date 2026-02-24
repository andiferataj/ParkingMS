<?php

include __DIR__ . '/includes/db.php';


$res = $conn->query("SHOW COLUMNS FROM parking_spots LIKE 'current_user_id'");
if ($res && $res->num_rows > 0) {
    echo "Column current_user_id already exists.\n";
    exit;
}

$sql = "ALTER TABLE parking_spots ADD COLUMN current_user_id INT DEFAULT NULL";
if ($conn->query($sql) === TRUE) {
    echo "Added column current_user_id to parking_spots.\n";
} else {
    echo "Error adding column: " . htmlspecialchars($conn->error) . "\n";
    exit;
}


$res = $conn->query("SHOW TABLES LIKE 'users'");
if ($res && $res->num_rows > 0) {

    $fk_sql = "ALTER TABLE parking_spots ADD INDEX idx_current_user_id (current_user_id)";
    if ($conn->query($fk_sql) === TRUE) {
        echo "Added index on current_user_id.\n";
    }
}

echo "Migration complete. You can now delete migrate_add_current_user.php.\n";

?>
