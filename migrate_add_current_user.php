<?php
// Run this once to add the `current_user_id` column if it's missing.
// After success, remove this file for security.
include __DIR__ . '/includes/db.php';

// Check if column exists
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

// Optionally add foreign key if users table exists
$res = $conn->query("SHOW TABLES LIKE 'users'");
if ($res && $res->num_rows > 0) {
    // check for existing FK with a pragmatic attempt — skip if fails
    $fk_sql = "ALTER TABLE parking_spots ADD INDEX idx_current_user_id (current_user_id)";
    if ($conn->query($fk_sql) === TRUE) {
        echo "Added index on current_user_id.\n";
    }
}

echo "Migration complete. You can now delete migrate_add_current_user.php.\n";

?>
