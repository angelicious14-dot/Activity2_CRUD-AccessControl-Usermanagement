<?php
require_once "auth.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php"); exit;
}
verify_csrf();

$fullname = trim($_POST["fullname"] ?? "");
$email = trim($_POST["email"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirm = $_POST["confirm_password"] ?? "";

if ($fullname === "" || $email === "" || $username === "" || $password === "" || $confirm === "") {
    header("Location: index.php?error=" . urlencode("Please complete all registration fields.") . "#registerPanel"); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?error=" . urlencode("Please enter a valid email address.") . "#registerPanel"); exit;
}
if (strlen($username) < 3 || strlen($password) < 8) {
    header("Location: index.php?error=" . urlencode("Username must be at least 3 characters and password at least 8 characters.") . "#registerPanel"); exit;
}
if ($password !== $confirm) {
    header("Location: index.php?error=" . urlencode("Passwords do not match.") . "#registerPanel"); exit;
}

$check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
$check->execute([$email, $username]);
if ($check->fetch()) {
    header("Location: index.php?error=" . urlencode("Email or username is already registered.") . "#registerPanel"); exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, 'user')");
$stmt->execute([$fullname, $email, $username, $hash]);

header("Location: index.php?success=" . urlencode("Registration successful! You can now sign in."));
exit;
