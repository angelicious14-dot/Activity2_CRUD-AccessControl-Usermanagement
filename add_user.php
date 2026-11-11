<?php
require_once "auth.php";
require_admin();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "user";
    if ($fullname === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($username) < 3 || strlen($password) < 8 || !in_array($role, ["admin", "user"], true)) {
        $error = "Please check all fields. Password must be at least 8 characters.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
        $check->execute([$email, $username]);
        if ($check->fetch()) {
            $error = "Email or username is already in use.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (fullname,email,username,password,role) VALUES (?,?,?,?,?)");
            $stmt->execute([$fullname,$email,$username,password_hash($password,PASSWORD_DEFAULT),$role]);
            header("Location: admin.php?msg=" . urlencode("User account created successfully.")); exit;
        }
    }
}
page_header("Add User");
?>
<main class="form-page"><a class="back-link" href="admin.php">← Back to dashboard</a><section class="content-card form-card"><span class="eyebrow">USER MANAGEMENT</span><h1>Add New User</h1><p class="muted">Create an account and assign its role.</p>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="POST" class="stack-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Full Name</label><input name="fullname" maxlength="100" required>
<label>Email</label><input type="email" name="email" maxlength="150" required>
<label>Username</label><input name="username" minlength="3" maxlength="50" required>
<label>Temporary Password</label><input type="password" name="password" minlength="8" required>
<label>Role</label><select name="role"><option value="user">User</option><option value="admin">Admin</option></select>
<button class="btn primary full" type="submit">Create User</button></form></section></main>
<?php page_footer(); ?>
