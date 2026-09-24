<?php
/**
 * Halaman Program Keahlian / Jurusan
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Program Keahlian & Jurusan Unggulan";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();
$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY urutan ASC")->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 20%, #1e293b 0%, #090e1a 100%);">
  <div class="container py-4 text-center position-relative" style="z-index: 2;">
    <span class="badge-glow mb-2"><i class="bi bi-mortarboard-fill"></i> PENDIDIKAN VOKASI</span>
    <h1 class="hero-title mb-2" style="font-size: 2.8rem;">Program Keahlian Unggulan</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px;">
      Kurikulum berbasis industri, sertifikasi kompetensi resmi, dan jaminan kesiapan kerja di era digital.
    </p>
  </div>
</section>

<!-- Jurusan Details List -->
<section class="py-5 bg-white">
  <div class="container py-lg-4">
    <div class="row g-5">
      <?php foreach ($jurusanList as $index => $j): ?>
        <div class="col-12" id="<?= htmlspecialchars($j['slug']) ?>">
          <div class="card-3d-tilt p-4 p-lg-5 <?= ($index % 2 === 1) ? 'bg-light border' : '' ?>">
            <div class="row align-items-center g-4">
              <div class="col-lg-3 text-center">
                <div class="p-3 bg-white rounded-4 shadow-sm border d-inline-block mb-3">
                  <img src="<?= getImageUrl($j['gambar']) ?>" alt="Logo <?= htmlspecialchars($j['nama_jurusan']) ?>" class="img-fluid" style="max-height: 150px; max-width: 150px; object-fit: contain;">
                </div>
                <div>
                  <span class="badge bg-primary text-white fs-6 px-4 py-2 rounded-pill shadow-sm">
                    <?= htmlspecialchars($j['kode']) ?>
                  </span>
                </div>
              </div>
              <div class="col-lg-9">
                <h3 class="fw-bold text-dark mb-2"><?= htmlspecialchars($j['nama_jurusan']) ?></h3>
                <p class="text-secondary mb-4" style="font-size: 1.05rem; line-height: 1.8;">
                  <?= htmlspecialchars($j['deskripsi_singkat']) ?>
                </p>

                <div class="row g-4 pt-3 border-top border-light-subtle">
                  <!-- Kompetensi -->
                  <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Materi &amp; Kompetensi Utama:</h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.7;">
                      <?= htmlspecialchars($j['kompetensi']) ?>
                    </p>
                  </div>
                  <!-- Prospek Karir -->
                  <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-briefcase-fill text-info me-2"></i> Peluang &amp; Prospek Karir:</h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.7;">
                      <?= htmlspecialchars($j['prospek_karir']) ?>
                    </p>
                  </div>
                </div>

                <div class="mt-4 pt-3 d-flex flex-wrap gap-2">
                  <a href="<?= $baseUrl ?>/kontak.php" class="btn btn-outline-primary btn-sm px-3 py-2 fw-bold">
                    <i class="bi bi-question-circle me-1"></i> Tanya Info Jurusan Ini
                  </a>
                  <a href="<?= $baseUrl ?>/artikel.php?kategori=info-ppdb-beasiswa" class="btn btn-bnb-primary btn-sm px-3 py-2">
                    <i class="bi bi-pencil-square me-1"></i> Daftar PPDB 2026
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
