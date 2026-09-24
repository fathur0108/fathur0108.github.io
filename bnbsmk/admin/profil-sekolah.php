<?php
/**
 * Pengaturan Profil Sekolah & Sambutan
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Profil Sekolah & Sambutan";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $namaSekolah = trim($_POST['nama_sekolah'] ?? '');
        $slogan = trim($_POST['slogan'] ?? '');
        $namaKepsek = trim($_POST['nama_kepsek'] ?? '');
        $sambutan = trim($_POST['sambutan_kepsek'] ?? '');
        $sejarah = trim($_POST['sejarah'] ?? '');
        $visi = trim($_POST['visi'] ?? '');
        $misi = trim($_POST['misi'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telepon = trim($_POST['telepon'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $maps = trim($_POST['maps_embed'] ?? '');
        $fb = trim($_POST['facebook'] ?? '');
        $ig = trim($_POST['instagram'] ?? '');
        $yt = trim($_POST['youtube'] ?? '');

        $stmt = $pdo->prepare("
          UPDATE school_profile SET
            nama_sekolah = ?,
            slogan = ?,
            nama_kepsek = ?,
            sambutan_kepsek = ?,
            sejarah = ?,
            visi = ?,
            misi = ?,
            alamat = ?,
            email = ?,
            telepon = ?,
            whatsapp = ?,
            maps_embed = ?,
            facebook = ?,
            instagram = ?,
            youtube = ?
          WHERE id = 1
        ");
        $stmt->execute([
            sanitize($namaSekolah),
            sanitize($slogan),
            sanitize($namaKepsek),
            sanitize($sambutan),
            sanitize($sejarah),
            sanitize($visi),
            sanitize($misi),
            sanitize($alamat),
            sanitize($email),
            sanitize($telepon),
            sanitize($whatsapp),
            $maps,
            sanitize($fb),
            sanitize($ig),
            sanitize($yt)
        ]);

        setFlash('success', 'Informasi profil sekolah berhasil diperbarui!');
        header("Location: " . $baseUrl . "/admin/profil-sekolah.php");
        exit;
    }
}

$school = getSchoolProfile();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Profil Sekolah &amp; Informasi Lembaga</h3>
    <p class="text-muted small mb-0">Ubah identitas lembaga, sambutan kepala sekolah, visi misi dan kontak resmi.</p>
  </div>
</div>

<form action="<?= $baseUrl ?>/admin/profil-sekolah.php" method="POST">
  <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

  <div class="row g-4">
    <!-- Identitas & Sambutan -->
    <div class="col-lg-7">
      <div class="admin-card mb-4">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-building text-primary me-2"></i> Identitas &amp; Sambutan Pimpinan</h6>
        </div>
        <div class="admin-card-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Nama Sekolah <span class="text-danger">*</span></label>
              <input type="text" name="nama_sekolah" class="form-control rounded-3" value="<?= htmlspecialchars($school['nama_sekolah']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Nama Kepala Sekolah <span class="text-danger">*</span></label>
              <input type="text" name="nama_kepsek" class="form-control rounded-3" value="<?= htmlspecialchars($school['nama_kepsek']) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Slogan / Tagline Sekolah</label>
            <input type="text" name="slogan" class="form-control rounded-3" value="<?= htmlspecialchars($school['slogan']) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Sambutan Kepala Sekolah <span class="text-danger">*</span></label>
            <textarea name="sambutan_kepsek" class="form-control rounded-3" rows="7" required><?= htmlspecialchars($school['sambutan_kepsek']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Sejarah Singkat Lembaga</label>
            <textarea name="sejarah" class="form-control rounded-3" rows="4"><?= htmlspecialchars($school['sejarah']) ?></textarea>
          </div>
        </div>
      </div>

      <!-- Visi & Misi -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-bullseye text-primary me-2"></i> Visi &amp; Misi</h6>
        </div>
        <div class="admin-card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold small">Visi Sekolah</label>
            <textarea name="visi" class="form-control rounded-3" rows="3"><?= htmlspecialchars($school['visi']) ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Misi Sekolah (Pisahkan dengan baris baru)</label>
            <textarea name="misi" class="form-control rounded-3" rows="6"><?= htmlspecialchars($school['misi']) ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Kontak & Media Sosial -->
    <div class="col-lg-5">
      <div class="admin-card mb-4">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-telephone-fill text-info me-2"></i> Kontak &amp; Alamat</h6>
        </div>
        <div class="admin-card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold small">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control rounded-3" rows="3"><?= htmlspecialchars($school['alamat']) ?></textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Telepon Kantor</label>
              <input type="text" name="telepon" class="form-control rounded-3" value="<?= htmlspecialchars($school['telepon']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Nomor WhatsApp</label>
              <input type="text" name="whatsapp" class="form-control rounded-3" value="<?= htmlspecialchars($school['whatsapp']) ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Email Resmi</label>
            <input type="email" name="email" class="form-control rounded-3" value="<?= htmlspecialchars($school['email']) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Google Maps Embed URL</label>
            <input type="text" name="maps_embed" class="form-control rounded-3" value="<?= htmlspecialchars($school['maps_embed']) ?>" placeholder="https://www.google.com/maps/embed?...">
          </div>
        </div>
      </div>

      <div class="admin-card mb-4">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-share text-warning me-2"></i> Media Sosial</h6>
        </div>
        <div class="admin-card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold small"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
            <input type="url" name="instagram" class="form-control rounded-3" value="<?= htmlspecialchars($school['instagram']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
            <input type="url" name="facebook" class="form-control rounded-3" value="<?= htmlspecialchars($school['facebook']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small"><i class="bi bi-youtube text-danger me-1"></i> YouTube URL</label>
            <input type="url" name="youtube" class="form-control rounded-3" value="<?= htmlspecialchars($school['youtube']) ?>">
          </div>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold">
          <i class="bi bi-save me-1"></i> Simpan Semua Perubahan
        </button>
      </div>
    </div>
  </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
