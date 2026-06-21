# Make A Match

Sistem Rekomendasi Makeup menggunakan **Content-Based Filtering (CBF)** dengan teknik **TF-IDF Vectorization** dan **Cosine Similarity**.

## Tentang

Make A Match membantu pengguna menemukan produk makeup yang sesuai dengan kebutuhan kulit mereka. Sistem menganalisis atribut produk (kategori, jenis kulit, finish type, tone, deskripsi) dan menghitung kemiripan antar produk menggunakan algoritma TF-IDF + Cosine Similarity.

### Fitur

- **Rekomendasi Berbasis Preferensi** — Pilih jenis kulit, finish type, dan tone untuk mendapat rekomendasi produk yang paling cocok
- **Rekomendasi Produk Serupa** — Di halaman detail, sistem menampilkan 5 produk paling mirip beserta skor match-nya
- **Pencarian Produk** — Cari berdasarkan nama, brand, atau kategori
- **Admin Panel** — CRUD produk (tambah, edit, hapus)
- **Visualisasi Skor** — Persentase kemiripan ditampilkan di setiap rekomendasi

### Algoritma

1. **Representasi Konten** — Setiap produk diubah menjadi dokumen teks dari gabungan atribut
2. **Tokenisasi** — Dokumen dipecah menjadi kata-kata, di-lowercase, tanpa simbol
3. **TF (Term Frequency)** — Menghitung frekuensi kata dalam satu dokumen
4. **IDF (Inverse Document Frequency)** — Menurunkan bobot kata yang terlalu umum di semua dokumen
5. **TF-IDF Vector** — Mengalikan TF × IDF untuk mendapat bobot akhir setiap kata
6. **Cosine Similarity** — Menghitung kemiripan antar vektor: `sim(A,B) = (A·B) / (|A| × |B|)`

## Tech Stack

- **Backend:** PHP 8.1 (native, no framework)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 (custom design system), Vanilla JavaScript
- **Server:** Apache 2.4

## Setup

### 1. Install Dependencies

```bash
sudo -S -p '' apt-get install php php-mysqli php-mbstring mariadb-server apache2 libapache2-mod-php
```

### 2. Setup Database

```bash
sudo -S -p '' mysql < database/schema.sql
```

### 3. Create Database User

```bash
sudo -S -p '' mysql -e "CREATE USER 'makeup_user'@'localhost' IDENTIFIED BY 'makeup_pass_2025';"
sudo -S -p '' mysql -e "GRANT ALL PRIVILEGES ON db_makeup.* TO 'makeup_user'@'localhost';"
sudo -S -p '' mysql -e "FLUSH PRIVILEGES;"
```

### 4. Configure Apache

```bash
sudo -S -p '' cp makeup-cbf.conf /etc/apache2/sites-available/
sudo -S -p '' a2ensite makeup-cbf.conf
sudo -S -p '' systemctl restart apache2
```

### 5. Akses

- **Beranda:** `http://localhost/`
- **Rekomendasi:** `http://localhost/rekomendasi.php`
- **Admin:** `http://localhost/admin/login.php`

**Login Admin:** `admin` / `admin123`

## Struktur Project

```
makeup-cbf/
├── index.php              # Beranda + daftar produk
├── detail.php             # Detail produk + rekomendasi CBF
├── rekomendasi.php        # Form preferensi + hasil rekomendasi
├── tentang.php            # Tentang sistem & algoritma
├── admin/
│   ├── login.php          # Login admin
│   ├── index.php          # Dashboard + tabel produk
│   ├── tambah.php         # Tambah produk
│   ├── edit.php           # Edit produk
│   ├── hapus.php          # Hapus produk
│   └── logout.php         # Logout
├── includes/
│   ├── header.php         # HTML head + navbar
│   ├── footer.php         # Footer + script
│   ├── koneksi.php        # Database connection
│   └── cbf.php            # CBF Engine (TF-IDF + Cosine Similarity)
├── css/
│   └── style.css          # Design system
├── js/
│   └── main.js            # Mobile menu + animations
├── images/                # Product images
└── database/
    └── schema.sql         # Database schema + sample data (20 produk)
```

## Spesifikasi Penelitian

- **Metode:** Content-Based Filtering
- **Teknik:** TF-IDF Vectorization + Cosine Similarity
- **Atribut:** Kategori, Jenis Kulit, Finish Type, Tone, Deskripsi
- **Dataset:** 20 produk makeup (sample testing)
- **Output:** Ranking produk berdasarkan skor kemiripan (0-100%)
