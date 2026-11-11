<?php
if (session_status() === PHP_SESSION_NONE) {
    // Basic session hardening.
    ini_set("session.use_strict_mode", "1");
    session_start();
}

require_once __DIR__ . "/database.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: index.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if (($_SESSION["role"] ?? "user") !== "admin") {
        http_response_code(403);
        exit("403 Forbidden: Admin access only.");
    }
}

function csrf_token() {
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verify_csrf() {
    $token = $_POST["csrf_token"] ?? "";
    if (!is_string($token) || !hash_equals($_SESSION["csrf_token"] ?? "", $token)) {
        http_response_code(419);
        exit("Invalid or expired form token. Please go back and try again.");
    }
}

function page_header($title) {
    $name = $_SESSION["fullname"] ?? "Guest";
    $role = ucfirst($_SESSION["role"] ?? "user");
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($title) . ' | Activity 2</title>';
    echo '<link rel="stylesheet" href="style.css"></head><body>';
    echo '<header class="topbar"><a class="brand" href="' . (is_logged_in() ? (($_SESSION["role"] ?? "") === "admin" ? "admin.php" : "dashboard.php") : "index.php") . '"><span class="brand-mark">A2</span><span>Account<span class="brand-light">Hub</span></span></a>';
    if (is_logged_in()) {
        echo '<div class="top-user"><span class="user-dot"></span><span>' . e($name) . '</span><span class="role-chip">' . e($role) . '</span></div>';
    }
    echo '</header>';
}

function page_footer() {
    echo '<footer class="footer">Activity #2 · User Management System</footer>';
    echo '<script src="script.js"></script></body></html>';
}
