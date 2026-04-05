<?php
require_once 'includes/auth.php';
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found — The Daily Blog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">The <span>Daily</span> Blog</a>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="not-found">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <a href="index.php" class="btn btn-primary">← Go Back Home</a>
    </div>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> The Daily Blog</p>
</footer>

</body>
</html>
