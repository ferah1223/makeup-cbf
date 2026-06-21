<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include '../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id LIMIT 1");
    $_SESSION['flash'] = ['type' => 'success', 'text' => 'Produk berhasil dihapus.'];
}
header('Location: index.php');
exit;
