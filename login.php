<?php
require_once "auth.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php"); exit;
}
verify_csrf();

$identity = trim($_POST["identity"] ?? "");
$password = $_POST["password"] ?? "";
if ($identity === "" || $password === "") {
    header("Location: index.php?error=" . urlencode("Please complete all fields.")); exit;
}

$stmt = $pdo->prepare("SELECT id, fullname, email, username, password, role FROM users WHERE username = :identity OR email = :identity LIMIT 1");
$stmt->execute([":identity" => $identity]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: index.php?error=" . urlencode("Account not found or incorrect credentials.")); exit;
}

// Supports Activity 1 accounts stored with password_hash(), and legacy plain-text
// passwords. A legacy password is upgraded to password_hash() after successful login.
$stored = (string)$user["password"];
$valid = password_verify($password, $stored);
if (!$valid && hash_equals($stored, $password)) {
    $valid = true;
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $upgrade = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $upgrade->execute([$newHash, $user["id"]]);
}
if (!$valid) {
    header("Location: index.php?error=" . urlencode("Account not found or incorrect credentials.")); exit;
}

session_regenerate_id(true);
$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["fullname"] = $user["fullname"];
$_SESSION["username"] = $user["username"];
$_SESSION["role"] = $user["role"] ?: "user";
$_SESSION["csrf_token"] = bin2hex(random_bytes(32));

header("Location: " . ($_SESSION["role"] === "admin" ? "admin.php" : "dashboard.php"));
exit;
