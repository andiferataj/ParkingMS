<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php include("../includes/db.php"); ?>
<?php include_once("../includes/auth.php"); ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Prishtina Parking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body{
background:
linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
url("prishtina.png");
background-size:cover;
background-position:center;
background-attachment:fixed;
min-height:100vh;
color:white;
}

.main-container{
background:rgba(255,255,255,0.95);
border-radius:12px;
padding:30px;
color:#222;
}


.zone-title{
margin-top:40px;
margin-bottom:15px;
font-weight:600;
border-left:5px solid #0d6efd;
padding-left:10px;
}

.spot-card{
border-radius:12px;
border:none;
padding:10px;
transition:0.2s;
position:relative;
}

.spot-card:hover{
transform:translateY(-4px);
box-shadow:0 10px 20px rgba(0,0,0,0.15);
}

.status-dot{
width:14px;
height:14px;
border-radius:50%;
display:inline-block;
margin-right:8px;
}

.status-free{
background:#22c55e;
}

.status-occupied{
background:#ef4444;
}

.btn-take{
background:#0d6efd;
border:none;
}

.btn-release{
border:1px solid #0d6efd;
color:#0d6efd;
}

.btn-release:hover{
background:#0d6efd;
color:white;
}

.status-text{
font-weight:600;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
<i class="bi bi-car-front-fill"></i> Prishtina Parking
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarsExample07">

<ul class="navbar-nav ms-auto">

<?php if (is_user()): ?>

<li class="nav-item">
<a class="nav-link">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
</li>

<li class="nav-item">
<a class="nav-link" href="login.php?logout=1">Logout</a>
</li>

<?php else: ?>

<li class="nav-item">
<a class="nav-link" href="login.php">Login</a>
</li>

<li class="nav-item">
<a class="nav-link" href="register.php">Register</a>
</li>

<?php endif; ?>

<li class="nav-item">
<a class="nav-link" href="../admin/login.php">Admin</a>
</li>

</ul>

</div>
</div>
</nav>

<main class="container my-5">

<div class="main-container">

<div class="mb-4">
<h1 class="fw-bold">Parking Availability</h1>
<p class="text-muted">Real-time parking spots in Prishtina</p>
</div>

<?php

$zones = $conn->query("SELECT * FROM zones");

while ($zone = $zones->fetch_assoc()) {

echo "<h3 class='zone-title'>" . htmlspecialchars($zone['zone_name']) . "</h3>";

$zone_id = $zone['id'];

$stmt = $conn->prepare("SELECT * FROM parking_spots WHERE zone_id = ?");
$stmt->bind_param('i', $zone_id);
$stmt->execute();

$spots = $stmt->get_result();

echo "<div class='row g-3'>";

while ($spot = $spots->fetch_assoc()) {

$status = $spot['status'];

$isFree = ($status === 'free');

$canTake = $isFree && is_user();

$canRelease = (!$isFree && is_user() && isset($spot['current_user_id']) && $spot['current_user_id'] == current_user_id());

echo "<div class='col-6 col-sm-4 col-md-3 col-lg-2'>";

echo "<div class='card spot-card'>";

echo "<div class='card-body text-center'>";

echo "<h5 class='card-title'><i class='bi bi-car-front'></i> Spot " . htmlspecialchars($spot['spot_number']) . "</h5>";

if ($isFree) {
echo "<p class='status-text'><span class='status-dot status-free'></span>Free</p>";
} else {
echo "<p class='status-text'><span class='status-dot status-occupied'></span>Occupied</p>";
}

if ($canTake) {

echo "<form method='POST' action='../actions/take_spot.php'>";

echo "<input type='hidden' name='spot_id' value='" . $spot['id'] . "'>";

echo "<button class='btn btn-take btn-sm'>Take</button>";

echo "</form>";

}

elseif ($canRelease) {

echo "<form method='POST' action='../actions/release_spot.php'>";

echo "<input type='hidden' name='spot_id' value='" . $spot['id'] . "'>";

echo "<button class='btn btn-release btn-sm'>Release</button>";

echo "</form>";

}

echo "</div>";

echo "</div>";

echo "</div>";

}

echo "</div>";

}

?>

</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>