<?php
/**
 * Global Header Component
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/functions.php';

$school = getSchoolProfile();
$baseUrl = getBaseUrl();

// Current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = isset($pageTitle) ? $pageTitle . " | " . $school['nama_sekolah'] : $school['nama_sekolah'] . " - Official Portal";
$pageDesc = isset($pageDesc) ? $pageDesc : $school['slogan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
  <link rel="icon" type="image/jpeg" href="<?= $baseUrl ?>/assets/images/logo-smk.jpg">

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom Modern 3D Style -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>

  <!-- Top Announcement Bar -->
  <div class="bg-dark text-white py-2 border-bottom border-secondary border-opacity-25 d-none d-md-block" style="font-size: 0.85rem; background: #080d1a !important;">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-4">
        <span><i class="bi bi-geo-alt text-cyan me-1 text-info"></i> <?= htmlspecialchars($school['alamat']) ?></span>
        <span><i class="bi bi-telephone text-cyan me-1 text-info"></i> <?= htmlspecialchars($school['telepon']) ?></span>
        <span><i class="bi bi-envelope text-cyan me-1 text-info"></i> <?= htmlspecialchars($school['email']) ?></span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="<?= htmlspecialchars($school['instagram']) ?>" target="_blank" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-instagram"></i></a>
        <a href="<?= htmlspecialchars($school['facebook']) ?>" target="_blank" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-facebook"></i></a>
        <a href="<?= htmlspecialchars($school['youtube']) ?>" target="_blank" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-youtube"></i></a>
        <span class="text-secondary opacity-50">|</span>
        <?php if (isLoggedIn()): ?>
          <a href="<?= $baseUrl ?>/admin/index.php" class="badge bg-primary text-decoration-none px-2 py-1"><i class="bi bi-speedometer2 me-1"></i> Dashboard Admin</a>
        <?php else: ?>
          <a href="<?= $baseUrl ?>/login.php" class="text-white-50 text-decoration-none"><i class="bi bi-shield-lock me-1"></i> Login Admin</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Main Navigation (Glassmorphism & 3D Brand Badge) -->
  <nav class="navbar navbar-expand-lg navbar-bnb sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-3" href="<?= $baseUrl ?>/index.php">
        <img src="<?= $baseUrl ?>/assets/images/logo-smk.jpg" alt="Logo SMK Bangun Nusa Bangsa" style="height: 46px; width: auto; object-fit: contain;" class="rounded-2 shadow-sm">
        <div>
          <span style="font-size: 1.15rem; font-weight: 800; color: #0f172a; line-height: 1.2; display: block;">SMK BANGUN NUSA BANGSA</span>
          <small class="d-block text-muted" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.05em;">CIBINONG - BOGOR</small>
        </div>
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-dismiss="false" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-1 text-dark"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php">
              <i class="bi bi-house-door me-1 d-lg-none"></i> Beranda
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'profil' ? 'active' : '' ?>" href="<?= $baseUrl ?>/profil.php">
              <i class="bi bi-building me-1 d-lg-none"></i> Profil Sekolah
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'jurusan' ? 'active' : '' ?>" href="<?= $baseUrl ?>/jurusan.php">
              <i class="bi bi-grid-fill me-1 d-lg-none"></i> Program Keahlian
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['artikel', 'artikel-detail']) ? 'active' : '' ?>" href="<?= $baseUrl ?>/artikel.php">
              <i class="bi bi-newspaper me-1 d-lg-none"></i> Artikel &amp; Berita
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'kontak' ? 'active' : '' ?>" href="<?= $baseUrl ?>/kontak.php">
              <i class="bi bi-chat-dots me-1 d-lg-none"></i> Kontak
            </a>
          </li>
          <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
            <a href="<?= $baseUrl ?>/artikel.php?kategori=info-ppdb-beasiswa" class="btn btn-bnb-primary btn-sm py-2 px-3">
              <i class="bi bi-mortarboard me-1"></i> Info PPDB 2026
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
