-- =============================================
-- Make A Match — Database Schema
-- Sistem Rekomendasi Makeup (Content-Based Filtering)
-- =============================================

CREATE DATABASE IF NOT EXISTS db_makeup
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_makeup;

-- Tabel produk makeup
DROP TABLE IF EXISTS produk;
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    harga INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) NOT NULL DEFAULT 'placeholder.jpg',
    kategori VARCHAR(80) NOT NULL,
    jenis_kulit VARCHAR(80) NOT NULL,
    finish_type VARCHAR(80) NOT NULL,
    tone VARCHAR(80) NOT NULL,
    deskripsi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel admin
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin (password: admin123)
INSERT INTO admin (username, password, nama_lengkap) VALUES
('admin', '$2y$10$1EodBqucNMyfBXweeg2Aeupiv7fuzV6.jwFvnSNKq2K3WHb1PYnCW', 'Administrator');

-- =============================================
-- 20 Sample Data Produk Makeup untuk Testing
-- =============================================

INSERT INTO produk (nama_produk, brand, harga, gambar, kategori, jenis_kulit, finish_type, tone, deskripsi) VALUES

-- 1
('Fit Me Matte + Poreless Foundation', 'Maybelline', 139000, 'fitme.jpeg',
'Foundation', 'Berminyak', 'Matte', 'Warm',
'Foundation ringan dengan formula micro-powder yang menyerap minyak dan menyamarkan pori. Cocok untuk kulit berminyak yang ingin tampilan matte natural sepanjang hari. Coverage medium to full yang bisa di-build.'),

-- 2
('ColorFit Foundation', 'Make Over', 169000, 'colorfit.jpeg',
'Foundation', 'Kombinasi', 'Satin', 'Neutral',
'Foundation dengan teknologi Color-Adapt yang menyesuaikan warna kulit. Formula ringan dengan skincare ingredient untuk menjaga kelembapan. Hasil akhir satin yang elegan dan tahan lama.'),

-- 3
('PowerStay Foundation', 'Wardah', 99000, 'powerstay.jpeg',
'Foundation', 'Berminyak', 'Matte', 'Cool',
'Foundation full coverage dengan formula tahan lama hingga 12 jam. Diperkaya vitamin E dan SPF 30 untuk perlindungan kulit. Hasil matte yang flawless tanpa crack.'),

-- 4
('Sky High Lash Sensational Mascara', 'Maybelline', 149000, 'sky.jpeg',
'Mascara', 'Normal', 'Natural', 'Warm',
'Mascara dengan teknologi flex fiber yang memberikan efek bulu mata panjang dan lentik. Formula ringan tidak menggumpal dan mudah dibersihkan.'),

-- 5
('Instant Age Rewind Concealer', 'Maybelline', 135000, 'fitme.jpeg',
'Concealer', 'Kering', 'Natural', 'Warm',
'Concealer dengan aplikator micro-cushion yang mudah diaplikasikan. Formula anti-aging dengan goji berry untuk menyamarkan lingkaran hitam dan garis halus.'),

-- 6
('Perfect Cover BB Cream', 'Missha', 185000, 'colorfit.jpeg',
'BB Cream', 'Normal', 'Dewy', 'Neutral',
'BB cream legendaris dengan coverage tinggi dan formula skincare. Mengandung ceramide dan hyaluronic acid yang melembapkan sekaligus memberikan tampilan dewy glowing.'),

-- 7
('Clear Finish Powder', 'Wardah', 65000, 'powerstay.jpeg',
'Powder', 'Berminyak', 'Matte', 'Warm',
'Bedak tabur translucent yang membantu mengontrol minyak berlebih. Formula ringan dengan micro-particles yang menyamarkan pori dan memberikan hasil matte sempurna.'),

-- 8
('Luminous Loose Powder', 'Make Over', 115000, 'sky.jpeg',
'Powder', 'Kering', 'Satin', 'Cool',
'Bedak tabur dengan partikel shimmer halus untuk tampilan glowing. Formula yang tidak mengeringkan kulit dan memberikan efek soft-focus pada wajah.'),

