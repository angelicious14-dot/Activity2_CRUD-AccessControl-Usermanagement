<?php
require_once "auth.php";
require_admin();

$total = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$admins = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$regular = $total - $admins;
$users = $pdo->query("SELECT id, fullname, email, username, role, created_at FROM users ORDER BY id DESC")->fetchAll();
$msg = $_GET["msg"] ?? "";
page_header("Admin Dashboard");
?>
<div class="app-layout">
    <aside class="sidebar">
        <div class="side-label">ADMIN MENU</div>
        <a class="side-link active" href="admin.php">▦ <span>Dashboard</span></a>
        <a class="side-link" href="#userTable">♙ <span>User Management</span></a>
        <div class="side-label">ACCOUNT</div>
        <a class="side-link" href="logout.php">↪ <span>Sign Out</span></a>
    </aside>
    <main class="main-content">
        <div class="page-heading"><div><span class="eyebrow">ADMINISTRATION</span><h1>Admin Dashboard</h1><p class="muted">Manage registered accounts and access levels.</p></div><span class="status-badge">● Administrator</span></div>
        <?php if ($msg): ?><div class="alert success"><?= e($msg) ?></div><?php endif; ?>
        <section class="stats-grid">
            <article class="stat-card"><span class="stat-icon purple">♙</span><small>TOTAL ACCOUNTS</small><strong><?= $total ?></strong><span class="stat-foot">All registered users</span></article>
            <article class="stat-card"><span class="stat-icon green">✓</span><small>REGULAR USERS</small><strong><?= $regular ?></strong><span class="stat-foot">Standard accounts</span></article>
            <article class="stat-card"><span class="stat-icon orange">◆</span><small>ADMIN ACCOUNTS</small><strong><?= $admins ?></strong><span class="stat-foot">Administrative access</span></article>
        </section>
        <section class="content-card" id="userTable">
            <div class="section-title"><div><h2>User Management</h2><p class="muted">Create, view, update, and delete accounts.</p></div><a class="btn primary" href="add_user.php">＋ Add User</a></div>
            <div class="table-wrap"><table><thead><tr><th>USER</th><th>USERNAME</th><th>ROLE</th><th>REGISTERED</th><th>ACTIONS</th></tr></thead><tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><strong><?= e($u["fullname"]) ?></strong><small class="table-sub"><?= e($u["email"]) ?></small></td>
                    <td><?= e($u["username"]) ?></td>
                    <td><span class="role-chip <?= $u["role"] === "admin" ? "admin-chip" : "" ?>"><?= e(ucfirst($u["role"])) ?></span></td>
                    <td><?= e(date("M d, Y", strtotime($u["created_at"]))) ?></td>
                    <td><div class="action-group"><a class="action-link" href="edit_user.php?id=<?= (int)$u["id"] ?>">Edit</a>
                    <?php if ((int)$u["id"] !== (int)$_SESSION["user_id"]): ?>
                    <form action="delete_user.php" method="POST" onsubmit="return confirm('Delete this account? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$u["id"] ?>"><button class="action-link danger-link" type="submit">Delete</button></form>
                    <?php else: ?><span class="muted tiny">You</span><?php endif; ?></div></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$users): ?><tr><td colspan="5" class="empty">No users found.</td></tr><?php endif; ?>
            </tbody></table></div>
        </section>
    </main>
</div>
<?php page_footer(); ?>
