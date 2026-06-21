<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include '../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id = $id LIMIT 1"));
if (!$data) { header('Location: index.php'); exit; }

$pageTitle = 'Edit: ' . $data['nama_produk'];
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
        $sql = "UPDATE produk SET
                nama_produk='$nama', brand='$brand', harga=$harga, gambar='$gambar',
                kategori='$kategori', jenis_kulit='$kulit', finish_type='$finish',
                tone='$tone', deskripsi='$deskripsi'
                WHERE id=$id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['flash'] = ['type' => 'success', 'text' => 'Produk berhasil diperbarui.'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Gagal update: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Semua field wajib diisi.';
    }
}
?>

<div class="admin-layout">
    <div class="admin-header">
        <h1>Edit Produk</h1>
        <a href="index.php" class="btn-secondary">← Kembali</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
            </div>
            <div class="form-group">
                <label>Brand *</label>
                <input type="text" name="brand" value="<?= htmlspecialchars($data['brand']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Harga (Rp) *</label>
                <input type="number" name="harga" value="<?= $data['harga'] ?>" min="0" required>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Jenis Kulit *</label>
                <input type="text" name="jenis_kulit" value="<?= htmlspecialchars($data['jenis_kulit']) ?>" required>
            </div>
            <div class="form-group">
                <label>Finish Type *</label>
                <input type="text" name="finish_type" value="<?= htmlspecialchars($data['finish_type']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tone *</label>
                <input type="text" name="tone" value="<?= htmlspecialchars($data['tone']) ?>" required>
            </div>
            <div class="form-group">
                <label>Nama File Gambar</label>
                <input type="text" name="gambar" value="<?= htmlspecialchars($data['gambar']) ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Deskripsi *</label>
            <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
