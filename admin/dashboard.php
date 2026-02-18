session_start();
<?php
include("../includes/db.php");
include_once __DIR__ . '/../includes/auth.php';
require_admin();

echo "<h1>Admin Dashboard</h1>";

$stmt = $conn->prepare(
    "SELECT p.id, p.spot_number, p.status, p.last_updated, z.zone_name, p.current_user_id
     FROM parking_spots p
     JOIN zones z ON z.id = p.zone_id"
);
$stmt->execute();
$res = $stmt->get_result();

while ($spot = $res->fetch_assoc()) {
    $owner = $spot['current_user_id'] ? " (user_id: {$spot['current_user_id']})" : '';
    echo "<div>
        <strong>Zone:</strong> {$spot['zone_name']} |
        <strong>Spot:</strong> {$spot['spot_number']} |
        <strong>Status:</strong> {$spot['status']}{$owner}
        <form action='../actions/update_action.php' method='POST' style='display:inline;'>
            <input type='hidden' name='id' value='{$spot['id']}'>
            <select name='status'>
                <option value='free'>Free</option>
                <option value='occupied'>Occupied</option>
            </select>
            <button>Update</button>
        </form>
    </div>";
}
?>
