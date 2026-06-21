<?php
/**
 * Make A Match — Content-Based Filtering Engine
 * Teknik: TF-IDF Vectorization + Cosine Similarity
 *
 * Alur:
 * 1. Gabungkan atribut produk menjadi satu dokumen teks
 * 2. Hitung TF (Term Frequency) untuk setiap term
 * 3. Hitung IDF (Inverse Document Frequency) dari seluruh corpus
 * 4. Hitung TF-IDF vector untuk setiap produk
 * 5. Hitung Cosine Similarity antara produk target dengan semua produk lain
 * 6. Return top-N rekomendasi
 */

class CBEngine {

    private $conn;
    private $stopwords = [];

    public function __construct($conn) {
        $this->conn = $conn;
        // Stopwords bahasa Indonesia — kata umum yang tidak bermakna
        $this->stopwords = [
            'yang', 'dan', 'di', 'dengan', 'untuk', 'pada', 'ke', 'dari',
            'ini', 'itu', 'adalah', 'akan', 'oleh', 'juga', 'tidak', 'bisa',
            'ada', 'dalam', 'lebih', 'sudah', 'atau', 'serta', 'seperti',
            'namun', 'karena', 'telah', 'masih', 'agar', 'dapat', 'hanya',
            'setelah', 'sebelum', 'antara', 'sampai', 'tanpa', 'tentang',
            'melalui', 'sebagai', 'memiliki', 'menggunakan', 'memberikan',
            'membantu', 'cocok', 'formula', 'dan', 'yang'
        ];
    }

    /**
     * Tokenisasi teks → array kata lowercase tanpa simbol
     */
    private function tokenize($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        $words = preg_split('/\s+/', trim($text));
        return array_filter($words, function($w) {
            return strlen($w) > 1 && !in_array($w, $this->stopwords);
        });
    }

    /**
     * Gabungkan atribut produk menjadi satu dokumen teks
     */
    private function buildDocument($row) {
        $parts = [
            $row['nama_produk'],
            $row['brand'],
            $row['kategori'],
            $row['jenis_kulit'],
            $row['finish_type'],
            $row['tone'],
            $row['deskripsi']
        ];
        return implode(' ', $parts);
    }

    /**
     * Hitung TF (Term Frequency) dari array token
     */
    private function computeTF($tokens) {
        $tf = [];
        $total = count($tokens);
        if ($total === 0) return $tf;

        foreach ($tokens as $term) {
            if (!isset($tf[$term])) $tf[$term] = 0;
            $tf[$term]++;
        }
        // Normalisasi: frekuensi / total kata
        foreach ($tf as $term => $count) {
            $tf[$term] = $count / $total;
        }
        return $tf;
    }

    /**
     * Hitung IDF (Inverse Document Frequency) dari seluruh corpus
     */
    private function computeIDF($corpus) {
        $idf = [];
        $N = count($corpus);

        // Hitung berapa dokumen mengandung setiap term
        $docFreq = [];
        foreach ($corpus as $tokens) {
            $unique = array_unique($tokens);
            foreach ($unique as $term) {
                if (!isset($docFreq[$term])) $docFreq[$term] = 0;
                $docFreq[$term]++;
            }
        }

        // IDF = log(N / df) — smooth untuk hindari div by zero
        foreach ($docFreq as $term => $df) {
            $idf[$term] = log(($N + 1) / ($df + 1)) + 1;
        }
        return $idf;
    }

    /**
     * Hitung TF-IDF vector
     */
    private function computeTFIDF($tf, $idf) {
        $tfidf = [];
        foreach ($tf as $term => $tfVal) {
            $idfVal = isset($idf[$term]) ? $idf[$term] : 1;
            $tfidf[$term] = $tfVal * $idfVal;
        }
        return $tfidf;
    }

