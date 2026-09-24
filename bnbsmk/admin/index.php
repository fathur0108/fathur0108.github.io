<?php
/**
 * Dashboard Utama Panel Admin
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Dashboard Overview";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Quick action: approve comment from dashboard
if (isset($_GET['approve_comment'])) {
    $cid = (int)$_GET['approve_comment'];
    $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
    $stmt->execute([$cid]);
    setFlash('success', 'Komentar berhasil disetujui dan dipublikasikan!');
    header("Location: " . $baseUrl . "/admin/index.php");
    exit;
}

// 1. Metrics Statistics
$totalArticles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalViews = $pdo->query("SELECT SUM(views) FROM articles")->fetchColumn() ?: 0;
$totalComments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$pendingComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// 2. Recent Articles
$recentArticles = $pdo->query("
  SELECT a.id, a.judul, a.slug, a.views, a.status, a.created_at, c.nama_kategori,
         (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id) AS comment_count
  FROM articles a
  LEFT JOIN categories c ON a.category_id = c.id
  ORDER BY a.created_at DESC
  LIMIT 5
")->fetchAll();

// 3. Recent Comments
$recentComments = $pdo->query("
  SELECT cm.*, a.judul AS article_title, a.slug AS article_slug
  FROM comments cm
  LEFT JOIN articles a ON cm.article_id = a.id
  ORDER BY cm.created_at DESC
  LIMIT 5
")->fetchAll();
?>

<!-- Page Header Title -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Ringkasan &amp; Analitik Website</h3>
    <p class="text-muted small mb-0">Pantau performa publikasi artikel, statistik pembaca, dan interaksi komentar terkini.</p>
  </div>
  <div class="mt-3 mt-md-0 d-flex gap-2">
    <a href="<?= $baseUrl ?>/admin/artikel-tambah.php" class="btn btn-primary rounded-3">
      <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
    </a>
  </div>
</div>

<!-- 1. Statistics Cards -->
<div class="row g-4 mb-4">
  <!-- Total Artikel -->
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card">
      <div>
        <div class="stat-label">Total Artikel</div>
        <div class="stat-value"><?= number_format($totalArticles) ?></div>
        <small class="text-primary fw-semibold"><i class="bi bi-file-earmark-text"></i> Published &amp; Draft</small>
      </div>
      <div class="stat-icon blue">
        <i class="bi bi-newspaper"></i>
      </div>
    </div>
  </div>

  <!-- Total Pembaca (Views) -->
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card stat-green">
      <div>
        <div class="stat-label">Total Pembaca</div>
        <div class="stat-value"><?= number_format($totalViews) ?></div>
        <small class="text-success fw-semibold"><i class="bi bi-graph-up-arrow"></i> Tayangan Halaman</small>
      </div>
      <div class="stat-icon green">
        <i class="bi bi-eye"></i>
      </div>
    </div>
  </div>

  <!-- Komentar & Moderasi -->
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card stat-amber">
      <div>
        <div class="stat-label">Total Komentar</div>
        <div class="stat-value"><?= number_format($totalComments) ?></div>
        <?php if ($pendingComments > 0): ?>
          <small class="text-danger fw-bold"><i class="bi bi-exclamation-circle-fill"></i> <?= $pendingComments ?> Menunggu Review</small>
        <?php else: ?>
          <small class="text-muted"><i class="bi bi-check2-all"></i> Semua Termoderasi</small>
        <?php endif; ?>
      </div>
      <div class="stat-icon amber">
        <i class="bi bi-chat-square-dots"></i>
      </div>
    </div>
  </div>

  <!-- Kategori Aktif -->
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card stat-cyan">
      <div>
        <div class="stat-label">Kategori Berita</div>
        <div class="stat-value"><?= number_format($totalCategories) ?></div>
        <small class="text-info fw-semibold"><i class="bi bi-tags"></i> Topik Terdaftar</small>
      </div>
      <div class="stat-icon cyan">
        <i class="bi bi-bookmark-star"></i>
      </div>
    </div>
  </div>
</div>

<!-- 2. Readership Chart & Quick Actions -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-activity text-primary me-2"></i> Tren Pembaca Mingguan (Analytics)</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">7 Hari Terakhir</span>
      </div>
      <div class="admin-card-body">
        <div style="height: 280px; position: relative;">
          <canvas id="analyticsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Aksi Cepat</h5>
      </div>
      <div class="admin-card-body d-flex flex-column gap-3 justify-content-center">
        <a href="<?= $baseUrl ?>/admin/artikel-tambah.php" class="p-3 rounded-3 border bg-light text-decoration-none d-flex align-items-center gap-3 hover-shadow">
          <div class="stat-icon blue" style="width: 44px; height: 44px; font-size: 1.2rem;">
            <i class="bi bi-pencil-square"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-0 text-dark">Buat Artikel Baru</h6>
            <small class="text-muted">Tulis berita dan upload media</small>
          </div>
        </a>

        <a href="<?= $baseUrl ?>/admin/komentar.php" class="p-3 rounded-3 border bg-light text-decoration-none d-flex align-items-center gap-3 hover-shadow">
          <div class="stat-icon amber" style="width: 44px; height: 44px; font-size: 1.2rem;">
            <i class="bi bi-chat-square-quote"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-0 text-dark">Moderasi Komentar</h6>
            <small class="text-muted"><?= $pendingComments ?> komentar menunggu verifikasi</small>
          </div>
        </a>

        <a href="<?= $baseUrl ?>/admin/kategori.php" class="p-3 rounded-3 border bg-light text-decoration-none d-flex align-items-center gap-3 hover-shadow">
          <div class="stat-icon cyan" style="width: 44px; height: 44px; font-size: 1.2rem;">
            <i class="bi bi-folder-plus"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-0 text-dark">Kelola Kategori</h6>
            <small class="text-muted">Tambah atau ubah kategori warta</small>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- 3. Recent Articles Table -->
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-richtext text-primary me-2"></i> Artikel Terkini</h5>
    <a href="<?= $baseUrl ?>/admin/artikel.php" class="btn btn-sm btn-outline-primary">Lihat Semua Artikel</a>
  </div>
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Judul Artikel</th>
          <th>Kategori</th>
          <th>Statistik</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentArticles)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada artikel.</td></tr>
        <?php else: ?>
          <?php foreach ($recentArticles as $art): ?>
            <tr>
              <td>
                <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                  <?= htmlspecialchars(mb_strimwidth($art['judul'], 0, 55, '...')) ?>
                </a>
              </td>
              <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($art['nama_kategori'] ?? 'Uncategorized') ?></span></td>
              <td>
                <small class="text-muted">
                  <i class="bi bi-eye text-primary"></i> <?= $art['views'] ?> &bull; 
                  <i class="bi bi-chat text-info"></i> <?= $art['comment_count'] ?>
                </small>
              </td>
              <td>
                <?php if ($art['status'] === 'published'): ?>
                  <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Published</span>
                <?php else: ?>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">Draft</span>
                <?php endif; ?>
              </td>
              <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($art['created_at'])) ?></small></td>
              <td class="text-end">
                <a href="<?= $baseUrl ?>/admin/artikel-edit.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 4. Recent Comments Table (Anonymous & Named indicators) -->
<div class="admin-card">
  <div class="admin-card-header">
    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-chat-quote-fill text-danger me-2"></i> Komentar &amp; Tanggapan Terbaru</h5>
    <a href="<?= $baseUrl ?>/admin/komentar.php" class="btn btn-sm btn-outline-danger">Semua Komentar</a>
  </div>
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Pengirim</th>
          <th>Tipe Pengirim</th>
          <th>Isi Komentar</th>
          <th>Pada Artikel</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentComments)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada komentar.</td></tr>
        <?php else: ?>
          <?php foreach ($recentComments as $cm): ?>
            <tr>
              <td>
                <div class="fw-bold text-dark"><?= htmlspecialchars($cm['nama_pengirim']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($cm['email_pengirim'] ?? 'Disembunyikan (Anonim)') ?></small>
              </td>
              <td>
                <?php if ($cm['is_anonymous']): ?>
                  <span class="badge bg-dark bg-opacity-75 text-info px-2 py-1"><i class="bi bi-incognito me-1"></i> Anonim</span>
                <?php else: ?>
                  <span class="badge bg-info bg-opacity-10 text-primary border border-info border-opacity-25 px-2 py-1"><i class="bi bi-person-check me-1"></i> Teridentifikasi</span>
                <?php endif; ?>
              </td>
              <td style="max-width: 250px;">
                <span class="small text-secondary"><?= htmlspecialchars(mb_strimwidth($cm['isi_komentar'], 0, 75, '...')) ?></span>
              </td>
              <td>
                <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($cm['article_slug'] ?? '') ?>" target="_blank" class="small text-dark text-decoration-none">
                  <?= htmlspecialchars(mb_strimwidth($cm['article_title'] ?? '-', 0, 35, '...')) ?>
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
              <td class="text-end">
                <?php if ($cm['status'] === 'pending'): ?>
                  <a href="<?= $baseUrl ?>/admin/index.php?approve_comment=<?= $cm['id'] ?>" class="btn btn-sm btn-success py-1 px-2" title="Setujui">
                    <i class="bi bi-check-lg"></i>
                  </a>
                <?php endif; ?>
                <a href="<?= $baseUrl ?>/admin/komentar.php" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Kelola">
                  <i class="bi bi-gear"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
