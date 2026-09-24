<?php
/**
 * Admin Panel Header & Sidebar
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../../config/functions.php';
requireLogin();

$user = getAuthUser();
$school = getSchoolProfile();
$baseUrl = getBaseUrl();
$currentAdminPage = basename($_SERVER['PHP_SELF'], '.php');

// Pending comments count for badge
$pdo = getDBConnection();
$pendingCommentsCount = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Dashboard' ?> | <?= htmlspecialchars($school['nama_sekolah']) ?></title>
  <link rel="icon" type="image/jpeg" href="<?= $baseUrl ?>/assets/images/logo-smk.jpg">
  
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Quill Editor Snow Theme CSS -->
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
  <!-- Custom Admin Style -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/admin.css">
</head>
<body class="admin-body">

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar">
    <a href="<?= $baseUrl ?>/admin/index.php" class="sidebar-brand">
      <img src="<?= $baseUrl ?>/assets/images/logo-smk.jpg" alt="Logo SMK" style="width: 38px; height: 38px; object-fit: contain;" class="rounded-2 p-1 bg-white shadow-sm">
      <div>
        <div style="font-family: 'Outfit'; font-weight: 800; font-size: 1.05rem; line-height: 1.2;">BNB ADMIN</div>
        <small class="text-white-50" style="font-size: 0.7rem;">CMS Management</small>
      </div>
    </a>

    <div class="sidebar-heading">Menu Utama</div>
    <ul class="sidebar-nav">
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/index.php" class="nav-link <?= $currentAdminPage === 'index' ? 'active' : '' ?>">
          <i class="bi bi-speedometer2 text-info"></i>
          <span>Dashboard</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-heading">Manajemen Artikel &amp; Media</div>
    <ul class="sidebar-nav">
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/artikel.php" class="nav-link <?= in_array($currentAdminPage, ['artikel', 'artikel-edit']) ? 'active' : '' ?>">
          <i class="bi bi-journal-text text-primary"></i>
          <span>Kelola Artikel</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/artikel-tambah.php" class="nav-link <?= $currentAdminPage === 'artikel-tambah' ? 'active' : '' ?>">
          <i class="bi bi-plus-circle text-success"></i>
          <span>Tambah Artikel</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/kategori.php" class="nav-link <?= $currentAdminPage === 'kategori' ? 'active' : '' ?>">
          <i class="bi bi-tags text-warning"></i>
          <span>Kategori Artikel</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/komentar.php" class="nav-link <?= $currentAdminPage === 'komentar' ? 'active' : '' ?>">
          <i class="bi bi-chat-square-text text-danger"></i>
          <span>Moderasi Komentar</span>
          <?php if ($pendingCommentsCount > 0): ?>
            <span class="badge bg-danger sidebar-badge"><?= $pendingCommentsCount ?></span>
          <?php endif; ?>
        </a>
      </li>
    </ul>

    <div class="sidebar-heading">Profil Sekolah &amp; Master</div>
    <ul class="sidebar-nav">
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/jurusan.php" class="nav-link <?= $currentAdminPage === 'jurusan' ? 'active' : '' ?>">
          <i class="bi bi-grid-fill text-info"></i>
          <span>Program Keahlian</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/profil-sekolah.php" class="nav-link <?= $currentAdminPage === 'profil-sekolah' ? 'active' : '' ?>">
          <i class="bi bi-building text-primary"></i>
          <span>Profil &amp; Sambutan</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/pengaturan.php" class="nav-link <?= $currentAdminPage === 'pengaturan' ? 'active' : '' ?>">
          <i class="bi bi-gear-fill text-secondary"></i>
          <span>Pengaturan Situs</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= $baseUrl ?>/admin/users.php" class="nav-link <?= $currentAdminPage === 'users' ? 'active' : '' ?>">
          <i class="bi bi-person-gear text-light"></i>
          <span>Akun Pengguna</span>
        </a>
      </li>
    </ul>

    <div class="p-3 mt-4 border-top border-secondary border-opacity-25">
      <a href="<?= $baseUrl ?>/logout.php" class="btn btn-outline-danger btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-box-arrow-left"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Admin Main Layout -->
  <div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
      <div class="d-flex align-items-center gap-3">
        <button id="sidebar-toggle" class="btn btn-light d-lg-none border-0">
          <i class="bi bi-list fs-4"></i>
        </button>
        <a href="<?= $baseUrl ?>/index.php" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
          <i class="bi bi-globe me-1"></i> Lihat Website Publik <i class="bi bi-arrow-up-right small"></i>
        </a>
      </div>

      <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
          <a href="#" class="admin-user-menu dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= getImageUrl($user['foto'] ?? 'admin.png', 'default-avatar.svg') ?>" alt="Admin" class="admin-avatar">
            <div class="d-none d-md-block text-start">
              <div class="fw-bold small"><?= htmlspecialchars($user['nama_lengkap']) ?></div>
              <div class="text-muted" style="font-size: 0.72rem; text-transform: uppercase;"><?= htmlspecialchars($user['role']) ?></div>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
            <li><a class="dropdown-item py-2" href="<?= $baseUrl ?>/admin/users.php"><i class="bi bi-person me-2 text-primary"></i> Profil Akun</a></li>
            <li><a class="dropdown-item py-2" href="<?= $baseUrl ?>/admin/pengaturan.php"><i class="bi bi-gear me-2 text-secondary"></i> Pengaturan</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger" href="<?= $baseUrl ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Content Container -->
    <main class="admin-content">
      <?php renderFlash(); ?>
