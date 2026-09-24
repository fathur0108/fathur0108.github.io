<?php
/**
 * Halaman Kontak & Lokasi
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/functions.php';

$feedbackMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_pesan'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        setFlash('success', 'Terima kasih <strong>' . htmlspecialchars($nama) . '</strong>! Pesan Anda telah kami terima dan akan segera kami respon.');
    } else {
        setFlash('danger', 'Mohon lengkapi semua kolom form kontak.');
    }
    header("Location: " . getBaseUrl() . "/kontak.php");
    exit;
}

$pageTitle = "Hubungi Kami & Lokasi Kampus";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 20%, #1e293b 0%, #090e1a 100%);">
  <div class="container py-4 text-center position-relative" style="z-index: 2;">
    <span class="badge-glow mb-2"><i class="bi bi-chat-dots-fill"></i> LAYANAN INFORMASI</span>
    <h1 class="hero-title mb-2" style="font-size: 2.8rem;">Hubungi SMK Bangun Nusa Bangsa</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px;">
      Ada pertanyaan seputar program keahlian, PPDB, atau kerjasama industri? Kami siap melayani Anda.
    </p>
  </div>
</section>

<!-- Content Info & Form -->
<section class="py-5 bg-white">
  <div class="container py-lg-4">
    <?php renderFlash(); ?>

    <div class="row g-5">
      <!-- Left: Contact Information Cards -->
      <div class="col-lg-5">
        <span class="section-tag">Pusat Layanan</span>
        <h2 class="section-title">Informasi Kontak Resmi</h2>
        <p class="text-secondary mb-4" style="line-height: 1.8;">
          Silakan menghubungi kami melalui saluran telepon, email, maupun WhatsApp resmi sekolah pada hari dan jam operasional.
        </p>

        <div class="d-flex flex-column gap-3 mb-4">
          <!-- Alamat -->
          <div class="d-flex gap-3 p-3 rounded-4 bg-light border">
            <div class="brand-badge-3d" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
              <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">Alamat Kampus</h6>
              <p class="text-secondary small mb-0"><?= htmlspecialchars($school['alamat']) ?></p>
            </div>
          </div>

          <!-- Telepon -->
          <div class="d-flex gap-3 p-3 rounded-4 bg-light border">
            <div class="brand-badge-3d" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
              <i class="bi bi-telephone-fill"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">Telepon Kantor</h6>
              <p class="text-secondary small mb-0"><?= htmlspecialchars($school['telepon']) ?></p>
            </div>
          </div>

          <!-- WhatsApp -->
          <div class="d-flex gap-3 p-3 rounded-4 bg-light border">
            <div class="brand-badge-3d" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0; background: linear-gradient(135deg, #10b981, #059669);">
              <i class="bi bi-whatsapp"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">WhatsApp Helpdesk</h6>
              <p class="text-secondary small mb-0">
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $school['whatsapp']) ?>" target="_blank" class="text-success fw-bold text-decoration-none">
                  <?= htmlspecialchars($school['whatsapp']) ?> (Chat Langsung)
                </a>
              </p>
            </div>
          </div>

          <!-- Email -->
          <div class="d-flex gap-3 p-3 rounded-4 bg-light border">
            <div class="brand-badge-3d" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
              <i class="bi bi-envelope-fill"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1 text-dark">Email Resmi</h6>
              <p class="text-secondary small mb-0"><?= htmlspecialchars($school['email']) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Message Form & Map -->
      <div class="col-lg-7">
        <div class="card-3d-tilt p-4 p-lg-5">
          <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-send-fill text-primary me-2"></i> Kirim Pesan / Pengaduan</h4>
          <p class="text-secondary small mb-4">Sampaikan pertanyaan Anda dan tim kami akan membalas melalui email secepatnya.</p>

          <form action="<?= $baseUrl ?>/kontak.php" method="POST">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control rounded-3" placeholder="Nama Anda" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control rounded-3" placeholder="nama@email.com" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Subjek / Topik Pertanyaan</label>
              <input type="text" name="subjek" class="form-control rounded-3" placeholder="Misal: Info Pendaftaran Jurusan RPL">
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold small">Isi Pesan <span class="text-danger">*</span></label>
              <textarea name="pesan" class="form-control rounded-3" rows="4" placeholder="Tuliskan pertanyaan Anda secara jelas..." required></textarea>
            </div>

            <button type="submit" name="kirim_pesan" class="btn btn-bnb-primary w-100 py-3">
              <i class="bi bi-send me-1"></i> Kirim Pesan Sekarang
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Google Maps Embed -->
    <?php if (!empty($school['maps_embed'])): ?>
      <div class="mt-5 pt-4">
        <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-map-fill text-primary me-2"></i> Denah Lokasi Kampus</h4>
        <div class="rounded-4 overflow-hidden border shadow-sm" style="height: 380px;">
          <iframe src="<?= htmlspecialchars($school['maps_embed']) ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
