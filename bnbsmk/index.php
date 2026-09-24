<?php
/**
 * Beranda Utama
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Beranda | SMK Pusat Keunggulan Berbasis Teknologi & Industri";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Fetch Jurusan
$jurusanStmt = $pdo->query("SELECT * FROM jurusan ORDER BY urutan ASC LIMIT 6");
$daftarJurusan = $jurusanStmt->fetchAll();

// Fetch Latest Articles with category and comment count
$articleQuery = "
  SELECT a.*, c.nama_kategori, c.slug AS cat_slug, u.nama_lengkap AS author_name,
         (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id AND cm.status = 'approved') AS total_comments
  FROM articles a
  LEFT JOIN categories c ON a.category_id = c.id
  LEFT JOIN users u ON a.user_id = u.id
  WHERE a.status = 'published'
  ORDER BY a.created_at DESC
  LIMIT 3
";
$articles = $pdo->query($articleQuery)->fetchAll();
?>

<!-- 1. HERO SECTION WITH THREE.JS 3D CANVAS -->
<section class="hero-section">
  <div id="threejs-hero-canvas"></div>
  <div class="container hero-content">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div class="badge-glow mb-2">
          <i class="bi bi-stars"></i> SMK PUSAT KEUNGGULAN &amp; DIGITAL EXCELLENCE
        </div>
        <h1 class="hero-title">
          Membangun Generasi Vokasi <span style="background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Masa Depan</span>
        </h1>
        <p class="hero-desc">
          <?= htmlspecialchars($school['slogan']) ?>. Memadukan kurikulum industri modern, penguasaan AI &amp; Software Engineering, serta pembentukan karakter luhur.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?= $baseUrl ?>/jurusan.php" class="btn btn-bnb-primary">
            <i class="bi bi-compass"></i> Jelajahi Program Keahlian
          </a>
          <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-bnb-outline">
            <i class="bi bi-newspaper"></i> Baca Warta &amp; Berita
          </a>
        </div>
      </div>

      <!-- 3D Interactive Floating Card -->
      <div class="col-lg-5">
        <div class="hero-floating-card-3d">
          <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3">
              <div class="brand-badge-3d" style="width: 42px; height: 42px;">
                <i class="bi bi-award-fill"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 fw-bold">Akreditasi "A" Unggul</h6>
                <small class="text-info">Kemendikbudristek RI</small>
              </div>
            </div>
            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-2 rounded-pill">
              <i class="bi bi-patch-check-fill me-1"></i> Terakreditasi
            </span>
          </div>

          <!-- Quick Stats Grid inside 3D Card -->
          <div class="row g-3">
            <div class="col-6">
              <div class="hero-stat-box">
                <h3 class="text-white fw-bold mb-1" style="font-size: 1.8rem; font-family: 'Outfit';">98.4%</h3>
                <small class="text-white-50">Terserap Kerja &amp; Wirausaha</small>
              </div>
            </div>
            <div class="col-6">
              <div class="hero-stat-box">
                <h3 class="text-white fw-bold mb-1" style="font-size: 1.8rem; font-family: 'Outfit';">45+</h3>
                <small class="text-white-50">Mitra Industri Nasional</small>
              </div>
            </div>
            <div class="col-6">
              <div class="hero-stat-box">
                <h3 class="text-white fw-bold mb-1" style="font-size: 1.8rem; font-family: 'Outfit';">3</h3>
                <small class="text-white-50">Kompetensi Keahlian Unggulan</small>
              </div>
            </div>
            <div class="col-6">
              <div class="hero-stat-box">
                <h3 class="text-white fw-bold mb-1" style="font-size: 1.8rem; font-family: 'Outfit';">100%</h3>
                <small class="text-white-50">Teaching Factory Berbasis Produk</small>
              </div>
            </div>
          </div>

          <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <span class="text-white-50 small"><i class="bi bi-info-circle me-1 text-info"></i> PPDB 2026 Dibuka</span>
            <a href="<?= $baseUrl ?>/artikel.php?kategori=info-ppdb-beasiswa" class="text-info fw-bold text-decoration-none small">
              Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 2. SAMBUTAN KEPALA SEKOLAH -->
<section class="py-5 bg-white border-bottom">
  <div class="container py-lg-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-4 text-center">
        <div class="card-3d-tilt d-inline-block p-3" style="max-width: 320px;">
          <img src="<?= getImageUrl($school['foto_kepsek'], 'kepsek.png') ?>" alt="Kepala Sekolah" class="img-fluid rounded-4 shadow-sm mb-3" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; object-position: top;">
          <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($school['nama_kepsek']) ?></h5>
          <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold">Kepala SMK Bangun Nusa Bangsa</span>
        </div>
      </div>
      <div class="col-lg-8">
        <span class="section-tag">Sambutan Pimpinan</span>
        <h2 class="section-title">Selamat Datang di Portal Resmi SMK Bangun Nusa Bangsa</h2>
        <div class="text-secondary" style="font-size: 1.05rem; line-height: 1.8;">
          <?= nl2br(htmlspecialchars($school['sambutan_kepsek'])) ?>
        </div>
        <div class="mt-4 pt-2">
          <a href="<?= $baseUrl ?>/profil.php" class="btn btn-outline-primary fw-bold px-4 py-2 rounded-3">
            <i class="bi bi-eye me-1"></i> Baca Profil &amp; Sejarah Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3. PROGRAM KEAHLIAN / JURUSAN (3D TILT CARDS) -->
<section class="py-5" style="background: #f1f5f9;">
  <div class="container py-lg-4">
    <div class="text-center mb-5">
      <span class="section-tag">Kompetensi Keahlian</span>
      <h2 class="section-title">Pilihan Program Keahlian Masa Depan</h2>
      <p class="section-subtitle">
        Dirancang khusus menyesuaikan kebutuhan revolusi industri 4.0 dan era digital modern, siap kerja dan berdaya saing tinggi.
      </p>
    </div>

    <div class="row g-4 perspective-container">
      <?php foreach ($daftarJurusan as $j): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card-3d-tilt">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="p-2 bg-white rounded-3 shadow-sm border d-inline-flex align-items-center justify-content-center" style="width: 75px; height: 75px;">
                <img src="<?= getImageUrl($j['gambar']) ?>" alt="Logo <?= htmlspecialchars($j['nama_jurusan']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
              </div>
              <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill fs-6">
                <?= htmlspecialchars($j['kode']) ?>
              </span>
            </div>
            <h4 class="fw-bold mb-2 text-dark"><?= htmlspecialchars($j['nama_jurusan']) ?></h4>
            <p class="text-secondary small flex-grow-1 mb-4" style="line-height: 1.7;">
              <?= htmlspecialchars($j['deskripsi_singkat']) ?>
            </p>
            <div class="pt-3 border-top border-light-subtle d-flex align-items-center justify-content-between">
              <a href="<?= $baseUrl ?>/jurusan.php#<?= urlencode($j['slug']) ?>" class="fw-bold text-primary text-decoration-none small">
                Detail Keahlian &amp; Karir <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 4. ARTIKEL & WARTA TERBARU (MAIN FEATURE WITH 3D HOVER CARDS) -->
<section class="py-5 bg-white">
  <div class="container py-lg-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
      <div>
        <span class="section-tag">Warta &amp; Inspirasi</span>
        <h2 class="section-title mb-0">Artikel &amp; Kegiatan Terbaru</h2>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-bnb-primary">
          <i class="bi bi-grid me-1"></i> Lihat Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="row g-4">
      <?php if (empty($articles)): ?>
        <div class="col-12 text-center py-5 text-muted">
          <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
          <p>Belum ada artikel yang dipublikasikan.</p>
        </div>
      <?php else: ?>
        <?php foreach ($articles as $art): ?>
          <div class="col-lg-4 col-md-6">
            <article class="article-card">
              <div class="article-thumbnail-wrapper">
                <img src="<?= getImageUrl($art['gambar_sampul']) ?>" alt="<?= htmlspecialchars($art['judul']) ?>" class="article-thumbnail">
                <span class="article-category-badge">
                  <i class="bi bi-tag-fill me-1 text-info"></i> <?= htmlspecialchars($art['nama_kategori']) ?>
                </span>
              </div>
              <div class="article-body">
                <div class="article-meta">
                  <span><i class="bi bi-calendar3"></i> <?= formatTanggalIndo($art['created_at']) ?></span>
                  <span><i class="bi bi-eye"></i> <?= $art['views'] ?> views</span>
                  <span><i class="bi bi-chat-dots"></i> <?= $art['total_comments'] ?></span>
                </div>
                <h3 class="article-title">
                  <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>">
                    <?= htmlspecialchars($art['judul']) ?>
                  </a>
                </h3>
                <p class="article-excerpt">
                  <?= htmlspecialchars(mb_strimwidth($art['ringkasan'], 0, 115, "...")) ?>
                </p>
                <div class="pt-3 border-top border-light-subtle d-flex align-items-center justify-content-between mt-auto">
                  <span class="small text-muted"><i class="bi bi-clock me-1"></i> <?= estimateReadingTime($art['konten']) ?> min baca</span>
                  <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="fw-bold text-primary text-decoration-none small">
                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 5. KEUNGGULAN 3D HIGHLIGHT BANNER -->
<section class="py-5" style="background: radial-gradient(circle at 50% 50%, #1e293b 0%, #0f172a 100%); color: #fff;">
  <div class="container py-lg-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 px-3 py-2 rounded-pill fw-bold mb-3">
          <i class="bi bi-trophy-fill me-1"></i> Kenapa Memilih SMK BNB?
        </span>
        <h2 class="text-white fw-bold mb-4" style="font-size: 2.4rem;">Pendidikan Vokasi Bertaraf Global dengan Pendekatan Praktis</h2>
        <div class="d-flex flex-column gap-3 mb-4">
          <div class="d-flex gap-3 align-items-start">
            <div class="brand-badge-3d mt-1" style="width: 38px; height: 38px; font-size: 1.1rem; flex-shrink: 0;">
              <i class="bi bi-cpu"></i>
            </div>
            <div>
              <h6 class="text-white fw-bold mb-1">Laboratorium Canggih &amp; AI-Ready</h6>
              <p class="text-white-50 small mb-0">Dilengkapi perangkat komputer spesifikasi tinggi, studio multimedia, dan simulator jaringan enterprise.</p>
            </div>
          </div>
          <div class="d-flex gap-3 align-items-start">
            <div class="brand-badge-3d mt-1" style="width: 38px; height: 38px; font-size: 1.1rem; flex-shrink: 0;">
              <i class="bi bi-award"></i>
            </div>
            <div>
              <h6 class="text-white fw-bold mb-1">Sertifikasi BNSP &amp; Vendor Internasional</h6>
              <p class="text-white-50 small mb-0">Lulusan dibekali sertifikasi kompetensi resmi seperti Mikrotik, Cisco, Adobe Certified, dan LSP-P1 BNSP.</p>
            </div>
          </div>
          <div class="d-flex gap-3 align-items-start">
            <div class="brand-badge-3d mt-1" style="width: 38px; height: 38px; font-size: 1.1rem; flex-shrink: 0;">
              <i class="bi bi-people"></i>
            </div>
            <div>
              <h6 class="text-white fw-bold mb-1">Bursa Kerja Khusus (BKK) Aktif</h6>
              <p class="text-white-50 small mb-0">Penyaluran langsung ke puluhan perusahaan mitra industri, magang bersertifikat, dan job fair tahunan.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <div class="hero-floating-card-3d p-4 text-start">
          <h4 class="text-white fw-bold mb-3"><i class="bi bi-megaphone-fill text-warning me-2"></i> Pendaftaran Siswa Baru 2026</h4>
          <p class="text-white-50 small mb-4">
            Dapatkan kesempatan beasiswa pendidikan penuh dan potongan biaya pembangunan gelombang awal. Kuota setiap program keahlian terbatas!
          </p>
          <div class="d-grid gap-2">
            <a href="<?= $baseUrl ?>/artikel.php?kategori=info-ppdb-beasiswa" class="btn btn-bnb-primary">
              <i class="bi bi-pencil-square me-1"></i> Informasi Lengkap PPDB 2026
            </a>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $school['whatsapp']) ?>" target="_blank" class="btn btn-bnb-outline">
              <i class="bi bi-whatsapp me-1 text-success"></i> Konsultasi via WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
