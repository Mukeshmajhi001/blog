<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (!$slug) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT p.*, a.username as author_name 
    FROM posts p 
    JOIN admins a ON p.author_id = a.id 
    WHERE p.slug = ?
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    header("Location: 404.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> — The Daily Blog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">The <span>Daily</span> Blog</a>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="admin/dashboard.php">Dashboard</a>
                <a href="admin/logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="container-narrow">
    <a href="index.php" style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--muted); display:inline-flex; align-items:center; gap:6px; margin-bottom:40px;">← Back to Home</a>

    <article>
        <header class="post-header">
            <div class="post-date"><?= date('F j, Y', strtotime($post['created_at'])) ?></div>
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-meta">
                <span>By <span class="author"><?= htmlspecialchars($post['author_name']) ?></span></span>
                <?php if ($post['updated_at'] !== $post['created_at']): ?>
                    <span>Updated <?= date('M j, Y', strtotime($post['updated_at'])) ?></span>
                <?php endif; ?>
                <?php if (isLoggedIn()): ?>
                    <a href="admin/edit-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline">Edit Post</a>
                <?php endif; ?>
            </div>
        </header>

        <?php if ($post['cover_image'] && file_exists('uploads/' . $post['cover_image'])): ?>
            <img class="post-cover" src="uploads/<?= htmlspecialchars($post['cover_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        <?php endif; ?>

        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
    </article>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog &nbsp;·&nbsp; <a href="mks75@2062/login.php">Admin</a></p>
</footer>

</body>
</html>
