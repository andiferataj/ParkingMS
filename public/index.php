<?php include("../includes/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Prishtina Parking</title>
</head>
<body>

<h1>Prishtina Parking Availability</h1>

<?php
$zones = $conn->query("SELECT * FROM zones");

while ($zone = $zones->fetch_assoc()) {
    echo "<h2>" . $zone['zone_name'] . "</h2>";
    
    $zone_id = $zone['id'];
    $spots = $conn->query("SELECT * FROM parking_spots WHERE zone_id=$zone_id");

    echo "<div class='zone-box'>";
    while ($spot = $spots->fetch_assoc()) {
        $class = ($spot['status'] == 'free') ? "free" : "occupied";

        echo "
        <div class='spot $class'>
            Spot " . $spot['spot_number'] . "<br>
            <small>" . $spot['status'] . "</small>
        </div>
        ";
    }
    echo "</div>";
}
?>

</body>
</html>
