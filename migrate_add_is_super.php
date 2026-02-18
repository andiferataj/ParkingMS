<?php
// Run once to add is_super column to admin_users
include __DIR__ . '/includes/db.php';
echo "Checking admin_users table...\n";
$res = $conn->query("SHOW COLUMNS FROM admin_users LIKE 'is_super'");
if ($res && $res->num_rows > 0) {
    echo "Column is_super already exists.\n";
    exit;
}

$sql = "ALTER TABLE admin_users ADD COLUMN is_super TINYINT(1) DEFAULT 0";
if ($conn->query($sql) === TRUE) {
    echo "Added is_super column.\n";
} else {
    echo "Failed to add is_super: " . $conn->error . "\n";
}

?>
