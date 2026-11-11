<?php
require_once "auth.php";
require_admin();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin.php"); exit;
}
verify_csrf();
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id || (int)$id === (int)$_SESSION["user_id"]) {
    header("Location: admin.php?msg=" . urlencode("Invalid action. You cannot delete your own account.")); exit;
}
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
header("Location: admin.php?msg=" . urlencode($stmt->rowCount() ? "User deleted successfully." : "User not found."));
exit;
