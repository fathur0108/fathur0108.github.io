<?php
/**
 * Global Footer Component
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/functions.php';
$school = getSchoolProfile();
$baseUrl = getBaseUrl();

// Fetch dynamic categories
$pdo = getDBConnection();
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY nama_kategori ASC LIMIT 5");
$footerCategories = $catStmt->fetchAll();

// Fetch latest 2 articles
$latestArticleStmt = $pdo->query("SELECT judul, slug, created_at, gambar_sampul FROM articles WHERE status='published' ORDER BY created_at DESC LIMIT 2");
$footerArticles = $latestArticleStmt->fetchAll();
?>
  <!-- Footer (Dark 3D & Modern Glass Accents) -->
  <footer class="footer-bnb">
    <div class="container">
      <div class="row g-5">
        <!-- Col 1: About School -->
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="<?= $baseUrl ?>/assets/images/logo-smk.jpg" alt="Logo SMK Bangun Nusa Bangsa" style="height: 52px; width: auto; object-fit: contain;" class="rounded-2 p-1 bg-white shadow-sm">
            <div>
              <h5 class="mb-0 text-white" style="padding-bottom: 0;"><?= htmlspecialchars($school['nama_sekolah']) ?></h5>
              <small class="text-info fw-bold">Cibinong - Bogor</small>
            </div>
          </div>
          <p class="text-secondary small pe-lg-3" style="line-height: 1.7;">
            <?= htmlspecialchars($school['slogan']) ?>. Memadukan keunggulan teknologi, kurikulum berstandar industri, dan penanaman akhlak mulia.
          </p>
          <div class="d-flex gap-2 mt-4">
            <a href="<?= htmlspecialchars($school['instagram']) ?>" target="_blank" class="social-btn-3d" title="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="<?= htmlspecialchars($school['facebook']) ?>" target="_blank" class="social-btn-3d" title="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="<?= htmlspecialchars($school['youtube']) ?>" target="_blank" class="social-btn-3d" title="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $school['whatsapp']) ?>" target="_blank" class="social-btn-3d" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>

        <!-- Col 2: Program Keahlian & Kategori -->
        <div class="col-lg-2 col-md-6 col-6">
          <h5>Kategori Artikel</h5>
          <ul class="footer-links small">
            <?php foreach ($footerCategories as $fCat): ?>
              <li>
                <a href="<?= $baseUrl ?>/artikel.php?kategori=<?= urlencode($fCat['slug']) ?>">
                  <i class="bi bi-chevron-right text-info"></i> <?= htmlspecialchars($fCat['nama_kategori']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Col 3: Quick Navigation -->
        <div class="col-lg-2 col-md-6 col-6">
          <h5>Tautan Cepat</h5>
          <ul class="footer-links small">
            <li><a href="<?= $baseUrl ?>/index.php"><i class="bi bi-chevron-right text-info"></i> Beranda</a></li>
            <li><a href="<?= $baseUrl ?>/profil.php"><i class="bi bi-chevron-right text-info"></i> Visi &amp; Misi</a></li>
            <li><a href="<?= $baseUrl ?>/jurusan.php"><i class="bi bi-chevron-right text-info"></i> Program Keahlian</a></li>
            <li><a href="<?= $baseUrl ?>/artikel.php"><i class="bi bi-chevron-right text-info"></i> Berita Terkini</a></li>
            <li><a href="<?= $baseUrl ?>/kontak.php"><i class="bi bi-chevron-right text-info"></i> Hubungi Kami</a></li>
            <li><a href="<?= $baseUrl ?>/login.php"><i class="bi bi-chevron-right text-info"></i> Portal Admin</a></li>
          </ul>
        </div>

        <!-- Col 4: Kontak & Alamat -->
        <div class="col-lg-4 col-md-6">
          <h5>Kontak &amp; Alamat</h5>
          <ul class="list-unstyled text-secondary small mb-3" style="line-height: 2;">
            <li class="d-flex gap-2">
              <i class="bi bi-geo-alt-fill text-info mt-1 fs-6"></i>
              <span><?= htmlspecialchars($school['alamat']) ?></span>
            </li>
            <li class="d-flex gap-2">
              <i class="bi bi-telephone-fill text-info mt-1 fs-6"></i>
              <span><?= htmlspecialchars($school['telepon']) ?></span>
            </li>
            <li class="d-flex gap-2">
              <i class="bi bi-envelope-fill text-info mt-1 fs-6"></i>
              <span><?= htmlspecialchars($school['email']) ?></span>
            </li>
            <li class="d-flex gap-2">
              <i class="bi bi-clock-fill text-info mt-1 fs-6"></i>
              <span>Senin - Jumat: 07.00 - 16.00 WIB</span>
            </li>
          </ul>
        </div>
      </div>

      <hr class="border-secondary border-opacity-25 my-4">

      <div class="row align-items-center small text-secondary">
        <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
          &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($school['nama_sekolah']) ?></strong>. All rights reserved.
        </div>
        <div class="col-md-6 text-center text-md-end">
          <span>Dikembangkan dengan <i class="bi bi-heart-fill text-danger mx-1"></i> PHP Native, Bootstrap 5 &amp; 3D Interactive Tech.</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Three.js CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <!-- Vanilla Tilt CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom 3D Hero Script -->
  <script src="<?= $baseUrl ?>/assets/js/3d-hero.js"></script>
  <!-- Main Script -->
  <script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>
