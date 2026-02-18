<?php
include("../includes/db.php");
include_once __DIR__ . '/../includes/auth.php';
session_start();
require_admin();

$stmt = $conn->prepare(
        "SELECT p.id, p.spot_number, p.status, p.last_updated, z.zone_name, p.current_user_id
         FROM parking_spots p
         JOIN zones z ON z.id = p.zone_id"
);
$stmt->execute();
$res = $stmt->get_result();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Prishtina Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../public/index.php">Prishtina Parking</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php?logout=1">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Zone</th>
                    <th>Spot</th>
                    <th>Status</th>
                    <th>Owner (user_id)</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
<?php
while ($spot = $res->fetch_assoc()) {
        $owner = $spot['current_user_id'] ? $spot['current_user_id'] : '-';
        echo "<tr>";
        echo "<td>" . htmlspecialchars($spot['zone_name']) . "</td>";
        echo "<td>" . htmlspecialchars($spot['spot_number']) . "</td>";
        echo "<td>" . htmlspecialchars($spot['status']) . "</td>";
        echo "<td>" . htmlspecialchars($owner) . "</td>";
        echo "<td>" . htmlspecialchars($spot['last_updated']) . "</td>";
        echo "<td>\n<form action='../actions/update_action.php' method='POST' class='d-flex'>\n<input type='hidden' name='id' value='" . $spot['id'] . "'>\n<select name='status' class='form-select form-select-sm me-2' style='width:120px;'>\n<option value='free'>Free</option>\n<option value='occupied'>Occupied</option>\n</select>\n<button class='btn btn-sm btn-primary'>Update</button>\n</form>\n</td>";
        echo "</tr>";
}
?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
