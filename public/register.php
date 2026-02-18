<?php
include_once __DIR__ . '/../includes/db.php';
session_start();
?>

<h2>User Register</h2>
<?php if (!empty($_GET['error'])) echo '<p style="color:red">Registration failed</p>'; ?>
<form action="../actions/register_action.php" method="POST">
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit">Register</button>
</form>

<p><a href="login.php">Already have an account? Login</a></p>
