/**
 * SMK BANGUN NUSA BANGSA - ADMIN PANEL JAVASCRIPT
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sidebar Toggle for Mobile
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });
  }

  // 2. SweetAlert2 Delete Confirmation
  const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      const itemTitle = this.getAttribute('data-item') || 'data ini';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Apakah Anda Yakin?',
          text: `Akan menghapus ${itemTitle}. Tindakan ini tidak dapat dibatalkan!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      } else {
        if (confirm(`Apakah Anda yakin ingin menghapus ${itemTitle}?`)) {
          window.location.href = href;
        }
      }
    });
  });

  // 3. Render Dashboard Chart if canvas exists
  const chartCanvas = document.getElementById('analyticsChart');
  if (chartCanvas && typeof Chart !== 'undefined') {
    const ctx = chartCanvas.getContext('2d');
    
    // Gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
          label: 'Total Pembaca Artikel (Views)',
          data: [145, 230, 185, 310, 420, 290, 520],
          borderColor: '#2563eb',
          borderWidth: 3,
          backgroundColor: gradient,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#2563eb',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#0f172a',
            padding: 12,
            titleFont: { family: 'Outfit', size: 14 },
            bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
            cornerRadius: 8
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } }
          },
          y: {
            grid: { color: '#f1f5f9' },
            ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } }
          }
        }
      }
    });
  }
});
