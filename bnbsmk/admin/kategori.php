<?php
/**
 * Manajemen Kategori Artikel
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Kategori Artikel";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// 1. Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $nama = trim($_POST['nama_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if (!empty($nama)) {
            $slug = createSlug($nama);
            // Check unique
            $chk = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
            $chk->execute([$slug]);
            if ($chk->fetch()) {
                $slug .= '-' . rand(10, 99);
            }

            $stmt = $pdo->prepare("INSERT INTO categories (nama_kategori, slug, deskripsi) VALUES (?, ?, ?)");
            $stmt->execute([sanitize($nama), $slug, sanitize($deskripsi)]);
            setFlash('success', 'Kategori <strong>' . htmlspecialchars($nama) . '</strong> berhasil ditambahkan!');
        } else {
            setFlash('danger', 'Nama kategori tidak boleh kosong.');
        }
    }
    header("Location: " . $baseUrl . "/admin/kategori.php");
    exit;
}

// 2. Handle Update Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $catId = (int)$_POST['category_id'];
        $nama = trim($_POST['nama_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if (!empty($nama)) {
            $stmt = $pdo->prepare("UPDATE categories SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
            $stmt->execute([sanitize($nama), sanitize($deskripsi), $catId]);
            setFlash('success', 'Kategori berhasil diperbarui!');
        }
    }
    header("Location: " . $baseUrl . "/admin/kategori.php");
    exit;
}

// 3. Handle Delete Category
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    // Cek apakah ada artikel di kategori ini
    $count = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE category_id = ?");
    $count->execute([$delId]);
    if ($count->fetchColumn() > 0) {
        setFlash('danger', 'Tidak dapat menghapus kategori ini karena masih memiliki artikel terkait.');
    } else {
        $del = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $del->execute([$delId]);
        setFlash('success', 'Kategori berhasil dihapus.');
    }
    header("Location: " . $baseUrl . "/admin/kategori.php");
    exit;
}

// Fetch categories with total articles
$categories = $pdo->query("
  SELECT c.*, (SELECT COUNT(*) FROM articles a WHERE a.category_id = c.id) AS total_art
  FROM categories c
  ORDER BY c.created_at DESC
")->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Kategori Artikel</h3>
    <p class="text-muted small mb-0">Kelola kelompok topik dan klasifikasi warta sekolah.</p>
  </div>
  <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addCatModal">
    <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
  </button>
</div>

<!-- Categories Table -->
<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Nama Kategori</th>
          <th>Slug (URL)</th>
          <th>Deskripsi</th>
          <th>Jumlah Artikel</th>
          <th>Dibuat Tanggal</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($categories)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
        <?php else: ?>
          <?php foreach ($categories as $c): ?>
            <tr>
              <td><strong class="text-dark"><?= htmlspecialchars($c['nama_kategori']) ?></strong></td>
              <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
              <td><small class="text-muted"><?= htmlspecialchars($c['deskripsi'] ?: '-') ?></small></td>
              <td>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                  <?= $c['total_art'] ?> Artikel
                </span>
              </td>
              <td><small class="text-muted"><?= date('d/m/Y', strtotime($c['created_at'])) ?></small></td>
              <td class="text-end">
                <button class="btn btn-sm btn-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                <a href="<?= $baseUrl ?>/admin/kategori.php?delete=<?= $c['id'] ?>" 
                   data-item="kategori '<?= htmlspecialchars($c['nama_kategori']) ?>'" 
                   class="btn btn-sm btn-danger btn-delete-confirm py-1 px-2" title="Hapus">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>

            <!-- Modal Edit Kategori -->
            <div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content rounded-4 border-0 shadow">
                  <form action="<?= $baseUrl ?>/admin/kategori.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="category_id" value="<?= $c['id'] ?>">
                    <div class="modal-header border-bottom-0 pb-0">
                      <h5 class="modal-title fw-bold">Edit Kategori</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control rounded-3" value="<?= htmlspecialchars($c['nama_kategori']) ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3"><?= htmlspecialchars($c['deskripsi']) ?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="edit_category" class="btn btn-primary rounded-3">Simpan Perubahan</button>
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow">
      <form action="<?= $baseUrl ?>/admin/kategori.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-tag-fill text-primary me-2"></i> Tambah Kategori Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama_kategori" class="form-control rounded-3" placeholder="Misal: Info Beasiswa &amp; Prestasi" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Deskripsi</label>
            <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Penjelasan singkat mengenai kategori ini..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="add_category" class="btn btn-primary rounded-3">Tambah Kategori</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
