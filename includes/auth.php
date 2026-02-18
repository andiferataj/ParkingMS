<?php
session_start();
include_once __DIR__ . '/db.php';

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) {
        header('Location: ../admin/login.php');
        exit();
    }
}

function is_user() {
    return !empty($_SESSION['user_id']);
}

function require_user() {
    if (!is_user()) {
        header('Location: ../public/login.php');
        exit();
    }
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function current_admin_id() {
    return $_SESSION['admin_id'] ?? null;
}

function current_username() {
    return $_SESSION['username'] ?? null;
}

?>
