<?php
/**
 * Manajemen Daftar Artikel
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Kelola Artikel";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Handle Delete Article
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    // Delete article
    $delStmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $delStmt->execute([$delId]);
    setFlash('success', 'Artikel berhasil dihapus dari sistem.');
    header("Location: " . $baseUrl . "/admin/artikel.php");
    exit;
}

// Handle Quick Status Toggle
if (isset($_GET['toggle_status'])) {
    $artId = (int)$_GET['toggle_status'];
    $currentStatus = $_GET['current'] === 'published' ? 'draft' : 'published';
    $togStmt = $pdo->prepare("UPDATE articles SET status = ? WHERE id = ?");
    $togStmt->execute([$currentStatus, $artId]);
    setFlash('info', 'Status artikel berhasil diubah menjadi <strong>' . $currentStatus . '</strong>.');
    header("Location: " . $baseUrl . "/admin/artikel.php");
    exit;
}

// Filter & Search
$search = trim($_GET['q'] ?? '');
$categoryFilter = trim($_GET['cat'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$where = ["1=1"];
$params = [];

if (!empty($search)) {
    $where[] = "(a.judul LIKE ? OR a.ringkasan LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (!empty($categoryFilter)) {
    $where[] = "a.category_id = ?";
    $params[] = $categoryFilter;
}

if (!empty($statusFilter)) {
    $where[] = "a.status = ?";
    $params[] = $statusFilter;
}

$whereClause = implode(" AND ", $where);

$sql = "
  SELECT a.*, c.nama_kategori, u.nama_lengkap AS author_name,
         (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id) AS total_comments
  FROM articles a
  LEFT JOIN categories c ON a.category_id = c.id
  LEFT JOIN users u ON a.user_id = u.id
  WHERE {$whereClause}
  ORDER BY a.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll();

// Categories for filter dropdown
$categories = $pdo->query("SELECT id, nama_kategori FROM categories ORDER BY nama_kategori ASC")->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Manajemen Artikel &amp; Berita</h3>
    <p class="text-muted small mb-0">Kelola semua publikasi warta, berita kegiatan, dan panduan akademik.</p>
  </div>
  <div class="mt-3 mt-md-0">
    <a href="<?= $baseUrl ?>/admin/artikel-tambah.php" class="btn btn-primary rounded-3">
      <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
    </a>
  </div>
</div>

<!-- Filter Bar -->
<div class="admin-card mb-4">
  <div class="admin-card-body p-3">
    <form action="<?= $baseUrl ?>/admin/artikel.php" method="GET" class="row g-2 align-items-center">
      <div class="col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="q" class="form-control border-start-0" placeholder="Cari judul artikel..." value="<?= htmlspecialchars($search) ?>">
        </div>
      </div>
      <div class="col-md-3">
        <select name="cat" class="form-select">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nama_kategori']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          <option value="published" <?= $statusFilter === 'published' ? 'selected' : '' ?>>Published</option>
          <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filter</button>
        <?php if (!empty($search) || !empty($categoryFilter) || !empty($statusFilter)): ?>
          <a href="<?= $baseUrl ?>/admin/artikel.php" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Articles Table -->
<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Thumbnail</th>
          <th>Judul Artikel</th>
          <th>Kategori</th>
          <th>Penulis</th>
          <th>Statistik</th>
          <th>Status</th>
          <th>Tanggal Rilis</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>
              Tidak ada artikel yang cocok dengan filter pencarian.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($articles as $art): ?>
            <tr>
              <td style="width: 70px;">
                <img src="<?= getImageUrl($art['gambar_sampul']) ?>" alt="Thumbnail" class="rounded-3" style="width: 60px; height: 45px; object-fit: cover;">
              </td>
              <td>
                <div class="fw-bold text-dark mb-1">
                  <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" target="_blank" class="text-dark text-decoration-none">
                    <?= htmlspecialchars($art['judul']) ?>
                  </a>
                </div>
                <small class="text-muted"><i class="bi bi-link-45deg"></i> <?= htmlspecialchars($art['slug']) ?></small>
              </td>
              <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($art['nama_kategori'] ?? 'Uncategorized') ?></span></td>
              <td><small class="text-muted"><?= htmlspecialchars($art['author_name'] ?? 'Admin') ?></small></td>
              <td>
                <small class="text-muted d-block"><i class="bi bi-eye text-primary"></i> <?= $art['views'] ?> views</small>
                <small class="text-muted d-block"><i class="bi bi-chat-dots text-info"></i> <?= $art['total_comments'] ?> komentar</small>
              </td>
              <td>
                <a href="<?= $baseUrl ?>/admin/artikel.php?toggle_status=<?= $art['id'] ?>&current=<?= $art['status'] ?>" 
                   class="text-decoration-none" title="Klik untuk mengubah status">
                  <?php if ($art['status'] === 'published'): ?>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                      <i class="bi bi-check-circle-fill me-1"></i> Published
                    </span>
                  <?php else: ?>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">
                      <i class="bi bi-hourglass-split me-1"></i> Draft
                    </span>
                  <?php endif; ?>
                </a>
              </td>
              <td><small class="text-muted"><?= date('d/m/Y', strtotime($art['created_at'])) ?></small></td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" target="_blank" class="btn btn-sm btn-light border" title="Lihat di Web">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= $baseUrl ?>/admin/artikel-edit.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-primary" title="Edit Artikel">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="<?= $baseUrl ?>/admin/artikel.php?delete=<?= $art['id'] ?>" 
                     data-item="artikel '<?= htmlspecialchars($art['judul']) ?>'" 
                     class="btn btn-sm btn-danger btn-delete-confirm" title="Hapus Artikel">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
