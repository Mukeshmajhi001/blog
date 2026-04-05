<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);

if ($id) {
    $stmt = $conn->prepare("SELECT cover_image FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    if ($post) {
        if ($post['cover_image'] && file_exists('../uploads/' . $post['cover_image'])) {
            unlink('../uploads/' . $post['cover_image']);
        }
        $del = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
    }
}

$base = getBaseUrl();
header("Location: $base/admin/posts.php?deleted=1");
exit();
