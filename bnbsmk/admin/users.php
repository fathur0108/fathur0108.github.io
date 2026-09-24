<?php
/**
 * Manajemen Akun Administrator & Profil
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Akun Pengguna & Keamanan";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();
$currentUserId = $_SESSION['user_id'] ?? 1;

// 1. Handle Profile / Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordBaru = trim($_POST['password_baru'] ?? '');
        $konfirmasiPassword = trim($_POST['konfirmasi_password'] ?? '');

        $errors = [];
        if (empty($namaLengkap)) $errors[] = 'Nama lengkap wajib diisi.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Alamat email valid wajib diisi.';

        if (!empty($passwordBaru)) {
            if (strlen($passwordBaru) < 6) {
                $errors[] = 'Kata sandi baru minimal 6 karakter.';
            }
            if ($passwordBaru !== $konfirmasiPassword) {
                $errors[] = 'Konfirmasi kata sandi tidak cocok.';
            }
        }

        if (empty($errors)) {
            if (!empty($passwordBaru)) {
                $hashedPassword = password_hash($passwordBaru, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([sanitize($namaLengkap), sanitize($email), $hashedPassword, $currentUserId]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ? WHERE id = ?");
                $stmt->execute([sanitize($namaLengkap), sanitize($email), $currentUserId]);
            }

            $_SESSION['user_name'] = $namaLengkap;
            setFlash('success', 'Profil dan kredensial akun Anda berhasil diperbarui!');
            header("Location: " . $baseUrl . "/admin/users.php");
            exit;
        } else {
            setFlash('danger', implode('<br>', $errors));
        }
    }
}

// Fetch all admin accounts
$users = $pdo->query("SELECT id, username, nama_lengkap, email, role, created_at FROM users ORDER BY id ASC")->fetchAll();
$currentUserData = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$currentUserData->execute([$currentUserId]);
$myUser = $currentUserData->fetch();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Akun Administrator &amp; Keamanan</h3>
    <p class="text-muted small mb-0">Kelola informasi akun Anda dan perbarui kata sandi untuk keamanan sistem.</p>
  </div>
</div>

<div class="row g-4">
  <!-- Left: Update Profile Form -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-circle text-primary me-2"></i> Profil Akun Saya</h5>
      </div>
      <div class="admin-card-body">
        <form action="<?= $baseUrl ?>/admin/users.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

          <div class="mb-3">
            <label class="form-label fw-semibold small">Username</label>
            <input type="text" class="form-control rounded-3 bg-light" value="<?= htmlspecialchars($myUser['username']) ?>" readonly disabled>
            <small class="text-muted">Username login sistem.</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_lengkap" class="form-control rounded-3" value="<?= htmlspecialchars($myUser['nama_lengkap']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control rounded-3" value="<?= htmlspecialchars($myUser['email']) ?>" required>
          </div>

          <hr class="my-4">
          <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock text-warning me-2"></i> Ganti Kata Sandi (Opsional)</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Kata Sandi Baru</label>
            <input type="password" name="password_baru" class="form-control rounded-3" placeholder="Biarkan kosong jika tidak ingin mengganti">
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold small">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="konfirmasi_password" class="form-control rounded-3" placeholder="Ulangi kata sandi baru">
          </div>

          <button type="submit" name="update_profile" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">
            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Right: Admin Users List -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-people-fill text-info me-2"></i> Daftar Pengguna Terdaftar</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-custom mb-0">
          <thead>
            <tr>
              <th>Nama &amp; Username</th>
              <th>Email</th>
              <th>Peran</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td>
                  <strong class="text-dark d-block"><?= htmlspecialchars($u['nama_lengkap']) ?></strong>
                  <small class="text-muted">@<?= htmlspecialchars($u['username']) ?></small>
                </td>
                <td><small class="text-secondary"><?= htmlspecialchars($u['email']) ?></small></td>
                <td>
                  <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                    <?= strtoupper(htmlspecialchars($u['role'])) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
