<?php
$pageTitle = 'Tentang Sistem';
include 'includes/koneksi.php';
include 'includes/header.php';
?>

<!-- ABOUT -->
<div class="about-wrapper">
    <div class="about-card">
        <div class="about-icon">✦</div>
        <h1>Tentang Make A Match</h1>
        <p>
            Make A Match adalah sistem rekomendasi produk makeup yang menggunakan metode
            <strong>Content-Based Filtering (CBF)</strong> dengan teknik
            <strong>TF-IDF Vectorization</strong> dan <strong>Cosine Similarity</strong>
            untuk menghasilkan rekomendasi yang relevan berdasarkan kemiripan atribut produk.
        </p>

        <div class="about-divider"></div>

        <h3 class="about-subtitle">Cara Kerja Sistem</h3>
        <div class="about-steps">
            <div class="step">
                <div class="step-num">01</div>
                <h4>Representasi Konten</h4>
                <p>Setiap produk direpresentasikan sebagai dokumen teks yang berisi: nama, brand, kategori, jenis kulit, finish type, tone, dan deskripsi.</p>
            </div>
            <div class="step">
                <div class="step-num">02</div>
                <h4>TF-IDF Vectorization</h4>
                <p>Dokumen diubah menjadi vektor numerik. TF menghitung frekuensi kata dalam dokumen, IDF menurunkan bobot kata yang muncul di semua dokumen.</p>
            </div>
            <div class="step">
                <div class="step-num">03</div>
                <h4>Cosine Similarity</h4>
                <p>Kemiripan antar vektor dihitung menggunakan cosine similarity. Skor 1.0 = identik, 0.0 = tidak ada kesamaan.</p>
            </div>
            <div class="step">
                <div class="step-num">04</div>
                <h4>Ranking & Rekomendasi</h4>
                <p>Produk diurutkan berdasarkan skor similarity tertinggi. Top-N produk ditampilkan sebagai rekomendasi.</p>
            </div>
        </div>

        <div class="about-divider"></div>

        <h3 class="about-subtitle">Metode yang Digunakan</h3>
        <div class="about-method">
            <div class="method-card">
                <h4>Content-Based Filtering (CBF)</h4>
                <p>Metode rekomendasi yang menganalisis atribut konten item untuk menemukan kemiripan. Keunggulan: tidak memerlukan data pengguna lain (cold-start friendly), rekomendasi transparan dan dapat dijelaskan.</p>
            </div>
            <div class="method-card">
                <h4>TF-IDF (Term Frequency — Inverse Document Frequency)</h4>
                <p>Teknik text mining dari Information Retrieval. <strong>TF</strong> = berapa kali kata muncul dalam dokumen, dibagi total kata. <strong>IDF</strong> = log(total dokumen / dokumen yang mengandung kata tersebut). Kata umum seperti "dan", "yang" mendapat bobot rendah.</p>
            </div>
            <div class="method-card">
                <h4>Cosine Similarity</h4>
                <p>Mengukur kesamaan antara dua vektor dengan menghitung cosinus sudut di antara keduanya: <code>sim(A,B) = (A·B) / (|A| × |B|)</code>. Rentang nilai: 0 (tidak mirip) hingga 1 (identik).</p>
            </div>
        </div>

        <div class="about-divider"></div>

        <h3 class="about-subtitle">Atribut Produk yang Dianalisis</h3>
        <div class="about-attrs-grid">
            <div class="about-attr">
                <strong>Kategori</strong>
                <span>Foundation, BB Cream, Concealer, Powder, Primer, Cushion, dll.</span>
            </div>
            <div class="about-attr">
                <strong>Jenis Kulit</strong>
                <span>Berminyak, Kering, Normal, Kombinasi, Sensitif</span>
            </div>
            <div class="about-attr">
                <strong>Finish Type</strong>
                <span>Matte, Dewy, Satin, Natural, Semi-Matte</span>
            </div>
            <div class="about-attr">
                <strong>Tone</strong>
                <span>Warm, Cool, Neutral, Olive</span>
            </div>
        </div>

        <div class="about-tags">
            <span class="about-tag">Content-Based Filtering</span>
            <span class="about-tag">TF-IDF</span>
            <span class="about-tag">Cosine Similarity</span>
            <span class="about-tag">PHP & MySQL</span>
            <span class="about-tag">Information Retrieval</span>
        </div>

        <p class="about-footer-text">
            Website ini dikembangkan sebagai bagian dari penelitian skripsi tentang sistem rekomendasi makeup.
        </p>

        <div style="margin-top: 32px;">
            <a href="index.php" class="btn-back">← Kembali ke Produk</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
