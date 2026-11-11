<?php
require_once "auth.php";
require_admin();
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) { header("Location: admin.php"); exit; }
$stmt = $pdo->prepare("SELECT id, fullname, email, username, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) { header("Location: admin.php?msg=" . urlencode("User not found.")); exit; }
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $role = $_POST["role"] ?? "user";
    $password = $_POST["password"] ?? "";
    if ($fullname === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($username) < 3 || !in_array($role, ["admin","user"], true)) {
        $error = "Please enter valid account details.";
    } elseif ((int)$id === (int)$_SESSION["user_id"] && $role !== "admin") {
        $error = "You cannot remove your own admin role.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE (email = ? OR username = ?) AND id <> ? LIMIT 1");
        $check->execute([$email,$username,$id]);
        if ($check->fetch()) {
            $error = "Email or username is already used by another account.";
        } else {
            if ($password !== "" && strlen($password) < 8) {
                $error = "New password must be at least 8 characters.";
            } else {
                if ($password !== "") {
                    $update = $pdo->prepare("UPDATE users SET fullname=?, email=?, username=?, role=?, password=? WHERE id=?");
                    $update->execute([$fullname,$email,$username,$role,password_hash($password,PASSWORD_DEFAULT),$id]);
                } else {
                    $update = $pdo->prepare("UPDATE users SET fullname=?, email=?, username=?, role=? WHERE id=?");
                    $update->execute([$fullname,$email,$username,$role,$id]);
                }
                if ((int)$id === (int)$_SESSION["user_id"]) {
                    $_SESSION["fullname"] = $fullname;
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $role;
                }
                header("Location: admin.php?msg=" . urlencode("User information updated.")); exit;
            }
        }
    }
    $user = array_merge($user, ["fullname"=>$fullname,"email"=>$email,"username"=>$username,"role"=>$role]);
}
page_header("Edit User");
?>
<main class="form-page"><a class="back-link" href="admin.php">← Back to dashboard</a><section class="content-card form-card"><span class="eyebrow">USER MANAGEMENT</span><h1>Edit User</h1><p class="muted">Update account details. Leave password blank to keep the current password.</p>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="POST" class="stack-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Full Name</label><input name="fullname" maxlength="100" value="<?= e($user["fullname"]) ?>" required>
<label>Email</label><input type="email" name="email" maxlength="150" value="<?= e($user["email"]) ?>" required>
<label>Username</label><input name="username" minlength="3" maxlength="50" value="<?= e($user["username"]) ?>" required>
<label>New Password (optional)</label><input type="password" name="password" minlength="8" placeholder="Leave blank to keep current password">
<label>Role</label><select name="role"><option value="user" <?= $user["role"]==="user"?"selected":"" ?>>User</option><option value="admin" <?= $user["role"]==="admin"?"selected":"" ?>>Admin</option></select>
<button class="btn primary full" type="submit">Save Changes</button></form></section></main>
<?php page_footer(); ?>
