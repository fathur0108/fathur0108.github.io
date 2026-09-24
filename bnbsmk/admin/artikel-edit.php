<?php
/**
 * Form Edit Artikel
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Edit Artikel";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    setFlash('danger', 'Artikel tidak ditemukan.');
    header("Location: " . $baseUrl . "/admin/artikel.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY nama_kategori ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrfToken)) {
        setFlash('danger', 'Token keamanan kedaluwarsa. Silakan refresh dan coba lagi.');
    } else {
        $judul = trim($_POST['judul'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
        $allowComments = isset($_POST['allow_comments']) ? 1 : 0;
        
        $errors = [];
        if (empty($judul)) $errors[] = 'Judul artikel wajib diisi.';
        if ($categoryId <= 0) $errors[] = 'Pilih kategori artikel.';
        if (empty($konten)) $errors[] = 'Konten artikel tidak boleh kosong.';

        // Handle Image Replacement
        $gambarSampul = $article['gambar_sampul'];
        if (isset($_FILES['gambar_sampul']) && $_FILES['gambar_sampul']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImage($_FILES['gambar_sampul'], 'uploads');
            if ($uploadResult['status']) {
                $gambarSampul = $uploadResult['fileName'];
            } else {
                $errors[] = $uploadResult['message'];
            }
        }

        if (empty($errors)) {
            if (empty($ringkasan)) {
                $ringkasan = mb_strimwidth(strip_tags($konten), 0, 160, '...');
            }

            $updateStmt = $pdo->prepare("
              UPDATE articles SET
                category_id = ?,
                judul = ?,
                ringkasan = ?,
                konten = ?,
                gambar_sampul = ?,
                status = ?,
                allow_comments = ?
              WHERE id = ?
            ");
            $updateStmt->execute([
                $categoryId,
                sanitize($judul),
                sanitize($ringkasan),
                $konten,
                $gambarSampul,
                $status,
                $allowComments,
                $id
            ]);

            setFlash('success', 'Perubahan pada artikel <strong>' . htmlspecialchars($judul) . '</strong> berhasil disimpan!');
            header("Location: " . $baseUrl . "/admin/artikel.php");
            exit;
        } else {
            setFlash('danger', implode('<br>', $errors));
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Edit Artikel</h3>
    <p class="text-muted small mb-0">Ubah konten, status publikasi, atau ganti gambar sampul artikel.</p>
  </div>
  <a href="<?= $baseUrl ?>/admin/artikel.php" class="btn btn-outline-secondary rounded-3">
    <i class="bi bi-arrow-left me-1"></i> Kembali
  </a>
</div>

<form action="<?= $baseUrl ?>/admin/artikel-edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" id="articleEditForm">
  <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
  <input type="hidden" name="konten" id="hiddenKonten">

  <div class="row g-4">
    <!-- Left Column: Title & Editor -->
    <div class="col-lg-8">
      <div class="admin-card mb-4">
        <div class="admin-card-body">
          <div class="mb-4">
            <label for="judul" class="form-label fw-bold text-dark">Judul Artikel <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-lg rounded-3" id="judul" name="judul" value="<?= htmlspecialchars($article['judul']) ?>" required>
          </div>

          <div class="mb-4">
            <label for="ringkasan" class="form-label fw-bold text-dark">Ringkasan Singkat</label>
            <textarea class="form-control rounded-3" id="ringkasan" name="ringkasan" rows="3"><?= htmlspecialchars($article['ringkasan']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Konten Lengkap Artikel <span class="text-danger">*</span></label>
            <!-- Quill Editor Container -->
            <div id="quill-editor" style="height: 380px; background: #ffffff;">
              <?= $article['konten'] ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Meta & Image -->
    <div class="col-lg-4">
      <div class="admin-card mb-4">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-send-check text-primary me-2"></i> Opsi Publikasi</h6>
        </div>
        <div class="admin-card-body">
          <div class="mb-3">
            <label for="status" class="form-label fw-semibold small">Status Publikasi</label>
            <select name="status" id="status" class="form-select rounded-3">
              <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-select rounded-3" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $article['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nama_kategori']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-check form-switch mt-3 pt-2 border-top">
            <input class="form-check-input" type="checkbox" role="switch" id="allow_comments" name="allow_comments" value="1" <?= $article['allow_comments'] ? 'checked' : '' ?>>
            <label class="form-check-label fw-semibold small" for="allow_comments">
              Izinkan Komentar Pengunjung
            </label>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold">
              <i class="bi bi-save me-1"></i> Perbarui Artikel
            </button>
          </div>
        </div>
      </div>

      <!-- Thumbnail -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h6 class="fw-bold mb-0"><i class="bi bi-image text-info me-2"></i> Gambar Sampul</h6>
        </div>
        <div class="admin-card-body text-center">
          <div class="mb-3">
            <img id="image-preview" src="<?= getImageUrl($article['gambar_sampul']) ?>" alt="Preview" class="img-fluid rounded-3 border shadow-sm" style="max-height: 180px; width: 100%; object-fit: cover;">
          </div>
          <input type="file" name="gambar_sampul" id="gambar_sampul" class="form-control form-control-sm rounded-3" accept="image/jpeg,image/png,image/webp">
          <small class="text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</small>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof Quill !== 'undefined') {
      const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ 'header': [1, 2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['blockquote', 'code-block'],
            [{ 'color': [] }, { 'background': [] }],
            ['link', 'clean']
          ]
        }
      });

      const form = document.getElementById('articleEditForm');
      form.addEventListener('submit', () => {
        document.getElementById('hiddenKonten').value = quill.root.innerHTML;
      });
    }

    const fileInput = document.getElementById('gambar_sampul');
    const imgPreview = document.getElementById('image-preview');
    if (fileInput && imgPreview) {
      fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            imgPreview.src = e.target.result;
          }
          reader.readAsDataURL(file);
        }
      });
    }
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
