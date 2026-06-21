<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include '../includes/koneksi.php';
$pageTitle = 'Tambah Produk';
$isAdmin = true;
include '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_produk'] ?? '');
    $brand    = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
    $harga    = (int)($_POST['harga'] ?? 0);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $kulit    = mysqli_real_escape_string($conn, $_POST['jenis_kulit'] ?? '');
    $finish   = mysqli_real_escape_string($conn, $_POST['finish_type'] ?? '');
    $tone     = mysqli_real_escape_string($conn, $_POST['tone'] ?? '');
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');
    $gambar   = mysqli_real_escape_string($conn, $_POST['gambar'] ?? 'placeholder.jpg');

    if ($nama && $brand && $harga && $kategori && $kulit && $finish && $tone && $deskripsi) {
        $sql = "INSERT INTO produk (nama_produk, brand, harga, gambar, kategori, jenis_kulit, finish_type, tone, deskripsi)
                VALUES ('$nama', '$brand', $harga, '$gambar', '$kategori', '$kulit', '$finish', '$tone', '$deskripsi')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['flash'] = ['type' => 'success', 'text' => 'Produk berhasil ditambahkan.'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Semua field wajib diisi.';
    }
}
?>

<div class="admin-layout">
    <div class="admin-header">
        <h1>+ Tambah Produk</h1>
        <a href="index.php" class="btn-secondary">← Kembali</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text" name="nama_produk" value="<?= htmlspecialchars($_POST['nama_produk'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Brand *</label>
                <input type="text" name="brand" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Harga (Rp) *</label>
                <input type="number" name="harga" value="<?= $_POST['harga'] ?? '' ?>" min="0" required>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <input type="text" name="kategori" value="<?= htmlspecialchars($_POST['kategori'] ?? '') ?>" placeholder="Foundation, BB Cream, dll." required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Jenis Kulit *</label>
                <input type="text" name="jenis_kulit" value="<?= htmlspecialchars($_POST['jenis_kulit'] ?? '') ?>" placeholder="Berminyak, Kering, Normal, dll." required>
            </div>
            <div class="form-group">
                <label>Finish Type *</label>
                <input type="text" name="finish_type" value="<?= htmlspecialchars($_POST['finish_type'] ?? '') ?>" placeholder="Matte, Dewy, Satin, dll." required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tone *</label>
                <input type="text" name="tone" value="<?= htmlspecialchars($_POST['tone'] ?? '') ?>" placeholder="Warm, Cool, Neutral" required>
            </div>
            <div class="form-group">
                <label>Nama File Gambar</label>
                <input type="text" name="gambar" value="<?= htmlspecialchars($_POST['gambar'] ?? 'placeholder.jpg') ?>" placeholder="placeholder.jpg">
            </div>
        </div>
        <div class="form-group">
            <label>Deskripsi *</label>
            <textarea name="deskripsi" required placeholder="Deskripsi lengkap produk..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Produk</button>
            <a href="index.php" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
