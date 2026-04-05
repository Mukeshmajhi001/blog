<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$posts = $conn->query("
    SELECT p.*, a.username as author 
    FROM posts p 
    JOIN admins a ON p.author_id = a.id 
    ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts — Admin</title>
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
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:32px;">
            <h1 style="margin-bottom:0;">All Posts</h1>
            <a href="new-post.php" class="btn btn-primary">+ New Post</a>
        </div>

        <?php if ($posts->num_rows === 0): ?>
            <div class="empty-state">
                <div class="icon">📝</div>
                <h3>No posts yet</h3>
                <p>Get started by creating your first blog post.</p>
                <a href="new-post.php" class="btn btn-primary" style="margin-top:16px;">Create First Post</a>
            </div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($post = $posts->fetch_assoc()): ?>
                <tr>
                    <td style="color:var(--muted)"><?= $i++ ?></td>
                    <td>
                        <strong><?= htmlspecialchars($post['title']) ?></strong>
                        <div style="font-size:12px; color:var(--muted); margin-top:2px;">/<?= htmlspecialchars($post['slug']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($post['author']) ?></td>
                    <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="btn btn-sm btn-outline" target="_blank">View</a>
                            <a href="edit-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post? This cannot be undone.')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog Admin</p>
</footer>

</body>
</html>
<!-- flash handled in header above -->
