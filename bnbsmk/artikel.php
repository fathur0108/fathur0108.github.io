<?php
/**
 * Halaman Indeks Berita & Artikel
 * SMK Bangun Nusa Bangsa
 */
$pageTitle = "Warta & Artikel Terkini";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Filter parameters
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$categorySlug = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Base query builder
$where = ["a.status = 'published'"];
$params = [];

if (!empty($search)) {
    $where[] = "(a.judul LIKE ? OR a.ringkasan LIKE ? OR a.konten LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($categorySlug)) {
    $where[] = "c.slug = ?";
    $params[] = $categorySlug;
}

$whereClause = implode(" AND ", $where);

// Count total articles for pagination
$countSql = "SELECT COUNT(*) FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE {$whereClause}";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalArticles = $countStmt->fetchColumn();
$totalPages = ceil($totalArticles / $limit);

// Fetch articles
$articlesSql = "
  SELECT a.*, c.nama_kategori, c.slug AS cat_slug, u.nama_lengkap AS author_name,
         (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id AND cm.status = 'approved') AS total_comments
  FROM articles a
  LEFT JOIN categories c ON a.category_id = c.id
  LEFT JOIN users u ON a.user_id = u.id
  WHERE {$whereClause}
  ORDER BY a.created_at DESC
  LIMIT {$limit} OFFSET {$offset}
";
$articlesStmt = $pdo->prepare($articlesSql);
$articlesStmt->execute($params);
$articles = $articlesStmt->fetchAll();

