<?php
// Auto-start session for admin
if (session_status() === PHP_SESSION_NONE) session_start();

// Current page detection
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isAdmin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Make A Match — Sistem Rekomendasi Makeup dengan Content-Based Filtering (TF-IDF & Cosine Similarity)">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>Make A Match</title>
    <link rel="stylesheet" href="<?= $isAdmin ? '../' : '' ?>css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <a href="<?= $isAdmin ? '../index.php' : 'index.php' ?>" class="nav-logo">✦ <span>Make A</span>Match</a>
        <button class="nav-toggle" onclick="toggleMenu()" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="<?= $isAdmin ? '../' : '' ?>index.php" class="<?= $currentPage === 'index' && !$isAdmin ? 'active' : '' ?>">Beranda</a>
            <a href="<?= $isAdmin ? '../' : '' ?>rekomendasi.php" class="<?= $currentPage === 'rekomendasi' ? 'active' : '' ?>">Rekomendasi</a>
            <a href="<?= $isAdmin ? '../' : '' ?>tentang.php" class="<?= $currentPage === 'tentang' ? 'active' : '' ?>">Tentang</a>
            <?php if (isset($_SESSION['admin_id'])): ?>
                <a href="<?= $isAdmin ? '' : 'admin/' ?>index.php" class="nav-admin <?= $isAdmin ? 'active' : '' ?>">Admin</a>
            <?php else: ?>
                <a href="<?= $isAdmin ? '' : 'admin/' ?>login.php" class="nav-admin">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
