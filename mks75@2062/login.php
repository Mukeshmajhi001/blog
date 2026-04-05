<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (isLoggedIn()) {
    $base = getBaseUrl();
    header("Location: $base/admin/dashboard.php");
    exit();
}

$error   = '';
$username_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Fixed: removed $ from 'password'
    $username_val = $username;

    if ($username === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin  = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['PASSWORD'])) {

        
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $base = getBaseUrl();
            header("Location: $base/admin/dashboard.php");
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — The Daily Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="../index.php" class="logo">The <span>Daily</span> Blog</a>
        <nav>
            <a href="../index.php">Back to Site</a>
        </nav>
    </div>
</header>

<div class="form-card">
    <h2>Admin Login</h2>
    <p class="form-subtitle">Sign in to manage your blog posts.</p>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username"
                   placeholder="Enter username or email"
                   value="<?= htmlspecialchars($username_val) ?>"
                   required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Sign In</button>
    </form>

    <hr class="divider">
    <p style="text-align:center; font-size:14px; color:var(--muted);">
        No account? <a href="signup.php">Create one</a>
    </p>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog</p>
</footer>

</body>
</html>