-- 9
('Insta Perfect Primer', 'Wardah', 79000, 'fitme.jpeg',
'Primer', 'Kombinasi', 'Matte', 'Neutral',
'Primer dengan formula blur technology yang menyamarkan pori dan garis halus. Membantu makeup tahan lebih lama dan memberikan base yang smooth.'),

-- 10
('Hydra Blur Primer', 'Make Over', 139000, 'colorfit.jpeg',
'Primer', 'Kering', 'Dewy', 'Warm',
'Primer hydrating dengan hyaluronic acid yang melembapkan kulit sekaligus menciptakan canvas makeup yang sempurna. Efek dewy yang natural.'),

-- 11
('SuperStay Full Coverage Foundation', 'Maybelline', 179000, 'powerstay.jpeg',
'Foundation', 'Normal', 'Matte', 'Cool',
'Foundation full coverage dengan ketahanan hingga 24 jam. Formula transfer-proof dan waterproof. Cocok untuk acara yang membutuhkan makeup tahan seharian.'),

-- 12
('Stay Matte BB Cream', 'Pond''s', 45000, 'sky.jpeg',
'BB Cream', 'Berminyak', 'Matte', 'Warm',
'BB cream dengan formula oil control yang ringan. Memberikan coverage sheer to medium dengan hasil matte. Harga terjangkau untuk penggunaan sehari-hari.'),

-- 13
('Skin Perfecting Concealer', 'Focallure', 55000, 'fitme.jpeg',
'Concealer', 'Normal', 'Satin', 'Neutral',
'Concealer creamy dengan coverage medium to full. Formula yang blendable dan tidak creasing. Cocok untuk menyamarkan bekas jerawat dan noda hitam.'),

-- 14
('Aqua Beauty Liquid Highlighter', 'Wardah', 69000, 'colorfit.jpeg',
'Highlighter', 'Kering', 'Dewy', 'Warm',
'Highlighter cair dengan formula water-based yang ringan. Memberikan efek glowing natural dari dalam. Bisa dicampur dengan foundation atau digunakan langsung.'),

-- 15
('Flawless Matte Cushion', 'Somethinc', 119000, 'powerstay.jpeg',
'Cushion', 'Berminyak', 'Semi-Matte', 'Cool',
'Cushion compact dengan coverage medium to full. Formula oil control dengan niacinamide untuk mencerahkan. Praktis untuk touch up kapan saja.'),

-- 16
('Glow Getter Cushion', 'Somethinc', 129000, 'sky.jpeg',
'Cushion', 'Kering', 'Dewy', 'Warm',
'Cushion dengan efek dewy glowing yang tahan lama. Diperkaya hyaluronic acid dan centella asiatica untuk kulit sensitif dan kering.'),

-- 17
('Invisible Setting Spray', 'Make Over', 125000, 'fitme.jpeg',
'Setting Spray', 'Semua Jenis Kulit', 'Natural', 'Neutral',
'Setting spray ringan yang mengunci makeup hingga 16 jam. Formula invisible yang tidak meninggalkan residu dan menjaga kelembapan kulit.'),

-- 18
('Acne Cover Foundation', 'Wardah', 85000, 'colorfit.jpeg',
'Foundation', 'Sensitif', 'Matte', 'Cool',
'Foundation khusus kulit berjerawat dengan formula non-comedogenic. Mengandung salicylic acid yang membantu mengatasi jerawat. Coverage medium yang buildable.'),

-- 19
('True Match Super-Blendable Foundation', 'L''Oréal', 199000, 'powerstay.jpeg',
'Foundation', 'Normal', 'Satin', 'Warm',
'Foundation dengan 45 shade yang super blendable. Formula dengan micro-fine pearl untuk tampilan natural. Mengandung vitamin B5 dan E untuk perawatan kulit.'),

-- 20
('Banila Co Prime Primer Finish Powder', 'Banila Co', 215000, 'sky.jpeg',
'Powder', 'Kombinasi', 'Semi-Matte', 'Neutral',
'Bedak padat multifungsi yang bisa digunakan sebagai primer dan finishing powder. Formula blur effect yang menyamarkan tekstur kulit dan mengontrol minyak.');