// Fetch all categories for sidebar/filter
$categories = $pdo->query("
  SELECT c.*, (SELECT COUNT(*) FROM articles a WHERE a.category_id = c.id AND a.status = 'published') AS total_art
  FROM categories c
  ORDER BY c.nama_kategori ASC
")->fetchAll();

// Fetch Popular Articles for sidebar
$popularArticles = $pdo->query("
  SELECT judul, slug, views, created_at, gambar_sampul
  FROM articles
  WHERE status = 'published'
  ORDER BY views DESC, created_at DESC
  LIMIT 4
")->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 20%, #1e293b 0%, #090e1a 100%);">
  <div class="container py-4 text-center position-relative" style="z-index: 2;">
    <span class="badge-glow mb-2"><i class="bi bi-newspaper"></i> WARTA &amp; MEDIA PUBLIKASI</span>
    <h1 class="hero-title mb-2" style="font-size: 2.8rem;">Artikel &amp; Kabar SMK BNB</h1>
    <p class="text-white-50 mx-auto" style="max-width: 600px;">
      Informasi resmi, prestasi siswa, warta kegiatan, teknologi, dan panduan akademik terkini.
    </p>
  </div>
</section>

<!-- Main Article List & Sidebar -->
<section class="py-5 bg-white">
  <div class="container py-lg-3">
    <!-- Category Pills Filter -->
    <div class="d-flex flex-wrap gap-2 mb-4 pb-3 border-bottom align-items-center">
      <span class="fw-bold text-dark small me-2"><i class="bi bi-funnel-fill text-primary"></i> Kategori:</span>
      <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-sm <?= empty($categorySlug) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
        Semua Kategori
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= $baseUrl ?>/artikel.php?kategori=<?= urlencode($cat['slug']) ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" 
           class="btn btn-sm <?= ($categorySlug === $cat['slug']) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
          <?= htmlspecialchars($cat['nama_kategori']) ?> (<?= $cat['total_art'] ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <div class="row g-5">
      <!-- Main Column: Article Grid -->
      <div class="col-lg-8">
        <?php if (!empty($search) || !empty($categorySlug)): ?>
          <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4">
            <div>
              <span class="text-muted">Menampilkan hasil untuk:</span>
              <?php if (!empty($search)): ?>
                <strong>Kata kunci "<?= htmlspecialchars($search) ?>"</strong>
              <?php endif; ?>
              <?php if (!empty($categorySlug)): ?>
                <span class="badge bg-info text-dark ms-2">Kategori: <?= htmlspecialchars($categorySlug) ?></span>
              <?php endif; ?>
            </div>
            <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-sm btn-outline-danger">Reset Filter</a>
          </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
          <div class="text-center py-5 bg-light rounded-4 my-3">
            <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
            <h5 class="fw-bold text-dark">Tidak ada artikel ditemukan</h5>
            <p class="text-muted small">Coba cari dengan kata kunci lain atau pilih kategori berbeda.</p>
            <a href="<?= $baseUrl ?>/artikel.php" class="btn btn-bnb-primary btn-sm mt-2">Lihat Semua Artikel</a>
          </div>
        <?php else: ?>
          <div class="row g-4">
            <?php foreach ($articles as $art): ?>
              <div class="col-md-6">
                <article class="article-card">
                  <div class="article-thumbnail-wrapper">
                    <img src="<?= getImageUrl($art['gambar_sampul']) ?>" alt="<?= htmlspecialchars($art['judul']) ?>" class="article-thumbnail">
                    <span class="article-category-badge">
                      <?= htmlspecialchars($art['nama_kategori']) ?>
                    </span>
                  </div>
                  <div class="article-body">
                    <div class="article-meta">
                      <span><i class="bi bi-calendar3"></i> <?= formatTanggalIndo($art['created_at']) ?></span>
                      <span><i class="bi bi-eye"></i> <?= $art['views'] ?></span>
                      <span><i class="bi bi-chat-dots"></i> <?= $art['total_comments'] ?></span>
                    </div>
                    <h3 class="article-title">
                      <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>">
                        <?= htmlspecialchars($art['judul']) ?>
                      </a>
                    </h3>
                    <p class="article-excerpt">
                      <?= htmlspecialchars(mb_strimwidth($art['ringkasan'], 0, 110, "...")) ?>
                    </p>
                    <div class="pt-3 border-top border-light-subtle d-flex align-items-center justify-content-between mt-auto">
                      <span class="small text-muted"><i class="bi bi-clock me-1"></i> <?= estimateReadingTime($art['konten']) ?> min baca</span>
                      <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="fw-bold text-primary text-decoration-none small">
                        Baca <i class="bi bi-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </article>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
            <nav class="mt-5" aria-label="Navigasi Halaman">
              <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>">
                    <i class="bi bi-chevron-left"></i>
                  </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>">
                    <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Search Box -->
        <div class="p-4 rounded-4 bg-light border mb-4">
          <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-search text-primary me-2"></i> Cari Artikel</h5>
          <form action="<?= $baseUrl ?>/artikel.php" method="GET">
            <?php if (!empty($categorySlug)): ?>
              <input type="hidden" name="kategori" value="<?= htmlspecialchars($categorySlug) ?>">
            <?php endif; ?>
            <div class="input-group">
              <input type="text" name="q" class="form-control rounded-start-3" placeholder="Ketik topik artikel..." value="<?= htmlspecialchars($search) ?>" required>
              <button class="btn btn-primary rounded-end-3 px-3" type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Popular Articles Widget -->
        <div class="p-4 rounded-4 bg-light border mb-4">
          <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-fire text-danger me-2"></i> Artikel Terpopuler</h5>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($popularArticles as $pop): ?>
              <div class="d-flex gap-3 align-items-center">
                <img src="<?= getImageUrl($pop['gambar_sampul']) ?>" alt="<?= htmlspecialchars($pop['judul']) ?>" class="rounded-3" style="width: 75px; height: 60px; object-fit: cover; flex-shrink: 0;">
                <div>
                  <h6 class="mb-1" style="font-size: 0.92rem; line-height: 1.35;">
                    <a href="<?= $baseUrl ?>/artikel-detail.php?slug=<?= urlencode($pop['slug']) ?>" class="text-dark fw-bold text-decoration-none">
                      <?= htmlspecialchars(mb_strimwidth($pop['judul'], 0, 50, '...')) ?>
                    </a>
                  </h6>
                  <small class="text-muted"><i class="bi bi-eye text-primary"></i> <?= $pop['views'] ?> views &bull; <?= formatTanggalIndo($pop['created_at']) ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Banner PPDB Info -->
        <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #1e40af 0%, #06b6d4 100%);">
          <h5 class="fw-bold mb-2"><i class="bi bi-mortarboard-fill me-2"></i> Pendaftaran PPDB 2026</h5>
          <p class="small text-white-50 mb-3">Raih beasiswa prestasi penuh dan bergabunglah bersama keluarga besar SMK Bangun Nusa Bangsa!</p>
          <a href="<?= $baseUrl ?>/artikel.php?kategori=info-ppdb-beasiswa" class="btn btn-light btn-sm fw-bold px-3 py-2 w-100 rounded-3">
            Lihat Persyaratan &amp; Alur
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
