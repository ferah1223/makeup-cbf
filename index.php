<?php
include 'includes/koneksi.php';
include 'includes/header.php';

$keyword = '';
if (isset($_GET['keyword']) && $_GET['keyword'] !== '') {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $query = mysqli_query(
        $conn,
        "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' OR brand LIKE '%$keyword%' OR kategori LIKE '%$keyword%' ORDER BY id ASC"
    );
} else {
    $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
}
$total = mysqli_num_rows($query);
?>

<!-- HERO -->
<?php if ($keyword === ''): ?>
<section class="hero">
    <div class="hero-content">
        <div class="hero-eyebrow">✦ Sistem Rekomendasi Berbasis Konten</div>
        <h1>Temukan Makeup yang <em>Cocok</em> untuk Kamu</h1>
        <p>Sistem rekomendasi cerdas menggunakan Content-Based Filtering dengan teknik TF-IDF dan Cosine Similarity untuk menemukan produk terbaik sesuai kebutuhan kulitmu.</p>
        <div class="hero-actions">
            <a href="rekomendasi.php" class="btn-primary">✦ Cari Rekomendasi</a>
            <a href="tentang.php" class="btn-secondary">Pelajari Cara Kerja</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SEARCH -->
<div class="search-section">
    <form method="GET" class="search-form">
        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari produk, brand, atau kategori...">
        <button type="submit">Cari</button>
    </form>
    <?php if ($keyword !== ''): ?>
    <a href="index.php" class="filter-clear">✕ Hapus filter</a>
    <?php endif; ?>
</div>

<!-- SECTION HEADER -->
<div class="section-header">
    <h2>
        <?php if ($keyword !== ''): ?>
            Hasil untuk "<?= htmlspecialchars($keyword) ?>"
        <?php else: ?>
            Semua Produk
        <?php endif; ?>
    </h2>
    <span class="count"><?= $total ?> produk</span>
</div>

<!-- PRODUCT GRID -->
<div class="container">
    <?php if ($total === 0): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9E7E8A" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M8 11h6"/></svg>
            </div>
            <p>Tidak ada produk yang cocok dengan pencarian Anda.</p>
            <a href="index.php" class="btn-secondary">Lihat Semua Produk</a>
        </div>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <a href="detail.php?id=<?= $row['id'] ?>" class="card">
            <div class="card-image-wrap">
                <img src="images/<?= htmlspecialchars($row['gambar']) ?>"
                     alt="<?= htmlspecialchars($row['nama_produk']) ?>"
                     loading="lazy"
                     onerror="this.src='images/placeholder.jpg'">
            </div>
            <div class="card-body">
                <span class="card-brand"><?= htmlspecialchars($row['brand']) ?></span>
                <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
                <div class="card-meta">
                    <span class="card-category"><?= htmlspecialchars($row['kategori']) ?></span>
                    <span class="card-price">Rp<?= number_format($row['harga'], 0, ',', '.') ?></span>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
