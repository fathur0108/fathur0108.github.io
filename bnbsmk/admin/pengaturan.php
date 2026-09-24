<?php
/**
 * Pengaturan Website & Kebijakan Komentar
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Pengaturan Sistem";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $siteTitle = trim($_POST['site_title'] ?? '');
        $siteTagline = trim($_POST['site_tagline'] ?? '');
        $allowAnonymous = isset($_POST['allow_anonymous_comments']) ? '1' : '0';
        $autoApprove = isset($_POST['auto_approve_comments']) ? '1' : '0';

        setSiteSetting('site_title', sanitize($siteTitle));
        setSiteSetting('site_tagline', sanitize($siteTagline));
        setSiteSetting('allow_anonymous_comments', $allowAnonymous);
        setSiteSetting('auto_approve_comments', $autoApprove);

        setFlash('success', 'Konfigurasi sistem &amp; kebijakan komentar berhasil diperbarui!');
        header("Location: " . $baseUrl . "/admin/pengaturan.php");
        exit;
    }
}

$siteTitle = getSiteSetting('site_title', 'SMK Bangun Nusa Bangsa - Official Portal');
$siteTagline = getSiteSetting('site_tagline', 'Mencetak Generasi Unggul & Siap Kerja');
$allowAnonymous = getSiteSetting('allow_anonymous_comments', '1');
$autoApprove = getSiteSetting('auto_approve_comments', '1');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Pengaturan Website &amp; Kebijakan Komentar</h3>
    <p class="text-muted small mb-0">Atur parameter global, mode komentar anonim atau verifikasi identitas, dan sistem moderasi.</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders text-primary me-2"></i> Parameter Sistem</h5>
      </div>
      <div class="admin-card-body">
        <form action="<?= $baseUrl ?>/admin/pengaturan.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

          <!-- General Meta -->
          <div class="mb-4 pb-3 border-bottom">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-globe me-2 text-info"></i> Identitas Portal</h6>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Judul Situs Web (Title Tag)</label>
              <input type="text" name="site_title" class="form-control rounded-3" value="<?= htmlspecialchars($siteTitle) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Tagline / Slogan Default</label>
              <input type="text" name="site_tagline" class="form-control rounded-3" value="<?= htmlspecialchars($siteTagline) ?>">
            </div>
          </div>

          <!-- Comment Policy Config -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-chat-square-quote-fill me-2 text-danger"></i> Kebijakan Komentar Artikel</h6>

            <div class="p-3 bg-light rounded-3 mb-3 border">
              <div class="form-check form-switch fs-5">
                <input class="form-check-input" type="checkbox" role="switch" id="allow_anonymous_comments" name="allow_anonymous_comments" value="1" <?= $allowAnonymous === '1' ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold small ms-2 text-dark" for="allow_anonymous_comments" style="font-size: 1rem;">
                  Izinkan Pengunjung Berkomentar sebagai Anonim
                </label>
              </div>
              <small class="text-secondary d-block mt-1 ps-4 ms-3">
                Bila aktif, pembaca dapat memilih opsi "Mode Anonim" di mana email tidak wajib diisi dan identitas nama asli disamarkan.
              </small>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 border">
              <div class="form-check form-switch fs-5">
                <input class="form-check-input" type="checkbox" role="switch" id="auto_approve_comments" name="auto_approve_comments" value="1" <?= $autoApprove === '1' ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold small ms-2 text-dark" for="auto_approve_comments" style="font-size: 1rem;">
                  Publikasikan Komentar Otomatis (Auto-Approve)
                </label>
              </div>
              <small class="text-secondary d-block mt-1 ps-4 ms-3">
                Bila dinonaktifkan, setiap komentar baru dari pembaca akan berstatus <em>Pending</em> dan wajib disetujui terlebih dahulu oleh admin melalui menu Moderasi Komentar sebelum tampil ke publik.
              </small>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
              <i class="bi bi-save me-1"></i> Simpan Pengaturan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Tech Info Card -->
  <div class="col-lg-4">
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle text-info me-2"></i> Informasi Lingkungan</h6>
      </div>
      <div class="admin-card-body small">
        <ul class="list-unstyled mb-0 d-flex flex-column gap-2 text-secondary">
          <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
          <li><strong>Web Server:</strong> <?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache') ?></li>
          <li><strong>Database Engine:</strong> MySQL (PDO Driver)</li>
          <li><strong>3D Engine:</strong> Three.js WebGL + VanillaTilt.js</li>
          <li><strong>Frontend Framework:</strong> Bootstrap v5.3.3</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
