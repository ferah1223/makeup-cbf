<?php
include 'includes/koneksi.php';
include 'includes/cbf.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id LIMIT 1");
$data = mysqli_fetch_assoc($query);
if (!$data) { header('Location: index.php'); exit; }

$pageTitle = $data['nama_produk'];
include 'includes/header.php';

// Hitung rekomendasi CBF
$engine = new CBEngine($conn);
$recommendations = $engine->getRecommendations($id, 5);
?>

<!-- DETAIL -->
<div class="detail-wrapper">
    <div class="detail-card">
        <div class="detail-image-panel">
            <img src="images/<?= htmlspecialchars($data['gambar']) ?>"
                 alt="<?= htmlspecialchars($data['nama_produk']) ?>"
                 onerror="this.src='images/placeholder.jpg'">
        </div>
        <div class="detail-info-panel">
            <span class="detail-badge"><?= htmlspecialchars($data['kategori']) ?></span>
            <h1><?= htmlspecialchars($data['nama_produk']) ?></h1>
            <div class="detail-price">Rp<?= number_format($data['harga'], 0, ',', '.') ?></div>

            <div class="detail-attrs">
                <div class="attr-item">
                    <div class="attr-label">Brand</div>
                    <div class="attr-value"><?= htmlspecialchars($data['brand']) ?></div>
                </div>
                <div class="attr-item">
                    <div class="attr-label">Jenis Kulit</div>
                    <div class="attr-value"><?= htmlspecialchars($data['jenis_kulit']) ?></div>
                </div>
                <div class="attr-item">
                    <div class="attr-label">Finish Type</div>
                    <div class="attr-value"><?= htmlspecialchars($data['finish_type']) ?></div>
                </div>
                <div class="attr-item">
                    <div class="attr-label">Tone</div>
                    <div class="attr-value"><?= htmlspecialchars($data['tone']) ?></div>
                </div>
            </div>

            <div class="detail-desc-label">Deskripsi Produk</div>
            <p class="detail-desc"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

            <a href="index.php" class="btn-back">← Kembali ke Produk</a>
        </div>
    </div>
</div>

<!-- REKOMENDASI CBF -->
<?php if (count($recommendations) > 0): ?>
<section class="recommendations">
    <div class="rec-header">
        <h2>✦ Produk Serupa</h2>
        <p>Rekomendasi berdasarkan kemiripan konten — Content-Based Filtering (TF-IDF + Cosine Similarity)</p>
    </div>
    <div class="rec-grid">
        <?php foreach ($recommendations as $rec): ?>
            <?php $p = $rec['product']; $pct = round($rec['score'] * 100); ?>
            <a href="detail.php?id=<?= $p['id'] ?>" class="rec-card">
                <div class="rec-image-wrap">
                    <img src="images/<?= htmlspecialchars($p['gambar']) ?>"
                         alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                         loading="lazy"
                         onerror="this.src='images/placeholder.jpg'">
                </div>
                <div class="rec-body">
                    <span class="rec-brand"><?= htmlspecialchars($p['brand']) ?></span>
                    <h4><?= htmlspecialchars($p['nama_produk']) ?></h4>
                    <div class="rec-meta">
                        <span class="rec-score"><?= $pct ?>% match</span>
                        <span class="rec-price">Rp<?= number_format($p['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
