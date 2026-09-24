<?php
/**
 * Halaman Profil Sekolah
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Profil Sekolah, Visi & Misi";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Banner -->
<section class="py-5 bg-dark text-white position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 20%, #1e293b 0%, #090e1a 100%);">
  <div class="container py-4 text-center position-relative" style="z-index: 2;">
    <span class="badge-glow mb-2"><i class="bi bi-building"></i> TENTANG KAMI</span>
    <h1 class="hero-title mb-2" style="font-size: 2.8rem;">Profil SMK Bangun Nusa Bangsa</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px;">
      Mengenal lebih dekat sejarah, visi, misi, dan nilai-nilai keunggulan yang kami junjung tinggi.
    </p>
  </div>
</section>

<!-- Content Profil -->
<section class="py-5 bg-white">
  <div class="container py-lg-4">
    <!-- Sejarah Singkat -->
    <div class="row align-items-center g-5 mb-5 pb-lg-4">
      <div class="col-lg-6">
        <span class="section-tag">Jejak Langkah</span>
        <h2 class="section-title">Sejarah &amp; Dedikasi Kami</h2>
        <div class="text-secondary" style="font-size: 1.05rem; line-height: 1.8;">
          <?= nl2br(htmlspecialchars($school['sejarah'])) ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card-3d-tilt p-4 p-lg-5 bg-light border-0 shadow-sm text-center">
          <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="p-3 bg-white rounded-4 shadow-sm border d-inline-block">
              <img src="<?= $baseUrl ?>/assets/images/logo-smk.jpg" alt="Logo SMK Bangun Nusa Bangsa" class="img-fluid" style="max-height: 220px; object-fit: contain;">
            </div>
          </div>
          <h4 class="fw-bold mb-1 text-dark">SMK BANGUN NUSA BANGSA</h4>
          <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold mb-3">Cibinong - Bogor</span>
          <p class="text-secondary mb-0 small" style="line-height: 1.7;">
            Pusat Keunggulan Pendidikan Kejuruan yang berdedikasi melahirkan generasi vokasi unggul, terampil, dan siap menghadapi tantangan era industri digital.
          </p>
        </div>
      </div>
    </div>

    <!-- Visi & Misi (3D Cards) -->
    <div class="row g-4 mb-5 perspective-container">
      <div class="col-lg-5">
        <div class="card-3d-tilt" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); color: #fff; border: none;">
          <div class="icon-box-3d" style="background: rgba(255,255,255,0.1); color: #67e8f9; border-color: rgba(255,255,255,0.2);">
            <i class="bi bi-eye-fill"></i>
          </div>
          <h3 class="text-white fw-bold mb-3">Visi Sekolah</h3>
          <p class="text-light opacity-90" style="font-size: 1.1rem; line-height: 1.8;">
            "<?= htmlspecialchars($school['visi']) ?>"
          </p>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card-3d-tilt">
          <div class="icon-box-3d">
            <i class="bi bi-bullseye"></i>
          </div>
          <h3 class="fw-bold mb-3 text-dark">Misi Sekolah</h3>
          <div class="text-secondary" style="line-height: 1.8;">
            <?= nl2br(htmlspecialchars($school['misi'])) ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Fasilitas Sekolah (Grid Cards) -->
    <div class="mt-5 pt-4">
      <div class="text-center mb-5">
        <span class="section-tag">Sarana &amp; Prasarana</span>
        <h2 class="section-title">Fasilitas Unggulan Kampus</h2>
        <p class="section-subtitle">
          Didukung sarana modern berstandar industri untuk mendukung proses belajar mengajar optimal.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-display fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Smart Computer Lab</h5>
            <p class="text-secondary small mb-0">Laboratorium komputer berspesifikasi tinggi untuk pemrograman, game development, dan simulator jaringan.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-camera-reels fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Studio Multimedia &amp; Podcast</h5>
            <p class="text-secondary small mb-0">Fasilitas fotografi, editing video 4K, audio mixing, dan green screen untuk siswa DKV &amp; konten kreator.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-briefcase fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Teaching Factory &amp; Business Center</h5>
            <p class="text-secondary small mb-0">Ruang simulasi industri di mana siswa mengerjakan pesanan software, desain, dan layanan bisnis riil.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-book fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Digital Library &amp; Co-Working</h5>
            <p class="text-secondary small mb-0">Perpustakaan digital terintegrasi ribuan e-book serta area belajar kelompok ber-AC yang nyaman.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-dribbble fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Sport Center &amp; Aula Serbaguna</h5>
            <p class="text-secondary small mb-0">Lapangan futsal, basket, voli, dan gedung serbaguna untuk kegiatan pentas seni dan upacara.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light border h-100">
            <i class="bi bi-moon-stars fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Masjid Sekolah Al-Barokah</h5>
            <p class="text-secondary small mb-0">Pusat pembinaan kerohanian, shalat berjamaah, tahfidz Quran, dan kajian keagamaan berkala.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
