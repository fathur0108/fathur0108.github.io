/**
 * SMK BANGUN NUSA BANGSA - MAIN FRONTEND SCRIPTS
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Navbar Scroll Effect
  const navbar = document.querySelector('.navbar-bnb');
  if (navbar) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // 2. 3D Tilt Effect on Cards
  if (typeof VanillaTilt !== 'undefined') {
    VanillaTilt.init(document.querySelectorAll('.card-3d-tilt'), {
      max: 12,
      speed: 400,
      glare: true,
      "max-glare": 0.15,
      scale: 1.02,
      perspective: 1000
    });

    VanillaTilt.init(document.querySelectorAll('.hero-floating-card-3d'), {
      max: 8,
      speed: 600,
      glare: true,
      "max-glare": 0.25,
      perspective: 1200
    });
  }

  // 3. Anonymous Comment Toggle Logic
  const anonCheckbox = document.getElementById('is_anonymous');
  const nameInput = document.getElementById('nama_pengirim');
  const emailInput = document.getElementById('email_pengirim');
  const emailHelp = document.getElementById('email-help-text');
  const nameLabel = document.getElementById('label-nama-pengirim');

  if (anonCheckbox && nameInput) {
    function toggleAnonymousState() {
      if (anonCheckbox.checked) {
        // Mode Anonim Aktif
        if (nameInput.value.trim() === '' || nameInput.value === 'Anonim') {
          nameInput.value = 'Anonim';
        }
        nameInput.placeholder = 'Misal: Siswa BNB / Anonim';
        if (nameLabel) {
          nameLabel.innerHTML = 'Nama Samaran / Panggilan <span class="badge bg-secondary">Mode Anonim</span>';
        }
        if (emailInput) {
          emailInput.required = false;
          emailInput.disabled = true;
          emailInput.value = '';
          emailInput.placeholder = 'Email disembunyikan (Mode Anonim)';
        }
        if (emailHelp) {
          emailHelp.textContent = 'Dalam mode anonim, email tidak diperlukan dan identitas Anda aman.';
        }
      } else {
        // Mode Identitas Lengkap
        if (nameInput.value === 'Anonim') {
          nameInput.value = '';
        }
        nameInput.placeholder = 'Masukkan nama lengkap Anda';
        if (nameLabel) {
          nameLabel.innerHTML = 'Nama Lengkap <span class="text-danger">*</span>';
        }
        if (emailInput) {
          emailInput.disabled = false;
          emailInput.placeholder = 'nama@email.com';
        }
        if (emailHelp) {
          emailHelp.textContent = 'Alamat email tidak akan dipublikasikan ke publik.';
        }
      }
    }

    anonCheckbox.addEventListener('change', toggleAnonymousState);
    toggleAnonymousState(); // Initial run on load
  }

  // 4. Auto-dismiss Alert messages after 5 seconds
  const autoAlerts = document.querySelectorAll('.alert-dismissible');
  autoAlerts.forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      if (bsAlert) bsAlert.close();
    }, 6000);
  });
});
