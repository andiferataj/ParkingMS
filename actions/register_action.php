<?php
include('../includes/db.php');
session_start();
<?php
include('../includes/db.php');
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    header('Location: ../public/register.php?error=1');
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$asAdmin = !empty($_POST['as_admin']);

if ($asAdmin) {
    // determine how many admins exist
    $cntRes = $conn->query('SELECT COUNT(*) as c FROM admin_users');
    $cntRow = $cntRes ? $cntRes->fetch_assoc() : null;
    $adminCount = $cntRow ? intval($cntRow['c']) : 0;

    // if admins exist, only allow creation by logged-in superadmin
    if ($adminCount > 0) {
        session_start();
        if (empty($_SESSION['admin_id']) || empty($_SESSION['is_super'])) {
            header('Location: ../public/register.php?error=forbidden');
            exit();
        }
    }

    // check admin_users table for duplicate
    $check = $conn->prepare('SELECT id FROM admin_users WHERE username = ?');
    $check->bind_param('s', $username);
    $check->execute();
    $res = $check->get_result();
    if ($res && $res->fetch_assoc()) {
        header('Location: ../public/register.php?error=exists');
        exit();
    }

    try {
        // first admin becomes super
        $is_super = ($adminCount == 0) ? 1 : 0;
        $stmt = $conn->prepare('INSERT INTO admin_users (username, password, is_super) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $username, $hash, $is_super);
        if ($stmt->execute()) {
            $_SESSION['admin_id'] = $conn->insert_id;
            $_SESSION['is_super'] = ($is_super == 1);
            $_SESSION['username'] = $username;
            header('Location: ../admin/dashboard.php');
            exit();
        }
    } catch (mysqli_sql_exception $ex) {
        // fallthrough
    }
} else {
    // check users table for duplicate
    $check = $conn->prepare('SELECT id FROM users WHERE username = ?');
    $check->bind_param('s', $username);
    $check->execute();
    $res = $check->get_result();
    if ($res && $res->fetch_assoc()) {
        header('Location: ../public/register.php?error=exists');
        exit();
    }

    try {
        $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            header('Location: ../public/index.php');
            exit();
        }
    } catch (mysqli_sql_exception $ex) {
        // fallthrough
    }
}

header('Location: ../public/register.php?error=2');
exit();
?>
