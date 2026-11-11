<?php
require_once "auth.php";
if (is_logged_in()) {
    header("Location: " . (($_SESSION["role"] ?? "user") === "admin" ? "admin.php" : "dashboard.php"));
    exit;
}
$error = $_GET["error"] ?? "";
$success = $_GET["success"] ?? "";
page_header("Sign In");
?>
<main class="auth-wrap">
    <section class="auth-intro">
        <span class="eyebrow">ACTIVITY 2 · FINAL TERM</span>
        <h1>Manage your accounts<br><span>with confidence.</span></h1>
        <p>A simple and secure account portal with role-based access and user management.</p>
        <div class="feature-pills"><span>✓ Secure sign in</span><span>✓ User access control</span><span>✓ Admin tools</span></div>
        <div class="decor-card"><div class="decor-icon">✦</div><strong>One place for your accounts</strong><small>Sign in to continue to your workspace.</small></div>
    </section>
    <section class="auth-card">
        <div class="auth-tabs">
            <button type="button" class="tab active" data-panel="loginPanel">Sign In</button>
            <button type="button" class="tab" data-panel="registerPanel">Create Account</button>
        </div>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>

        <div id="loginPanel" class="form-panel">
            <h2>Welcome back</h2><p class="muted">Enter your account details to continue.</p>
            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <label>Username or Email</label>
                <input type="text" name="identity" placeholder="Enter username or email" required autocomplete="username">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required autocomplete="current-password">
                <button class="btn primary full" type="submit">Sign In <span>→</span></button>
            </form>
            <p class="switch-note">New here? <button type="button" class="text-button" data-show="registerPanel">Create an account</button></p>
        </div>

        <div id="registerPanel" class="form-panel hidden">
            <h2>Create account</h2><p class="muted">Register as a regular user.</p>
            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <label>Full Name</label><input type="text" name="fullname" placeholder="Your full name" maxlength="100" required>
                <label>Email</label><input type="email" name="email" placeholder="name@email.com" maxlength="150" required>
                <label>Username</label><input type="text" name="username" placeholder="Choose a username" minlength="3" maxlength="50" required>
                <label>Password</label><input type="password" name="password" placeholder="At least 8 characters" minlength="8" required>
                <label>Confirm Password</label><input type="password" name="confirm_password" placeholder="Repeat your password" minlength="8" required>
                <button class="btn primary full" type="submit">Create Account <span>→</span></button>
            </form>
            <p class="switch-note">Already registered? <button type="button" class="text-button" data-show="loginPanel">Sign in</button></p>
        </div>
    </section>
</main>
<?php page_footer(); ?>
