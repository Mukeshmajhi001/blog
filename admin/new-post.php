<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = sanitize($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = sanitize($_POST['excerpt'] ?? '');

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        // Generate unique slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        $base_slug = $slug;
        $counter = 1;
        while ($conn->query("SELECT id FROM posts WHERE slug = '$slug'")->num_rows > 0) {
            $slug = $base_slug . '-' . $counter++;
        }

        // Handle image upload
        $cover_image = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image type. Allowed: JPG, PNG, GIF, WEBP';
            } elseif ($_FILES['cover_image']['size'] > 5 * 1024 * 1024) {
                $error = 'Image too large. Max 5MB.';
            } else {
                $cover_image = uniqid('img_') . '.' . $ext;
                move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $cover_image);
            }
        }

        if (!$error) {
            $author_id = $_SESSION['admin_id'];
            $stmt = $conn->prepare("INSERT INTO posts (title, slug, content, excerpt, cover_image, author_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $slug, $content, $excerpt, $cover_image, $author_id);

            if ($stmt->execute()) {
                header('Location: ' . getBaseUrl() . '/admin/posts.php?created=1'); exit();
            } else {
                $error = 'Failed to create post. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post — Admin</title>
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
            <h1 style="margin-bottom:0;">New Post</h1>
            <a href="posts.php" class="btn btn-outline">← All Posts</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="background:var(--white); border:1px solid var(--border); border-radius:var(--radius); padding:36px; box-shadow:var(--card-shadow); max-width:760px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Post Title *</label>
                    <input type="text" id="title" name="title" placeholder="Enter a catchy title..." value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="excerpt">Short Excerpt</label>
                    <input type="text" id="excerpt" name="excerpt" placeholder="Brief summary shown in post cards (optional)" value="<?= htmlspecialchars($_POST['excerpt'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="cover_image">Cover Image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    <div style="font-size:12px; color:var(--muted); margin-top:6px;">JPG, PNG, GIF, WEBP · Max 5MB</div>
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" placeholder="Write your blog post here..." style="min-height:320px;" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                </div>

                <div style="display:flex; gap:12px;">
                    <button type="submit" class="btn btn-primary">Publish Post</button>
                    <a href="posts.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog Admin</p>
</footer>

</body>
</html>
