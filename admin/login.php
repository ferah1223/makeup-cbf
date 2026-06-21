<?php
session_start();
include '../includes/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' LIMIT 1");
    $admin = mysqli_fetch_assoc($query);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama_lengkap'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}

$pageTitle = 'Login Admin';
$isAdmin = true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — Make A Match</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="../index.php" class="nav-logo">✦ <span>Make A</span>Match</a>
        <div class="nav-links">
            <a href="../index.php">Beranda</a>
        </div>
    </div>
</nav>

<div class="login-wrapper">
    <div class="login-card">
        <div class="about-icon" style="margin-bottom: 20px;">✦</div>
        <h1>Admin Login</h1>
        <p>Masuk untuk mengelola produk</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group" style="text-align: left;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group" style="text-align: left;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary btn-full">Masuk</button>
        </form>
    </div>
</div>

<script src="../js/main.js"></script>
</body>
</html>