    /**
     * Cosine Similarity antara dua vektor
     */
    private function cosineSimilarity($vecA, $vecB) {
        // Kumpulkan semua terms
        $allTerms = array_unique(array_merge(array_keys($vecA), array_keys($vecB)));

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($allTerms as $term) {
            $a = isset($vecA[$term]) ? $vecA[$term] : 0;
            $b = isset($vecB[$term]) ? $vecB[$term] : 0;
            $dotProduct += $a * $b;
            $magnitudeA += $a * $a;
            $magnitudeB += $b * $b;
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0 || $magnitudeB == 0) return 0;

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    /**
     * Ambil rekomendasi untuk produk tertentu
     *
     * @param int $productId ID produk target
     * @param int $limit     Jumlah rekomendasi (default 5)
     * @return array         Array produk rekomendasi dengan skor similarity
     */
    public function getRecommendations($productId, $limit = 5) {
        // 1. Ambil semua produk dari database
        $query = mysqli_query($this->conn, "SELECT * FROM produk ORDER BY id ASC");
        $allProducts = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $allProducts[] = $row;
        }

        if (count($allProducts) < 2) return [];

        // 2. Bangun dokumen dan tokenisasi untuk semua produk
        $documents = [];
        $tokenized = [];
        foreach ($allProducts as $product) {
            $doc = $this->buildDocument($product);
            $tokens = $this->tokenize($doc);
            $documents[] = $doc;
            $tokenized[] = $tokens;
        }

        // 3. Hitung IDF dari seluruh corpus
        $idf = $this->computeIDF($tokenized);

        // 4. Hitung TF-IDF vector untuk setiap produk
        $tfidfVectors = [];
        foreach ($tokenized as $tokens) {
            $tf = $this->computeTF($tokens);
            $tfidfVectors[] = $this->computeTFIDF($tf, $idf);
        }

        // 5. Cari index produk target
        $targetIndex = -1;
        foreach ($allProducts as $i => $product) {
            if ($product['id'] == $productId) {
                $targetIndex = $i;
                break;
            }
        }

        if ($targetIndex === -1) return [];

        // 6. Hitung cosine similarity dengan semua produk lain
        $similarities = [];
        for ($i = 0; $i < count($allProducts); $i++) {
            if ($i === $targetIndex) continue;

            $score = $this->cosineSimilarity(
                $tfidfVectors[$targetIndex],
                $tfidfVectors[$i]
            );

            $similarities[] = [
                'product' => $allProducts[$i],
                'score' => round($score, 4)
            ];
        }

        // 7. Sort descending berdasarkan skor
        usort($similarities, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 8. Return top N
        return array_slice($similarities, 0, $limit);
    }

    /**
     * Ambil rekomendasi berdasarkan preferensi user (multi-atribut)
     *
     * @param array $preferences ['kategori' => 'Foundation', 'jenis_kulit' => 'Berminyak', ...]
     * @param int $limit
     * @return array
     */
    public function getRecommendationsByPreferences($preferences, $limit = 5) {
        // Buat dokumen query dari preferensi
        $queryDoc = implode(' ', array_values($preferences));
        $queryTokens = $this->tokenize($queryDoc);

        // Ambil semua produk
        $query = mysqli_query($this->conn, "SELECT * FROM produk ORDER BY id ASC");
        $allProducts = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $allProducts[] = $row;
        }

        if (count($allProducts) === 0) return [];

        // Bangun corpus
        $tokenized = [];
        foreach ($allProducts as $product) {
            $doc = $this->buildDocument($product);
            $tokenized[] = $this->tokenize($doc);
        }

        // Tambahkan query ke corpus untuk perhitungan IDF
        $fullCorpus = $tokenized;
        $fullCorpus[] = $queryTokens;
        $idf = $this->computeIDF($fullCorpus);

        // TF-IDF query
        $queryTF = $this->computeTF($queryTokens);
        $queryTFIDF = $this->computeTFIDF($queryTF, $idf);

        // Hitung similarity
        $results = [];
        foreach ($allProducts as $i => $product) {
            $tf = $this->computeTF($tokenized[$i]);
            $tfidf = $this->computeTFIDF($tf, $idf);
            $score = $this->cosineSimilarity($queryTFIDF, $tfidf);

            $results[] = [
                'product' => $product,
                'score' => round($score, 4)
            ];
        }

        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($results, 0, $limit);
    }
}
