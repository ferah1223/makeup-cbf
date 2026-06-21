<?php
// Database Connection
$conn = mysqli_connect("localhost", "makeup_user", "makeup_pass_2025", "db_makeup");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
