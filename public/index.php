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
    <style>
        body { background: #f0f4f8; }
        .spot-card.free { border-left: 4px solid #0d6efd; }
        .spot-card.occupied { border-left: 4px solid #6c757d; opacity: 0.9; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">Prishtina Parking</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (is_user()): ?>
            <li class="nav-item"><a class="nav-link" href="#">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="login.php?logout=1">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="../admin/login.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">
  <h1 class="mb-4">Parking Availability</h1>

  <?php
  $zones = $conn->query("SELECT * FROM zones");

  while ($zone = $zones->fetch_assoc()) {
      echo "<h3 class='mt-4'>" . htmlspecialchars($zone['zone_name']) . "</h3>";
      $zone_id = $zone['id'];
      $stmt = $conn->prepare("SELECT * FROM parking_spots WHERE zone_id = ?");
      $stmt->bind_param('i', $zone_id);
      $stmt->execute();
      $spots = $stmt->get_result();

      echo "<div class='row'>";
      while ($spot = $spots->fetch_assoc()) {
          $status = $spot['status'];
          $isFree = ($status === 'free');
          $canTake = $isFree && is_user();
          $canRelease = (!$isFree && is_user() && isset($spot['current_user_id']) && $spot['current_user_id'] == current_user_id());

          echo "<div class='col-6 col-sm-4 col-md-3 mb-3'>";
          echo "<div class='card spot-card " . ($isFree ? 'free' : 'occupied') . "'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>Spot " . htmlspecialchars($spot['spot_number']) . "</h5>";
          echo "<p class='card-text text-muted'>" . htmlspecialchars($status) . "</p>";
          if ($canTake) {
              echo "<form method='POST' action='../actions/take_spot.php'><input type='hidden' name='spot_id' value='" . $spot['id'] . "'><button class='btn btn-primary btn-sm'>Take</button></form>";
          } elseif ($canRelease) {
              echo "<form method='POST' action='../actions/release_spot.php'><input type='hidden' name='spot_id' value='" . $spot['id'] . "'><button class='btn btn-outline-primary btn-sm'>Release</button></form>";
          }
          echo "</div></div></div>";
      }
      echo "</div>";
  }
  ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
