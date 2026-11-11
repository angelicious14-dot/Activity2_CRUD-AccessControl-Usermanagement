<?php
require_once "auth.php";
require_login();
if (($_SESSION["role"] ?? "user") === "admin") {
    header("Location: admin.php"); exit;
}
$stmt = $pdo->prepare("SELECT id, fullname, email, username, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$me = $stmt->fetch();
page_header("My Dashboard");
?>
<div class="app-layout">
    <aside class="sidebar">
        <div class="side-label">WORKSPACE</div>
        <a class="side-link active" href="dashboard.php">▦ <span>My Dashboard</span></a>
        <div class="side-label">ACCOUNT</div>
        <a class="side-link" href="logout.php">↪ <span>Sign Out</span></a>
    </aside>
    <main class="main-content">
        <div class="page-heading"><div><span class="eyebrow">OVERVIEW</span><h1>Hello, <?= e($_SESSION["fullname"]) ?>!</h1><p class="muted">Welcome to your personal dashboard.</p></div><span class="status-badge">● Account active</span></div>
        <section class="stats-grid">
            <article class="stat-card"><span class="stat-icon purple">♙</span><small>ACCOUNT TYPE</small><strong>Regular User</strong><span class="stat-foot">Standard access</span></article>
            <article class="stat-card"><span class="stat-icon green">✓</span><small>ACCESS STATUS</small><strong>Verified session</strong><span class="stat-foot">You are signed in</span></article>
        </section>
        <section class="content-card">
            <div class="section-title"><div><h2>My Account</h2><p class="muted">Your registered account information.</p></div></div>
            <?php if ($me): ?>
            <div class="profile-row"><div class="avatar"><?= e(strtoupper(substr($me["fullname"], 0, 1))) ?></div><div><strong><?= e($me["fullname"]) ?></strong><p class="muted"><?= e($me["email"]) ?></p></div></div>
            <div class="details-grid">
                <div><small>USERNAME</small><strong><?= e($me["username"]) ?></strong></div>
                <div><small>ACCOUNT ROLE</small><strong>User</strong></div>
                <div><small>DATE REGISTERED</small><strong><?= e($me["created_at"]) ?></strong></div>
            </div>
            <?php else: ?><p>Account details could not be loaded.</p><?php endif; ?>
        </section>
        <div class="notice"><strong>Access Control</strong><p>Your account has regular-user permissions. Admin-only pages and user management tools are restricted.</p></div>
    </main>
</div>
<?php page_footer(); ?>
