<?php
/**
 * Manajemen Program Keahlian / Jurusan
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Kelola Program Keahlian";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Handle Add Jurusan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_jurusan'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama_jurusan'] ?? '');
        $ikon = trim($_POST['ikon'] ?? 'bi-laptop');
        $deskripsi = trim($_POST['deskripsi_singkat'] ?? '');
        $kompetensi = trim($_POST['kompetensi'] ?? '');
        $prospek = trim($_POST['prospek_karir'] ?? '');
        $urutan = (int)($_POST['urutan'] ?? 1);

        if (!empty($kode) && !empty($nama)) {
            $slug = createSlug($nama);
            $stmt = $pdo->prepare("
              INSERT INTO jurusan (kode, nama_jurusan, slug, ikon, deskripsi_singkat, kompetensi, prospek_karir, urutan)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                sanitize($kode),
                sanitize($nama),
                $slug,
                sanitize($ikon),
                sanitize($deskripsi),
                sanitize($kompetensi),
                sanitize($prospek),
                $urutan
            ]);
            setFlash('success', 'Program keahlian <strong>' . htmlspecialchars($nama) . '</strong> berhasil ditambahkan!');
        }
    }
    header("Location: " . $baseUrl . "/admin/jurusan.php");
    exit;
}

// Handle Edit Jurusan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_jurusan'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($csrfToken)) {
        $id = (int)$_POST['jurusan_id'];
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama_jurusan'] ?? '');
        $ikon = trim($_POST['ikon'] ?? 'bi-laptop');
        $deskripsi = trim($_POST['deskripsi_singkat'] ?? '');
        $kompetensi = trim($_POST['kompetensi'] ?? '');
        $prospek = trim($_POST['prospek_karir'] ?? '');
        $urutan = (int)($_POST['urutan'] ?? 1);

        if (!empty($kode) && !empty($nama)) {
            $stmt = $pdo->prepare("
              UPDATE jurusan SET
                kode = ?,
                nama_jurusan = ?,
                ikon = ?,
                deskripsi_singkat = ?,
                kompetensi = ?,
                prospek_karir = ?,
                urutan = ?
              WHERE id = ?
            ");
            $stmt->execute([
                sanitize($kode),
                sanitize($nama),
                sanitize($ikon),
                sanitize($deskripsi),
                sanitize($kompetensi),
                sanitize($prospek),
                $urutan,
                $id
            ]);
            setFlash('success', 'Program keahlian berhasil diperbarui!');
        }
    }
    header("Location: " . $baseUrl . "/admin/jurusan.php");
    exit;
}

// Handle Delete Jurusan
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $del = $pdo->prepare("DELETE FROM jurusan WHERE id = ?");
    $del->execute([$delId]);
    setFlash('success', 'Program keahlian berhasil dihapus.');
    header("Location: " . $baseUrl . "/admin/jurusan.php");
    exit;
}

$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY urutan ASC")->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Program Keahlian &amp; Jurusan</h3>
    <p class="text-muted small mb-0">Kelola informasi kompetensi keahlian dan prospek kerja siswa.</p>
  </div>
  <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addJurusanModal">
    <i class="bi bi-plus-lg me-1"></i> Tambah Program Keahlian
  </button>
</div>

<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-custom mb-0">
      <thead>
        <tr>
          <th>Logo</th>
          <th>Kode</th>
          <th>Nama Program Keahlian</th>
          <th>Deskripsi Singkat</th>
          <th>Urutan</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($jurusanList)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada jurusan.</td></tr>
        <?php else: ?>
          <?php foreach ($jurusanList as $j): ?>
            <tr>
              <td>
                <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                  <img src="<?= getImageUrl($j['gambar']) ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
              </td>
              <td><span class="badge bg-primary px-2 py-1"><?= htmlspecialchars($j['kode']) ?></span></td>
              <td><strong class="text-dark"><?= htmlspecialchars($j['nama_jurusan']) ?></strong></td>
              <td style="max-width: 320px;">
                <small class="text-secondary"><?= htmlspecialchars(mb_strimwidth($j['deskripsi_singkat'], 0, 80, '...')) ?></small>
              </td>
              <td><span class="badge bg-light text-dark border"><?= $j['urutan'] ?></span></td>
              <td class="text-end">
                <button class="btn btn-sm btn-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#editJurusanModal<?= $j['id'] ?>" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                <a href="<?= $baseUrl ?>/admin/jurusan.php?delete=<?= $j['id'] ?>" 
                   data-item="jurusan '<?= htmlspecialchars($j['nama_jurusan']) ?>'" 
                   class="btn btn-sm btn-danger btn-delete-confirm py-1 px-2" title="Hapus">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editJurusanModal<?= $j['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                  <form action="<?= $baseUrl ?>/admin/jurusan.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="jurusan_id" value="<?= $j['id'] ?>">

                    <div class="modal-header border-bottom-0 pb-0">
                      <h5 class="modal-title fw-bold">Edit Program Keahlian</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row g-3 mb-3">
                        <div class="col-md-4">
                          <label class="form-label fw-semibold small">Kode Singkat <span class="text-danger">*</span></label>
                          <input type="text" name="kode" class="form-control rounded-3" value="<?= htmlspecialchars($j['kode']) ?>" required>
                        </div>
                        <div class="col-md-5">
                          <label class="form-label fw-semibold small">Nama Program Keahlian <span class="text-danger">*</span></label>
                          <input type="text" name="nama_jurusan" class="form-control rounded-3" value="<?= htmlspecialchars($j['nama_jurusan']) ?>" required>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label fw-semibold small">Bootstrap Icon Class</label>
                          <input type="text" name="ikon" class="form-control rounded-3" value="<?= htmlspecialchars($j['ikon']) ?>">
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi Singkat <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_singkat" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($j['deskripsi_singkat']) ?></textarea>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-semibold small">Materi &amp; Kompetensi Utama</label>
                        <textarea name="kompetensi" class="form-control rounded-3" rows="3"><?= htmlspecialchars($j['kompetensi']) ?></textarea>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-9">
                          <label class="form-label fw-semibold small">Peluang &amp; Prospek Karir</label>
                          <textarea name="prospek_karir" class="form-control rounded-3" rows="2"><?= htmlspecialchars($j['prospek_karir']) ?></textarea>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label fw-semibold small">Nomor Urut Tampil</label>
                          <input type="number" name="urutan" class="form-control rounded-3" value="<?= $j['urutan'] ?>">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="edit_jurusan" class="btn btn-primary rounded-3">Simpan Perubahan</button>
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

<!-- Modal Tambah Jurusan -->
<div class="modal fade" id="addJurusanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4 border-0 shadow">
      <form action="<?= $baseUrl ?>/admin/jurusan.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-grid-fill text-primary me-2"></i> Tambah Program Keahlian Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Kode Singkat <span class="text-danger">*</span></label>
              <input type="text" name="kode" class="form-control rounded-3" placeholder="Misal: RPL" required>
            </div>
            <div class="col-md-5">
              <label class="form-label fw-semibold small">Nama Program Keahlian <span class="text-danger">*</span></label>
              <input type="text" name="nama_jurusan" class="form-control rounded-3" placeholder="Misal: Rekayasa Perangkat Lunak" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Bootstrap Icon Class</label>
              <input type="text" name="ikon" class="form-control rounded-3" value="bi-code-slash" placeholder="bi-laptop">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Deskripsi Singkat <span class="text-danger">*</span></label>
            <textarea name="deskripsi_singkat" class="form-control rounded-3" rows="3" placeholder="Gambaran umum kompetensi keahlian ini..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Materi &amp; Kompetensi Utama</label>
            <textarea name="kompetensi" class="form-control rounded-3" rows="3" placeholder="Daftar kompetensi yang dipelajari..."></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-9">
              <label class="form-label fw-semibold small">Peluang &amp; Prospek Karir</label>
              <textarea name="prospek_karir" class="form-control rounded-3" rows="2" placeholder="Contoh: Web Developer, Mobile Engineer, QA Tester"></textarea>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Nomor Urut Tampil</label>
              <input type="number" name="urutan" class="form-control rounded-3" value="1">
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="add_jurusan" class="btn btn-primary rounded-3">Tambah Jurusan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
