<?php
include 'includes/koneksi.php';
include 'includes/cbf.php';
$pageTitle = 'Rekomendasi';
include 'includes/header.php';

// Ambil opsi filter dari database
$kategoriQ = mysqli_query($conn, "SELECT DISTINCT kategori FROM produk ORDER BY kategori");
$kulitQ    = mysqli_query($conn, "SELECT DISTINCT jenis_kulit FROM produk ORDER BY jenis_kulit");
$finishQ   = mysqli_query($conn, "SELECT DISTINCT finish_type FROM produk ORDER BY finish_type");
$toneQ     = mysqli_query($conn, "SELECT DISTINCT tone FROM produk ORDER BY tone");

$kategoriList = []; while ($r = mysqli_fetch_assoc($kategoriQ)) $kategoriList[] = $r['kategori'];
$kulitList    = []; while ($r = mysqli_fetch_assoc($kulitQ))    $kulitList[]    = $r['jenis_kulit'];
$finishList   = []; while ($r = mysqli_fetch_assoc($finishQ))   $finishList[]   = $r['finish_type'];
$toneList     = []; while ($r = mysqli_fetch_assoc($toneQ))     $toneList[]     = $r['tone'];

$results = [];
$hasFilter = false;

// Proses form rekomendasi
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hitung'])) {
    $prefs = [];
    if (!empty($_GET['kategori']))    { $prefs['kategori']    = $_GET['kategori'];    $hasFilter = true; }
    if (!empty($_GET['jenis_kulit'])) { $prefs['jenis_kulit'] = $_GET['jenis_kulit']; $hasFilter = true; }
    if (!empty($_GET['finish_type'])) { $prefs['finish_type'] = $_GET['finish_type']; $hasFilter = true; }
    if (!empty($_GET['tone']))        { $prefs['tone']        = $_GET['tone'];        $hasFilter = true; }

    if ($hasFilter) {
        $engine = new CBEngine($conn);
        $results = $engine->getRecommendationsByPreferences($prefs, 8);
    }
}
?>

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="page-header-content">
        <h1>✦ Rekomendasi Produk</h1>
        <p>Masukkan preferensi kulitmu, dan sistem akan mencocokkan produk terbaik menggunakan <strong>Content-Based Filtering</strong> (TF-IDF + Cosine Similarity).</p>
    </div>
</div>

<!-- FILTER FORM -->
<div class="rekomendasi-wrapper">
    <form method="GET" class="filter-form">
        <div class="filter-grid">
            <div class="form-group">
                <label for="kategori">Kategori Produk</label>
                <select name="kategori" id="kategori">
                    <option value="">— Semua Kategori —</option>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= $kat ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] === $kat) ? 'selected' : '' ?>><?= $kat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jenis_kulit">Jenis Kulit</label>
                <select name="jenis_kulit" id="jenis_kulit">
                    <option value="">— Semua Jenis —</option>
                    <?php foreach ($kulitList as $kulit): ?>
                        <option value="<?= $kulit ?>" <?= (isset($_GET['jenis_kulit']) && $_GET['jenis_kulit'] === $kulit) ? 'selected' : '' ?>><?= $kulit ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="finish_type">Finish Type</label>
                <select name="finish_type" id="finish_type">
                    <option value="">— Semua Finish —</option>
                    <?php foreach ($finishList as $fin): ?>
                        <option value="<?= $fin ?>" <?= (isset($_GET['finish_type']) && $_GET['finish_type'] === $fin) ? 'selected' : '' ?>><?= $fin ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tone">Tone</label>
                <select name="tone" id="tone">
                    <option value="">— Semua Tone —</option>
                    <?php foreach ($toneList as $t): ?>
                        <option value="<?= $t ?>" <?= (isset($_GET['tone']) && $_GET['tone'] === $t) ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="hitung" value="1">
        <button type="submit" class="btn-primary btn-full">✦ Hitung Rekomendasi</button>
    </form>

    <!-- RESULTS -->
    <?php if ($hasFilter && count($results) > 0): ?>
    <div class="results-section">
        <div class="results-header">
            <h2>Hasil Rekomendasi</h2>
            <p>Diurutkan berdasarkan skor kemiripan (Cosine Similarity) dari TF-IDF vector</p>
        </div>

        <div class="results-grid">
            <?php foreach ($results as $i => $rec): ?>
                <?php $p = $rec['product']; $score = $rec['score']; $pct = round($score * 100); ?>
                <a href="detail.php?id=<?= $p['id'] ?>" class="result-card">
                    <div class="result-rank">#<?= $i + 1 ?></div>
                    <div class="result-image-wrap">
                        <img src="images/<?= htmlspecialchars($p['gambar']) ?>"
                             alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                             loading="lazy"
                             onerror="this.src='images/placeholder.jpg'">
                    </div>
                    <div class="result-body">
                        <span class="result-brand"><?= htmlspecialchars($p['brand']) ?></span>
                        <h4><?= htmlspecialchars($p['nama_produk']) ?></h4>
                        <div class="result-attrs">
                            <span class="attr-chip"><?= htmlspecialchars($p['kategori']) ?></span>
                            <span class="attr-chip"><?= htmlspecialchars($p['jenis_kulit']) ?></span>
                            <span class="attr-chip"><?= htmlspecialchars($p['finish_type']) ?></span>
                        </div>
                        <div class="result-footer">
                            <div class="score-bar-wrap">
                                <div class="score-bar" style="width: <?= $pct ?>%"></div>
                                <span class="score-text"><?= $pct ?>% match</span>
                            </div>
                            <span class="result-price">Rp<?= number_format($p['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- ALGORITMA EXPLANATION -->
        <div class="algo-explain">
            <h3>Bagaimana Sistem Ini Bekerja?</h3>
            <div class="algo-steps">
                <div class="algo-step">
                    <div class="algo-num">1</div>
                    <h4>Preferensi → Query Vector</h4>
                    <p>Pilihan kamu (kategori, jenis kulit, finish, tone) diubah menjadi dokumen teks, lalu di-tokenisasi dan dihitung TF-IDF-nya.</p>
                </div>
                <div class="algo-step">
                    <div class="algo-num">2</div>
                    <h4>TF-IDF Vectorization</h4>
                    <p>Setiap produk di database juga punya TF-IDF vector. TF menghitung frekuensi kata, IDF menurunkan bobot kata yang terlalu umum.</p>
                </div>
                <div class="algo-step">
                    <div class="algo-num">3</div>
                    <h4>Cosine Similarity</h4>
                    <p>Sistem menghitung cosine similarity antara query vector dan setiap produk. Skor 1.0 = identik, 0.0 = tidak mirip.</p>
                </div>
                <div class="algo-step">
                    <div class="algo-num">4</div>
                    <h4>Ranking</h4>
                    <p>Produk diurutkan dari skor tertinggi. Semakin tinggi persentase, semakin cocok produk tersebut dengan preferensimu.</p>
                </div>
            </div>
        </div>
    </div>

    <?php elseif ($hasFilter && count($results) === 0): ?>
    <div class="empty-state">
        <p>Tidak ditemukan produk yang cocok dengan preferensi tersebut.</p>
        <a href="rekomendasi.php" class="btn-secondary">Reset Filter</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
