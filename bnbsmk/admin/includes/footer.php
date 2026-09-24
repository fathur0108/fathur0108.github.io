    </main>
    
    <!-- Admin Footer -->
    <footer class="py-3 px-4 bg-white border-top text-muted small text-center text-md-start d-flex justify-content-between">
      <div>&copy; <?= date('Y') ?> <strong><?= htmlspecialchars($school['nama_sekolah']) ?></strong> CMS Panel</div>
      <div class="d-none d-md-block">Versi 2.0 (PHP Native + 3D Engine)</div>
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Quill Rich Text Editor CDN -->
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
  <!-- Admin Scripts -->
  <script src="<?= $baseUrl ?>/assets/js/admin.js"></script>
</body>
</html>
