<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php include("../includes/db.php"); ?>
<?php include_once("../includes/auth.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Prishtina Parking</title>
</head>
<body>

<h1>Prishtina Parking Availability</h1>

<?php if (is_user()): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> — <a href="login.php?logout=1">Logout</a></p>
<?php else: ?>
    <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
<?php endif; ?>

<?php
$zones = $conn->query("SELECT * FROM zones");

while ($zone = $zones->fetch_assoc()) {
    echo "<h2>" . $zone['zone_name'] . "</h2>";
    
    $zone_id = $zone['id'];
    $stmt = $conn->prepare("SELECT * FROM parking_spots WHERE zone_id = ?");
    $stmt->bind_param('i', $zone_id);
    $stmt->execute();
    $spots = $stmt->get_result();

    echo "<div class='zone-box'>";
    while ($spot = $spots->fetch_assoc()) {
        $class = ($spot['status'] == 'free') ? "free" : "occupied";
        echo "<div class='spot $class'>Spot " . $spot['spot_number'] . "<br><small>" . $spot['status'] . "</small>";
        if ($spot['status'] == 'free' && is_user()) {
            echo "<form method='POST' action='../actions/take_spot.php' style='margin-top:6px;'><input type='hidden' name='spot_id' value='" . $spot['id'] . "'><button>Take</button></form>";
        } elseif ($spot['status'] == 'occupied' && is_user() && (isset($spot['current_user_id']) && $spot['current_user_id'] == current_user_id())) {
            echo "<form method='POST' action='../actions/release_spot.php' style='margin-top:6px;'><input type='hidden' name='spot_id' value='" . $spot['id'] . "'><button>Release</button></form>";
        }
        echo "</div>";
    }
    echo "</div>";
}
?>

</body>
</html>
