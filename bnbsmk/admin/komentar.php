<?php
/**
 * Moderasi Komentar Pengunjung (Anonim & Teridentifikasi)
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Moderasi Komentar";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// 1. Handle Status Changes (Approve / Spam / Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $commentId = (int)$_GET['id'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$commentId]);
        setFlash('success', 'Komentar berhasil disetujui dan kini tampil di artikel.');
    } elseif ($action === 'spam') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'spam' WHERE id = ?");
        $stmt->execute([$commentId]);
        setFlash('warning', 'Komentar ditandai sebagai SPAM.');
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        setFlash('success', 'Komentar berhasil dihapus.');
    }
    header("Location: " . $baseUrl . "/admin/komentar.php" . (isset($_GET['tab']) ? '?tab=' . $_GET['tab'] : ''));
    exit;
}

// 2. Handle Admin Reply Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $articleId = (int)$_POST['article_id'];
        $parentId = (int)$_POST['parent_id'];
        $replyText = trim($_POST['reply_text'] ?? '');
        $adminName = $_SESSION['user_name'] ?? 'Admin SMK BNB';

        if (!empty($replyText)) {
            $stmt = $pdo->prepare("
              INSERT INTO comments (article_id, parent_id, nama_pengirim, email_pengirim, is_anonymous, isi_komentar, status, ip_address)
              VALUES (?, ?, ?, ?, 0, ?, 'approved', ?)
            ");
            $stmt->execute([
                $articleId,
                $parentId,
                $adminName . ' (Admin)',
                'admin@smkbangunnusabangsa.sch.id',
                sanitize($replyText),
                $_SERVER['REMOTE_ADDR'] ?? ''
            ]);
            setFlash('success', 'Balasan resmi berhasil dipublikasikan!');
        }
    }
    header("Location: " . $baseUrl . "/admin/komentar.php");
    exit;
}

// Filter Tab
$tab = $_GET['tab'] ?? 'all';
$whereClause = "1=1";
if ($tab === 'pending') $whereClause = "cm.status = 'pending'";
elseif ($tab === 'approved') $whereClause = "cm.status = 'approved'";
elseif ($tab === 'spam') $whereClause = "cm.status = 'spam'";

// Fetch Comments with Article Details
$comments = $pdo->query("
  SELECT cm.*, a.judul AS article_title, a.slug AS article_slug
  FROM comments cm
  LEFT JOIN articles a ON cm.article_id = a.id
  WHERE {$whereClause}
  ORDER BY cm.created_at DESC
")->fetchAll();

// Count per status
$countPending = $pdo->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn();
$countApproved = $pdo->query("SELECT COUNT(*) FROM comments WHERE status='approved'")->fetchColumn();
$countSpam = $pdo->query("SELECT COUNT(*) FROM comments WHERE status='spam'")->fetchColumn();
$countAll = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Moderasi &amp; Diskusi Artikel</h3>
    <p class="text-muted small mb-0">Kelola tanggapan pembaca, pantau komentar anonim dan berikan balasan resmi.</p>
  </div>
</div>

<!-- Status Tabs Navigation -->
<ul class="nav nav-pills mb-4 gap-2">
  <li class="nav-item">
    <a class="nav-link <?= $tab === 'all' ? 'active' : 'bg-white border' ?>" href="<?= $baseUrl ?>/admin/komentar.php?tab=all">
      Semua Komentar <span class="badge bg-secondary ms-1"><?= $countAll ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $tab === 'pending' ? 'active' : 'bg-white border' ?>" href="<?= $baseUrl ?>/admin/komentar.php?tab=pending">
      Menunggu Moderasi <span class="badge bg-danger ms-1"><?= $countPending ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $tab === 'approved' ? 'active' : 'bg-white border' ?>" href="<?= $baseUrl ?>/admin/komentar.php?tab=approved">
      Disetujui <span class="badge bg-success ms-1"><?= $countApproved ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $tab === 'spam' ? 'active' : 'bg-white border' ?>" href="<?= $baseUrl ?>/admin/komentar.php?tab=spam">
      Spam <span class="badge bg-dark ms-1"><?= $countSpam ?></span>
    </a>
  </li>
</ul>

<!-- Comments Table -->
<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Pengirim</th>
          <th>Tipe &amp; Mode</th>
          <th>Isi Komentar</th>
          <th>Artikel Terkait</th>
          <th>Status</th>
          <th>Waktu</th>
          <th class="text-end">Aksi &amp; Respon</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($comments)): ?>
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bi bi-chat-heart fs-1 d-block mb-2"></i>
              Tidak ada komentar pada filter ini.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($comments as $cm): ?>
            <tr>
              <td>
                <div class="fw-bold text-dark"><?= htmlspecialchars($cm['nama_pengirim']) ?></div>
                <?php if (!empty($cm['email_pengirim'])): ?>
                  <small class="text-muted"><i class="bi bi-envelope"></i> <?= htmlspecialchars($cm['email_pengirim']) ?></small>
                <?php else: ?>
                  <small class="text-secondary opacity-75"><em>Tanpa Email</em></small>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($cm['is_anonymous']): ?>
                  <span class="badge bg-dark text-info px-2 py-1">
                    <i class="bi bi-incognito me-1"></i> Mode Anonim
                  </span>
                <?php else: ?>
                  <span class="badge bg-info bg-opacity-10 text-primary border border-info border-opacity-25 px-2 py-1">
                    <i class="bi bi-person-check me-1"></i> Beridentitas
                  </span>
                <?php endif; ?>
              </td>
              <td style="max-width: 280px;">
                <div class="small text-secondary" style="line-height: 1.6;">
                  <?= nl2br(htmlspecialchars($cm['isi_komentar'])) ?>
                </div>
              </td>
              <td>
                <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($cm['article_slug'] ?? '') ?>" target="_blank" class="small fw-semibold text-dark text-decoration-none">
                  <?= htmlspecialchars(mb_strimwidth($cm['article_title'] ?? '-', 0, 32, '...')) ?> <i class="bi bi-box-arrow-up-right small"></i>
                </a>
              </td>
              <td>
                <?php if ($cm['status'] === 'approved'): ?>
                  <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Approved</span>
                <?php elseif ($cm['status'] === 'pending'): ?>
                  <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">Pending</span>
                <?php else: ?>
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Spam</span>
                <?php endif; ?>
              </td>
              <td><small class="text-muted"><?= timeAgo($cm['created_at']) ?></small></td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <!-- Approve -->
                  <?php if ($cm['status'] !== 'approved'): ?>
                    <a href="<?= $baseUrl ?>/admin/komentar.php?action=approve&id=<?= $cm['id'] ?>&tab=<?= $tab ?>" class="btn btn-sm btn-success py-1 px-2" title="Setujui Komentar">
                      <i class="bi bi-check-lg"></i>
                    </a>
                  <?php endif; ?>

                  <!-- Reply Button -->
                  <button class="btn btn-sm btn-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#replyModal<?= $cm['id'] ?>" title="Balas sebagai Admin">
                    <i class="bi bi-reply-fill"></i>
                  </button>

                  <!-- Mark as Spam -->
                  <?php if ($cm['status'] !== 'spam'): ?>
                    <a href="<?= $baseUrl ?>/admin/komentar.php?action=spam&id=<?= $cm['id'] ?>&tab=<?= $tab ?>" class="btn btn-sm btn-warning py-1 px-2 text-dark" title="Tandai Spam">
                      <i class="bi bi-shield-x"></i>
                    </a>
                  <?php endif; ?>

                  <!-- Delete -->
                  <a href="<?= $baseUrl ?>/admin/komentar.php?action=delete&id=<?= $cm['id'] ?>&tab=<?= $tab ?>" 
                     data-item="komentar dari '<?= htmlspecialchars($cm['nama_pengirim']) ?>'" 
                     class="btn btn-sm btn-danger btn-delete-confirm py-1 px-2" title="Hapus">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>

            <!-- Reply Modal -->
            <div class="modal fade" id="replyModal<?= $cm['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content rounded-4 border-0 shadow">
                  <form action="<?= $baseUrl ?>/admin/komentar.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="article_id" value="<?= $cm['article_id'] ?>">
                    <input type="hidden" name="parent_id" value="<?= $cm['id'] ?>">

                    <div class="modal-header border-bottom-0 pb-0">
                      <h5 class="modal-title fw-bold"><i class="bi bi-reply-fill text-primary me-2"></i> Balas Komentar</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="p-3 bg-light rounded-3 mb-3 border">
                        <small class="text-muted d-block mb-1">Komentar dari <strong><?= htmlspecialchars($cm['nama_pengirim']) ?></strong>:</small>
                        <p class="small text-secondary mb-0"><?= nl2br(htmlspecialchars($cm['isi_komentar'])) ?></p>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-semibold small">Tulis Tanggapan Resmi Sekolah <span class="text-danger">*</span></label>
                        <textarea name="reply_text" class="form-control rounded-3" rows="4" placeholder="Ketik balasan resmi di sini..." required></textarea>
                      </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="submit_reply" class="btn btn-primary rounded-3">Kirim Balasan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
