<?php
// Force-add current_user_id column and show parking_spots structure.
// Run once via browser or CLI, then delete the file.
include __DIR__ . '/includes/db.php';

header('Content-Type: text/plain');
echo "Connected DB: ";
$res = $conn->query('SELECT DATABASE() as db');
if ($res) { $r = $res->fetch_assoc(); echo ($r['db'] ?? 'unknown') . "\n\n"; }

echo "Checking existing columns...\n";
$res = $conn->query("SHOW COLUMNS FROM parking_spots LIKE 'current_user_id'");
if ($res && $res->num_rows > 0) {
    echo "Column current_user_id already exists.\n\n";
} else {
    echo "Column missing — attempting to add...\n";
    $errs = [];
    $sqls = [
        "ALTER TABLE parking_spots ADD COLUMN current_user_id INT DEFAULT NULL",
        "ALTER TABLE parking_spots ADD COLUMN IF NOT EXISTS current_user_id INT DEFAULT NULL"
    ];
    foreach ($sqls as $sql) {
        if ($conn->query($sql) === TRUE) {
            echo "Success: $sql\n";
            break;
        } else {
            $errs[] = "Failed: $sql -> (" . $conn->errno . ") " . $conn->error;
        }
    }
    if (!empty($errs)) {
        echo "All attempts failed:\n" . implode("\n", $errs) . "\n";
        echo "You may need to run the ALTER manually in phpMyAdmin.\n";
    }
}

echo "\nFinal parking_spots structure:\n";
$res = $conn->query('DESCRIBE parking_spots');
if ($res) {
    while ($col = $res->fetch_assoc()) {
        echo $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . ($col['Key'] ?: '-') . "\n";
    }
} else {
    echo "DESCRIBE failed: " . $conn->error . "\n";
}

echo "\nDone.\n";
?>
