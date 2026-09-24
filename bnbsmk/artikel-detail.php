<?php
/**
 * Halaman Baca Artikel & Sistem Komentar (Anonim / Identitas)
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/functions.php';

$pdo = getDBConnection();
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header("Location: " . getBaseUrl() . "/artikel.php");
    exit;
}

// 1. Fetch Article Data
$stmt = $pdo->prepare("
  SELECT a.*, c.nama_kategori, c.slug AS cat_slug, u.nama_lengkap AS author_name, u.foto AS author_foto
  FROM articles a
  LEFT JOIN categories c ON a.category_id = c.id
  LEFT JOIN users u ON a.user_id = u.id
  WHERE a.slug = ? AND a.status = 'published'
  LIMIT 1
");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
    setFlash('danger', 'Artikel yang Anda cari tidak ditemukan atau telah diarsipkan.');
    header("Location: " . getBaseUrl() . "/artikel.php");
    exit;
}

// 2. Increment Views (Once per session per article)
$viewKey = 'viewed_article_' . $article['id'];
if (!isset($_SESSION[$viewKey])) {
    $updateView = $pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
    $updateView->execute([$article['id']]);
    $article['views'] += 1;
    $_SESSION[$viewKey] = true;
}

// 3. Handle Comment Submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    $honeypot = $_POST['website_hp'] ?? ''; // Anti-spam bot trap
    
    if (!empty($honeypot)) {
        // Silent rejection for bots
        setFlash('success', 'Komentar Anda telah terkirim.');
        header("Location: " . getBaseUrl() . "/artikel-detail.php?slug=" . urlencode($slug) . "#komentar");
        exit;
    }

    if (!verifyCsrfToken($csrfToken)) {
        setFlash('danger', 'Validasi keamanan gagal. Silakan muat ulang halaman.');
        header("Location: " . getBaseUrl() . "/artikel-detail.php?slug=" . urlencode($slug) . "#komentar");
        exit;
    }

    $isAnonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $nama = trim($_POST['nama_pengirim'] ?? '');
    $email = trim($_POST['email_pengirim'] ?? '');
    $isiKomentar = trim($_POST['isi_komentar'] ?? '');
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    // Validation
    $errors = [];
    if (empty($isiKomentar)) {
        $errors[] = 'Isi komentar tidak boleh kosong.';
    }

    if ($isAnonymous) {
        // Mode Anonim: email opsional/null
        if (empty($nama) || $nama === 'Anonim') {
            $nama = 'Anonim (' . substr(md5($_SERVER['REMOTE_ADDR'] . date('Y-m-d')), 0, 4) . ')';
        }
        $email = null;
    } else {
        // Mode Identitas Lengkap
        if (empty($nama)) {
            $errors[] = 'Nama lengkap wajib diisi jika tidak memilih mode anonim.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Alamat email yang valid wajib diisi.';
        }
    }

    if (empty($errors)) {
        $autoApprove = getSiteSetting('auto_approve_comments', '1');
        $status = ($autoApprove === '1') ? 'approved' : 'pending';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

        $insertStmt = $pdo->prepare("
          INSERT INTO comments (article_id, parent_id, nama_pengirim, email_pengirim, is_anonymous, isi_komentar, status, ip_address)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insertStmt->execute([
            $article['id'],
            $parentId,
            sanitize($nama),
            $email ? sanitize($email) : null,
            $isAnonymous,
            sanitize($isiKomentar),
            $status,
            $ipAddress
        ]);

        if ($status === 'approved') {
            setFlash('success', 'Terima kasih! Komentar Anda berhasil dipublikasikan.');
        } else {
            setFlash('info', 'Terima kasih! Komentar Anda sedang menunggu moderasi dari administrator.');
        }

        header("Location: " . getBaseUrl() . "/artikel-detail.php?slug=" . urlencode($slug) . "#komentar");
        exit;
    } else {
        setFlash('danger', implode('<br>', $errors));
    }
}

// 4. Fetch Approved Comments
$commentsStmt = $pdo->prepare("
  SELECT * FROM comments
  WHERE article_id = ? AND status = 'approved'
  ORDER BY created_at ASC
");
$commentsStmt->execute([$article['id']]);
$allComments = $commentsStmt->fetchAll();

// Group comments by parent for replies
$rootComments = [];
$replies = [];
foreach ($allComments as $c) {
    if (empty($c['parent_id'])) {
        $rootComments[] = $c;
    } else {
        $replies[$c['parent_id']][] = $c;
    }
}

// 5. Fetch Related Articles
$relatedStmt = $pdo->prepare("
  SELECT judul, slug, created_at, views, gambar_sampul
  FROM articles
  WHERE category_id = ? AND id != ? AND status = 'published'
  ORDER BY created_at DESC
  LIMIT 3
");
$relatedStmt->execute([$article['category_id'], $article['id']]);
$relatedArticles = $relatedStmt->fetchAll();

$pageTitle = $article['judul'];
$pageDesc = $article['ringkasan'];
require_once __DIR__ . '/includes/header.php';
?>

<!-- Article Header Breadcrumb & Title -->
<section class="py-5 bg-dark text-white position-relative" style="background: radial-gradient(circle at 20% 30%, #1e293b 0%, #060b14 100%);">
  <div class="container py-3 position-relative" style="z-index: 2;">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php" class="text-info text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/artikel.php" class="text-info text-decoration-none">Artikel</a></li>
        <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/artikel.php?kategori=<?= urlencode($article['cat_slug']) ?>" class="text-info text-decoration-none"><?= htmlspecialchars($article['nama_kategori']) ?></a></li>
      </ol>
    </nav>
    <h1 class="hero-title mb-3" style="font-size: 2.5rem; line-height: 1.25;">
      <?= htmlspecialchars($article['judul']) ?>
    </h1>
    <div class="d-flex flex-wrap align-items-center gap-3 text-white-50 small">
      <span><i class="bi bi-person-circle text-info me-1"></i> <?= htmlspecialchars($article['author_name'] ?? 'Admin BNB') ?></span>
      <span>&bull;</span>
      <span><i class="bi bi-calendar3 text-info me-1"></i> <?= formatTanggalIndo($article['created_at'], true) ?></span>
      <span>&bull;</span>
      <span><i class="bi bi-eye text-info me-1"></i> <?= $article['views'] ?> pembaca</span>
      <span>&bull;</span>
      <span><i class="bi bi-clock text-info me-1"></i> <?= estimateReadingTime($article['konten']) ?> menit baca</span>
    </div>
  </div>
</section>

<!-- Main Article Body -->
<section class="py-5 bg-white">
  <div class="container py-lg-2">
    <div class="row g-5">
      <div class="col-lg-8">
        <!-- Featured Image with 3D Card Style -->
        <div class="mb-4 rounded-4 overflow-hidden shadow-sm">
          <img src="<?= getImageUrl($article['gambar_sampul']) ?>" alt="<?= htmlspecialchars($article['judul']) ?>" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;">
        </div>

        <!-- Article Summary Box -->
        <?php if (!empty($article['ringkasan'])): ?>
          <div class="p-4 rounded-4 mb-4 bg-light border-start border-4 border-primary shadow-sm" style="font-size: 1.1rem; font-style: italic; color: #334155;">
            <?= htmlspecialchars($article['ringkasan']) ?>
          </div>
        <?php endif; ?>

        <!-- Rich Content -->
        <div class="article-content-body text-secondary" style="font-size: 1.08rem; line-height: 1.85;">
          <?= $article['konten'] ?>
        </div>

        <!-- Share & Tags -->
        <div class="mt-5 pt-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark small"><i class="bi bi-tag-fill text-primary me-1"></i> Kategori:</span>
            <a href="<?= $baseUrl ?>/artikel.php?kategori=<?= urlencode($article['cat_slug']) ?>" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none px-3 py-2 rounded-pill">
              <?= htmlspecialchars($article['nama_kategori']) ?>
            </a>
          </div>

          <!-- Social Share -->
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark small me-1">Bagikan:</span>
            <?php 
              $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              $shareTitle = urlencode($article['judul']);
            ?>
            <a href="https://api.whatsapp.com/send?text=<?= $shareTitle ?>%20<?= urlencode($currentUrl) ?>" target="_blank" class="btn btn-sm btn-success rounded-circle" title="Share ke WhatsApp" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" target="_blank" class="btn btn-sm btn-primary rounded-circle" title="Share ke Facebook" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?text=<?= $shareTitle ?>&url=<?= urlencode($currentUrl) ?>" target="_blank" class="btn btn-sm btn-dark rounded-circle" title="Share ke X" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-twitter-x"></i>
            </a>
          </div>
        </div>

        <!-- ======================================================= -->
        <!-- KOMENTAR SECTION (SUPPORT ANONIM & IDENTITAS LENGKAP)   -->
        <!-- ======================================================= -->
        <div id="komentar" class="comments-section">
          <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark">
              <i class="bi bi-chat-square-text-fill text-primary me-2"></i> Diskusi &amp; Komentar (<?= count($allComments) ?>)
            </h4>
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill small">
              <i class="bi bi-shield-check text-success me-1"></i> Komentar Terbuka
            </span>
          </div>

          <!-- Flash Message Handler -->
          <?php renderFlash(); ?>

          <!-- List of Comments -->
          <?php if (empty($rootComments)): ?>
            <div class="text-center py-4 text-muted bg-light rounded-4 mb-4">
              <i class="bi bi-chat-left-dots fs-2 d-block mb-2 text-primary opacity-50"></i>
              <p class="mb-1">Belum ada komentar untuk artikel ini.</p>
              <small class="text-secondary">Jadilah yang pertama memberikan tanggapan atau opini!</small>
            </div>
          <?php else: ?>
            <div class="mb-5">
              <?php foreach ($rootComments as $comment): ?>
                <div class="comment-card <?= $comment['is_anonymous'] ? 'is-anonymous' : '' ?>">
                  <div class="d-flex gap-3">
                    <div class="comment-avatar <?= $comment['is_anonymous'] ? 'avatar-anon' : 'avatar-user' ?>">
                      <?php if ($comment['is_anonymous']): ?>
                        <i class="bi bi-incognito"></i>
                      <?php else: ?>
                        <?= strtoupper(substr($comment['nama_pengirim'], 0, 1)) ?>
                      <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center gap-2">
                          <strong class="text-dark"><?= htmlspecialchars($comment['nama_pengirim']) ?></strong>
                          <?php if ($comment['is_anonymous']): ?>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-2 py-0 small" style="font-size: 0.72rem;">
                              <i class="bi bi-eye-slash-fill me-1"></i> Anonim
                            </span>
                          <?php endif; ?>
                        </div>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i> <?= timeAgo($comment['created_at']) ?></small>
                      </div>
                      <p class="text-secondary mb-2" style="font-size: 0.96rem; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($comment['isi_komentar'])) ?>
                      </p>
                    </div>
                  </div>

                  <!-- Render Replies if any -->
                  <?php if (!empty($replies[$comment['id']])): ?>
                    <div class="ms-5 mt-3 pt-3 border-top border-secondary border-opacity-10">
                      <?php foreach ($replies[$comment['id']] as $rep): ?>
                        <div class="d-flex gap-3 mt-2 p-2 bg-white rounded-3 border">
                          <div class="comment-avatar avatar-admin" style="width: 36px; height: 36px; font-size: 0.9rem;">
                            <i class="bi bi-patch-check-fill"></i>
                          </div>
                          <div>
                            <div class="d-flex align-items-center gap-2">
                              <strong class="text-dark small"><?= htmlspecialchars($rep['nama_pengirim']) ?></strong>
                              <span class="badge bg-danger text-white rounded-pill" style="font-size: 0.65rem;">Admin</span>
                              <small class="text-muted ms-2" style="font-size: 0.75rem;"><?= timeAgo($rep['created_at']) ?></small>
                            </div>
                            <p class="text-secondary small mb-0 mt-1">
                              <?= nl2br(htmlspecialchars($rep['isi_komentar'])) ?>
                            </p>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- FORMULIR TINGGALKAN KOMENTAR -->
          <?php if ($article['allow_comments']): ?>
            <div class="comment-form-box">
              <h5 class="fw-bold mb-3 text-dark">
                <i class="bi bi-pencil-fill text-primary me-2"></i> Tulis Tanggapan Anda
              </h5>

              <form action="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($slug) ?>#komentar" method="POST">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <!-- Honeypot anti-bot field -->
                <div style="display:none !important;">
                  <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                </div>

                <!-- Opsi Mode Anonim -->
                <div class="anonymous-toggle-card d-flex align-items-center justify-content-between">
                  <div>
                    <label class="form-check-label fw-bold text-dark d-block" for="is_anonymous">
                      <i class="bi bi-incognito text-info me-1"></i> Kirim sebagai Komentar Anonim
                    </label>
                    <small class="text-muted">Centang jika tidak ingin mencantumkan email dan nama asli Anda.</small>
                  </div>
                  <div class="form-check form-switch fs-4 mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_anonymous" name="is_anonymous" value="1">
                  </div>
                </div>

                <div class="row g-3 mb-3">
                  <!-- Nama -->
                  <div class="col-md-6">
                    <label for="nama_pengirim" class="form-label fw-semibold small" id="label-nama-pengirim">
                      Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control rounded-3" id="nama_pengirim" name="nama_pengirim" placeholder="Masukkan nama Anda" required>
                  </div>
                  <!-- Email -->
                  <div class="col-md-6">
                    <label for="email_pengirim" class="form-label fw-semibold small" id="label-email-pengirim">
                      Alamat Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control rounded-3" id="email_pengirim" name="email_pengirim" placeholder="nama@email.com" required>
                    <small class="text-muted d-block mt-1" id="email-help-text" style="font-size: 0.76rem;">Email tidak akan dipublikasikan.</small>
                  </div>
                </div>

                <!-- Isi Komentar -->
                <div class="mb-4">
                  <label for="isi_komentar" class="form-label fw-semibold small">Isi Pesan / Komentar <span class="text-danger">*</span></label>
                  <textarea class="form-control rounded-3" id="isi_komentar" name="isi_komentar" rows="4" placeholder="Tuliskan pendapat atau pertanyaan Anda secara sopan..." required></textarea>
                </div>

                <button type="submit" name="submit_comment" class="btn btn-bnb-primary">
                  <i class="bi bi-send-fill me-1"></i> Kirim Komentar Sekarang
                </button>
              </form>
            </div>
          <?php else: ?>
            <div class="alert alert-warning mb-0">
              <i class="bi bi-lock-fill me-2"></i> Kolom komentar untuk artikel ini telah dinonaktifkan oleh administrator.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Right Column: Related Articles & Author Info -->
      <div class="col-lg-4">
        <!-- Author Profile Card -->
        <div class="p-4 rounded-4 bg-light border mb-4 text-center">
          <div class="comment-avatar avatar-admin mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
            <i class="bi bi-person-fill"></i>
          </div>
          <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($article['author_name'] ?? 'Admin BNB') ?></h5>
          <small class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill mb-3">Tim Redaksi &amp; Humas BNB</small>
          <p class="text-secondary small mb-0" style="line-height: 1.6;">
            Mengabarkan berita terkini, karya teknologi, serta inspirasi pendidikan vokasi dari kampus SMK Bangun Nusa Bangsa.
          </p>
        </div>

        <!-- Related Articles -->
        <div class="p-4 rounded-4 bg-light border mb-4">
          <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-collection-fill text-primary me-2"></i> Artikel Terkait</h5>
          <?php if (empty($relatedArticles)): ?>
            <p class="text-muted small mb-0">Belum ada artikel terkait lainnya dalam kategori ini.</p>
          <?php else: ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($relatedArticles as $rel): ?>
                <div class="d-flex gap-3 align-items-center">
                  <img src="<?= getImageUrl($rel['gambar_sampul']) ?>" alt="<?= htmlspecialchars($rel['judul']) ?>" class="rounded-3" style="width: 75px; height: 60px; object-fit: cover; flex-shrink: 0;">
                  <div>
                    <h6 class="mb-1" style="font-size: 0.92rem; line-height: 1.35;">
                      <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($rel['slug']) ?>" class="text-dark fw-bold text-decoration-none">
                        <?= htmlspecialchars(mb_strimwidth($rel['judul'], 0, 50, '...')) ?>
                      </a>
                    </h6>
                    <small class="text-muted"><i class="bi bi-calendar3"></i> <?= formatTanggalIndo($rel['created_at']) ?></small>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Back to Article List -->
        <div class="d-grid">
          <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-outline-secondary rounded-3 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Semua Artikel
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
