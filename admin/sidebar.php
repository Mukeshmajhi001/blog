<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-title">Admin Panel</div>
    <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="posts.php" class="<?= $current === 'posts.php' ? 'active' : '' ?>">📰 All Posts</a>
    <a href="new-post.php" class="<?= $current === 'new-post.php' ? 'active' : '' ?>">✏️ New Post</a>
    <hr style="border-color:rgba(255,255,255,0.1); margin:16px 0;">
    <a href="../index.php">🌐 View Site</a>
    <a href="logout.php" style="color:#e87b7b;">🚪 Logout</a>
</aside>
