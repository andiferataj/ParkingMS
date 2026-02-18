<?php
include_once __DIR__ . '/../includes/db.php';
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

<h2>User Login</h2>
<?php if (!empty($_GET['error'])) echo '<p style="color:red">Login failed</p>'; ?>
<form action="../actions/user_login_action.php" method="POST">
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit">Login</button>
</form>

<p><a href="register.php">Register</a></p>
