<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include '../includes/koneksi.php';
$pageTitle = 'Admin Dashboard';
$isAdmin = true;
include '../includes/header.php';

// Stats
$totalQ = mysqli_query($conn, "SELECT COUNT(*) as c FROM produk");
$total = mysqli_fetch_assoc($totalQ)['c'];

$katQ = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as c FROM produk");
$katCount = mysqli_fetch_assoc($katQ)['c'];

$brandQ = mysqli_query($conn, "SELECT COUNT(DISTINCT brand) as c FROM produk");
$brandCount = mysqli_fetch_assoc($brandQ)['c'];

// All products
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");

// Flash message
$msg = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<div class="admin-layout">
    <div class="admin-header">
        <div>
            <h1>Dashboard</h1>
            <p class="admin-subtitle">Selamat datang, <?= htmlspecialchars($_SESSION['admin_nama']) ?></p>
        </div>
        <div class="admin-header-actions">
            <a href="tambah.php" class="btn-primary">+ Tambah Produk</a>
            <a href="logout.php" class="btn-secondary">Logout</a>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg['type'] ?>"><?= $msg['text'] ?></div>
    <?php endif; ?>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num"><?= $total ?></div>
            <div class="stat-label">Total Produk</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $katCount ?></div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $brandCount ?></div>
            <div class="stat-label">Brand</div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($produk)): ?>
                <tr>
                    <td><img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="" onerror="this.src='../images/placeholder.jpg'"></td>
                    <td><strong><?= htmlspecialchars($row['nama_produk']) ?></strong></td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><span class="attr-chip"><?= htmlspecialchars($row['kategori']) ?></span></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <div class="admin-actions">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
