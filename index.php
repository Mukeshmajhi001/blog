<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$posts = $conn->query("
    SELECT p.*, a.username as author_name 
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
    <title>The Daily Blog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">The <span>Daily</span> Blog</a>
        <nav>
            <a href="index.php" class="active">Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="admin/dashboard.php">Dashboard</a>
                <a href="admin/logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<section class="page-hero">
    <h1>Stories Worth Reading</h1>
    <p>Thoughtful articles, ideas & perspectives — fresh from the desk.</p>
</section>

<div class="container">
    <?php if ($posts->num_rows === 0): ?>
        <div class="empty-state">
            <div class="icon">✍️</div>
            <h3>No posts yet</h3>
            <p>Check back soon — great content is coming.</p>
        </div>
    <?php else: ?>
        <div class="posts-grid">
            <?php while ($post = $posts->fetch_assoc()): ?>
            <article class="post-card">
                <?php if ($post['cover_image'] && file_exists('uploads/' . $post['cover_image'])): ?>
                    <img class="post-card-img" src="uploads/<?= htmlspecialchars($post['cover_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php else: ?>
                    <div class="post-card-img-placeholder">📰</div>
                <?php endif; ?>
                <div class="post-card-body">
                    <div class="post-date"><?= date('M j, Y', strtotime($post['created_at'])) ?> · <?= htmlspecialchars($post['author_name']) ?></div>
                    <h2 class="post-card-title">
                        <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                    </h2>
                    <p class="post-card-excerpt">
                        <?= htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160) . '...') ?>
                    </p>
                    <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="read-more">Read more</a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog &nbsp;·&nbsp; <a href="mks75@2062/login.php">Admin</a></p>
</footer>

</body>
</html>
