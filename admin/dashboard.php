<?php
include("../includes/db.php");
session_start();
?>

<h1>Admin Dashboard</h1>

<?php
$spots = $conn->query("
    SELECT parking_spots.*, zones.zone_name 
    FROM parking_spots 
    JOIN zones ON zones.id = parking_spots.zone_id
");

while ($spot = $spots->fetch_assoc()) {
    echo "
    <div>
        <strong>Zone:</strong> {$spot['zone_name']} |
        <strong>Spot:</strong> {$spot['spot_number']} |
        <strong>Status:</strong> {$spot['status']} 
        <form action='../actions/update_action.php' method='POST' style='display:inline;'>
            <input type='hidden' name='id' value='{$spot['id']}'>
            <select name='status'>
                <option value='free'>Free</option>
                <option value='occupied'>Occupied</option>
            </select>
            <button>Update</button>
        </form>
    </div>
    ";
}
?>
