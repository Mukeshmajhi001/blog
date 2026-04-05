<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$total_posts = $conn->query("SELECT COUNT(*) as c FROM posts")->fetch_assoc()['c'];
$recent = $conn->query("SELECT p.*, a.username as author FROM posts p JOIN admins a ON p.author_id = a.id ORDER BY p.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — The Daily Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="../index.php" class="logo">The <span>Daily</span> Blog</a>
        <nav>
            <a href="../index.php">View Site</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<div class="admin-layout">
    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?> 👋</h1>

        <div style="display:flex; gap:20px; margin-bottom:40px; flex-wrap:wrap;">
            <div style="background:var(--white); border:1px solid var(--border); border-radius:var(--radius); padding:28px 36px; box-shadow:var(--card-shadow); flex:1; min-width:160px;">
                <div style="font-size:42px; font-weight:900; font-family:'Playfair Display',serif; color:var(--ink);"><?= $total_posts ?></div>
                <div style="font-size:13px; text-transform:uppercase; letter-spacing:0.1em; color:var(--muted); font-weight:700; margin-top:4px;">Total Posts</div>
            </div>
            <div style="background:var(--ink); border-radius:var(--radius); padding:28px 36px; box-shadow:var(--card-shadow); flex:1; min-width:160px; display:flex; align-items:center;">
                <a href="new-post.php" class="btn btn-outline" style="color:white; border-color:white; width:100%; text-align:center;">+ New Post</a>
            </div>
        </div>

        <h2 style="font-family:'Playfair Display',serif; font-size:20px; margin-bottom:20px;">Recent Posts</h2>

        <?php if ($recent->num_rows === 0): ?>
            <div class="empty-state">
                <div class="icon">📝</div>
                <h3>No posts yet</h3>
                <p>Create your first post to get started.</p>
                <a href="new-post.php" class="btn btn-primary" style="margin-top:16px;">Create Post</a>
            </div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($post = $recent->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($post['title']) ?></strong></td>
                    <td><?= htmlspecialchars($post['author']) ?></td>
                    <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="btn btn-sm btn-outline" target="_blank">View</a>
                            <a href="edit-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p style="margin-top:16px; font-size:14px;"><a href="posts.php">View all posts →</a></p>
        <?php endif; ?>
    </main>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog Admin</p>
</footer>

</body>
</html>
