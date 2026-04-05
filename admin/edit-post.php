<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if (!$id) redirect('posts.php');

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) redirect('posts.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = sanitize($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = sanitize($_POST['excerpt'] ?? '');

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        $cover_image = $post['cover_image'];

        // Handle new image upload
        if (!empty($_FILES['cover_image']['name'])) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image type.';
            } elseif ($_FILES['cover_image']['size'] > 5 * 1024 * 1024) {
                $error = 'Image too large. Max 5MB.';
            } else {
                // Delete old image
                if ($cover_image && file_exists('../uploads/' . $cover_image)) {
                    unlink('../uploads/' . $cover_image);
                }
                $cover_image = uniqid('img_') . '.' . $ext;
                move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $cover_image);
            }
        }

        // Remove image if checkbox ticked
        if (isset($_POST['remove_image']) && $post['cover_image']) {
            if (file_exists('../uploads/' . $post['cover_image'])) {
                unlink('../uploads/' . $post['cover_image']);
            }
            $cover_image = null;
        }

        if (!$error) {
            $stmt = $conn->prepare("UPDATE posts SET title=?, content=?, excerpt=?, cover_image=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $content, $excerpt, $cover_image, $id);

            if ($stmt->execute()) {
                $success = 'Post updated successfully!';
                // Refresh post data
                $stmt2 = $conn->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt2->bind_param("i", $id);
                $stmt2->execute();
                $post = $stmt2->get_result()->fetch_assoc();
            } else {
                $error = 'Update failed. Please try again.';
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
    <title>Edit Post — Admin</title>
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
            <h1 style="margin-bottom:0;">Edit Post</h1>
            <div style="display:flex; gap:10px;">
                <a href="../post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="btn btn-outline" target="_blank">View Post</a>
                <a href="posts.php" class="btn btn-outline">← All Posts</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div style="background:var(--white); border:1px solid var(--border); border-radius:var(--radius); padding:36px; box-shadow:var(--card-shadow); max-width:760px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Post Title *</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="excerpt">Short Excerpt</label>
                    <input type="text" id="excerpt" name="excerpt" value="<?= htmlspecialchars($post['excerpt'] ?? '') ?>" placeholder="Brief summary (optional)">
                </div>

                <div class="form-group">
                    <label>Cover Image</label>
                    <?php if ($post['cover_image'] && file_exists('../uploads/' . $post['cover_image'])): ?>
                        <div style="margin-bottom:12px;">
                            <img src="../uploads/<?= htmlspecialchars($post['cover_image']) ?>" style="max-height:160px; border-radius:4px; border:1px solid var(--border);">
                            <div style="margin-top:8px;">
                                <label style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:400; text-transform:none; letter-spacing:0; cursor:pointer;">
                                    <input type="checkbox" name="remove_image" value="1"> Remove current image
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    <div style="font-size:12px; color:var(--muted); margin-top:6px;">Upload new image to replace · JPG, PNG, GIF, WEBP · Max 5MB</div>
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" style="min-height:320px;" required><?= htmlspecialchars($post['content']) ?></textarea>
                </div>

                <div style="display:flex; gap:12px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
