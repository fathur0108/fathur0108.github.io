<?php
/**
 * Halaman Login Admin
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/functions.php';

// If already logged in, redirect to admin panel
if (isLoggedIn()) {
    header("Location: " . getBaseUrl() . "/admin/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrfToken)) {
        $error = 'Validasi token keamanan kedaluwarsa. Silakan refresh.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Username dan kata sandi wajib diisi.';
        } else {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) LIMIT 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nama_lengkap'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_username'] = $user['username'];

                setFlash('success', 'Selamat datang kembali, <strong>' . htmlspecialchars($user['nama_lengkap']) . '</strong>!');
                header("Location: " . getBaseUrl() . "/admin/index.php");
                exit;
            } else {
                $error = 'Kombinasi username/email dan password tidak sesuai.';
            }
        }
    }
}

$baseUrl = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Administrator | SMK Bangun Nusa Bangsa</title>
  <link rel="icon" type="image/svg+xml" href="<?= $baseUrl ?>/assets/images/logo.svg">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 10% 20%, #0d1527 0%, #060b14 100%);
      position: relative;
      overflow-x: hidden;
      padding: 20px;
    }
    .login-3d-card {
      width: 100%;
      max-width: 440px;
      background: rgba(15, 23, 42, 0.75);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 24px;
      padding: 40px;
      color: #fff;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.2);
    }
    .form-control-dark {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #fff;
      border-radius: 12px;
      padding: 12px 16px;
    }
    .form-control-dark:focus {
      background: rgba(255, 255, 255, 0.14);
      border-color: #38bdf8;
      color: #fff;
      box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
    }
  </style>
</head>
<body>

  <div class="login-3d-card card-3d-tilt">
    <div class="text-center mb-4">
      <div class="brand-badge-3d mx-auto mb-3" style="width: 58px; height: 58px; font-size: 1.8rem;">
        <i class="bi bi-shield-lock-fill"></i>
      </div>
      <h3 class="fw-bold text-white mb-1" style="font-family: 'Outfit';">Admin Portal</h3>
      <p class="text-white-50 small mb-0">SMK Bangun Nusa Bangsa</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger bg-danger bg-opacity-25 border border-danger border-opacity-50 text-white small p-3 rounded-3 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
      </div>
    <?php endif; ?>

    <form action="<?= $baseUrl ?>/login.php" method="POST">
      <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

      <div class="mb-3">
        <label for="username" class="form-label text-white-50 small fw-semibold">Username atau Email</label>
        <div class="input-group">
          <span class="input-group-text bg-dark border-secondary border-opacity-25 text-info rounded-start-3"><i class="bi bi-person"></i></span>
          <input type="text" class="form-control form-control-dark rounded-end-3" id="username" name="username" placeholder="admin" value="admin" required autofocus>
        </div>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label text-white-50 small fw-semibold">Kata Sandi (Password)</label>
        <div class="input-group">
          <span class="input-group-text bg-dark border-secondary border-opacity-25 text-info rounded-start-3"><i class="bi bi-key"></i></span>
          <input type="password" class="form-control form-control-dark rounded-end-3" id="password" name="password" placeholder="admin123" value="admin123" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <small class="text-info opacity-75">Demo default: admin / admin123</small>
        </div>
      </div>

      <button type="submit" class="btn btn-bnb-primary w-100 py-3 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Dashboard
      </button>

      <div class="text-center">
        <a href="<?= $baseUrl ?>/index.php" class="text-white-50 text-decoration-none small">
          <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda Website
        </a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
  <script>
    if (typeof VanillaTilt !== 'undefined') {
      VanillaTilt.init(document.querySelector('.card-3d-tilt'), {
        max: 8,
        speed: 500,
        glare: true,
        "max-glare": 0.2
      });
    }
  </script>
</body>
</html>
