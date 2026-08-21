/**
 * SMK BANGUN NUSA BANGSA - INTERACTIVE SCRIPTS
 * Dynamic components, animations, modals, and WhatsApp form integration.
 */

// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', () => {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      once: true,
      duration: 800,
      offset: 60,
      easing: 'ease-out-cubic'
    });
  }

  // Init Counter Observer
  initCounterObserver();

  // Active Nav Scroll Spy
  initScrollSpy();
});

// --- Sticky Navbar & Back to Top Button ---
const navbar = document.getElementById('navbar');
const backToTopBtn = document.getElementById('backToTop');

window.addEventListener('scroll', () => {
  const scrollY = window.scrollY;

  // Navbar Background Shrink Effect
  if (scrollY > 60) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }

  // Back to top button visibility
  if (scrollY > 400) {
    backToTopBtn.classList.add('show');
  } else {
    backToTopBtn.classList.remove('show');
  }
});

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

// --- Mobile Navigation Drawer Toggle ---
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');

if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.toggle('open');
    navToggle.classList.toggle('active');
  });

  // Close menu when clicking link
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
      navMenu.classList.remove('open');
      navToggle.classList.remove('active');
    });
  });
}

// --- Smooth Scroll Spy ---
function initScrollSpy() {
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    const scrollPosition = window.scrollY + 200;

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${current}`) {
        link.classList.add('active');
      }
    });
  });
}

// --- Statistics Number Counter Animation ---
function initCounterObserver() {
  const counters = document.querySelectorAll('.counter');
  let animated = false;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !animated) {
        counters.forEach(counter => {
          const target = +counter.getAttribute('data-target');
          const duration = 1800; // ms
          const stepTime = 25;
          const totalSteps = duration / stepTime;
          const increment = target / totalSteps;
          let current = 0;

          const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
              counter.textContent = target;
              clearInterval(timer);
            } else {
              counter.textContent = Math.ceil(current);
            }
          }, stepTime);
        });
        animated = true;
      }
    });
  }, { threshold: 0.4 });

  const statsSection = document.querySelector('.stats-banner');
  if (statsSection) {
    observer.observe(statsSection);
  }
}

// --- Facility Filtering Tabs ---
function filterFacility(category, btnElement) {
  // Update Tab Active Class
  const tabs = document.querySelectorAll('.filter-tab-btn');
  tabs.forEach(tab => tab.classList.remove('active'));
  btnElement.classList.add('active');

  // Filter Items
  const items = document.querySelectorAll('.facility-item');
  items.forEach(item => {
    const itemCat = item.getAttribute('data-cat');
    if (category === 'all' || itemCat === category) {
      item.style.display = 'flex';
      item.style.opacity = '1';
      item.style.transform = 'scale(1)';
    } else {
      item.style.display = 'none';
      item.style.opacity = '0';
      item.style.transform = 'scale(0.95)';
    }
  });
}

// --- PPDB Modal Logic ---
const ppdbModal = document.getElementById('ppdbModal');

function openPpdbModal() {
  ppdbModal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closePpdbModal() {
  ppdbModal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Close modal on outside click
ppdbModal.addEventListener('click', (e) => {
  if (e.target === ppdbModal) {
    closePpdbModal();
  }
});

// Handle PPDB Form Submission -> WhatsApp
function handlePpdbSubmit(event) {
  event.preventDefault();
  
  const name = document.getElementById('pName').value.trim();
  const phone = document.getElementById('pPhone').value.trim();
  const origin = document.getElementById('pOrigin').value.trim();
  const major = document.getElementById('pMajor').value;
  const address = document.getElementById('pAddress').value.trim();

  // Create WhatsApp message
  const adminPhone = '6281234567890';
  const text = `*PENDAFTARAN PPDB 2026/2027 - SMK BANGUN NUSA BANGSA*%0A%0A` +
               `*Nama Calon Siswa:* ${encodeURIComponent(name)}%0A` +
               `*No. WhatsApp:* ${encodeURIComponent(phone)}%0A` +
               `*Asal Sekolah:* ${encodeURIComponent(origin)}%0A` +
               `*Pilihan Jurusan:* ${encodeURIComponent(major)}%0A` +
               `*Alamat Domisili:* ${encodeURIComponent(address)}%0A%0A` +
               `_Mohon informasi persyaratan lengkap, rincian biaya, dan jadwal tes seleksi PPDB._`;

  const waUrl = `https://wa.me/${adminPhone}?text=${text}`;
  
  // Open WhatsApp
  window.open(waUrl, '_blank');
  closePpdbModal();
  alert('Terima kasih! Data pendaftaran awal Anda telah disiapkan. Anda akan dialihkan ke WhatsApp Panitia PPDB untuk konfirmasi.');
}

// --- Major Details Modal Logic ---
const majorData = {
  tkj: {
    title: 'Teknik Komputer dan Jaringan (TKJ)',
    badge: 'IT & Cybersecurity Specialist',
    badgeClass: 'badge-tkj',
    img: 'assets/images/logo_tkj.jpg',
    desc: 'Program keahlian yang membekali siswa dengan keahlian komprehensif dalam perancangan, instalasi, konfigurasi jaringan komputer lokal maupun global (WAN/Fiber Optic), cloud computing, administrasi server, serta proteksi keamanan siber (cybersecurity).',
    syllabus: [
      'Infrastruktur Jaringan & Routing Enterprise (Mikrotik & Cisco)',
      'Administrasi Sistem Server (Linux Ubuntu/Debian & Windows Server)',
      'Cloud Server Architecture & Virtualisasi (Proxmox / Docker)',
      'Fundamental Keamanan Jaringan & Cyber Defense',
      'Teknologi Fiber Optik, Splice & OTDR Testing'
    ],
    certifications: ['MikroTik Certified Network Associate (MTCNA)', 'Cisco CCNA Prep', 'Sertifikat Kompetensi LSP-P1 BNSP'],
    careers: ['Network Engineer', 'System Administrator', 'Cybersecurity Analyst', 'Cloud Support Specialist', 'Wirausaha ISP / IT Consultant']
  },
  tkr: {
    title: 'Teknik Kendaraan Ringan (TKR)',
    badge: 'Modern Automotive Technology',
    badgeClass: 'badge-tkr',
    img: 'assets/images/logo_tkr.jpg',
    desc: 'Program keahlian yang mencetak teknisi otomotif profesional yang menguasai perawatan dan perbaikan kendaraan roda empat generasi terbaru, sistem injeksi elektronik (EFI), kelistrikan bodi, AC mobil, hingga pemindaian computerized ECU diagnostic.',
    syllabus: [
      'Pemeliharaan Mesin Kendaraan Ringan (Engine Overhaul & EFI Tuning)',
      'Pemeliharaan Sistem Kelistrikan & Computerized Diagnostic (OBD-II Scanner)',
      'Pemeliharaan Chasis, Kemudi, Suspensi & Sistem Rem ABS',
      'Sistem Pendingin Kabin Otomotif (Auto Climate Control)',
      'Budaya Keselamatan Kerja & Manajemen Bengkel 5R'
    ],
    certifications: ['Sertifikasi Uji Kompetensi BNSP Otomotif', 'Sertifikasi Bengkel Resmi Astra / Rekanan'],
    careers: ['Automotive Diagnostic Technician', 'Service Advisor Bengkel Resmi', 'Quality Control Inspector Otomotif', 'Wirausaha Bengkel Mandiri']
  },
  akl: {
    title: 'Akuntansi & Keuangan Lembaga (AKL)',
    badge: 'Digital Finance & Modern Accounting',
    badgeClass: 'badge-akl',
    img: 'assets/images/logo_akl.jpg',
    desc: 'Program keahlian yang mendidik tenaga ahli pembukuan dan keuangan modern yang mahir mengoperasikan software akuntansi digital, menyusun laporan keuangan terpadu, menghitung pajak (e-Faktur & PPh/PPN), serta mengelola administrasi perbankan.',
    syllabus: [
      'Praktikum Akuntansi Perusahaan Jasa, Dagang, dan Manufaktur',
      'Aplikasi Komputer Akuntansi Terintegrasi (MYOB, Accurate, Spreadsheet)',
      'Administrasi Perpajakan Digital & E-Billing / E-SPT',
      'Layanan Lembaga Perbankan & Pengelolaan Kas Mini Bank',
      'Etika Profesi Keuangan & Analisis Laporan Keuangan Dasar'
    ],
    certifications: ['Sertifikasi Teknisi Akuntansi Yunior BNSP', 'Sertifikat Kompetensi Accurate / MYOB Official'],
    careers: ['Junior Financial Accountant', 'Tax Administration Officer', 'Customer Service / Teller Bank', 'Staff Keuangan Perusahaan Swasta/BUMN']
  }
};

const majorModal = document.getElementById('majorModal');
const majorModalContent = document.getElementById('majorModalContent');

function openMajorDetail(key) {
  const data = majorData[key];
  if (!data) return;

  majorModalContent.innerHTML = `
    <button class="modal-close" onclick="closeMajorModal()">&times;</button>
    <div class="modal-header" style="align-items: center;">
      <div class="modal-major-logo-box">
        <img src="${data.img}" alt="${data.title}" class="modal-major-logo-img">
      </div>
      <div>
        <span class="testi-tag ${data.badgeClass}">${data.badge}</span>
        <h3 style="margin-top: 0.4rem;">${data.title}</h3>
      </div>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
      <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">${data.desc}</p>
    </div>

    <div style="margin-bottom: 1.5rem;">
      <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--text-white);">
        <i class="fa-solid fa-book-open text-accent"></i> Kurikulum & Materi Unggulan:
      </h4>
      <ul style="display: flex; flex-direction: column; gap: 0.5rem;">
        ${data.syllabus.map(item => `
          <li style="display: flex; align-items: center; gap: 0.6rem; color: var(--text-muted); font-size: 0.88rem;">
            <i class="fa-solid fa-circle-check" style="color: var(--primary-emerald-light);"></i> ${item}
          </li>
        `).join('')}
      </ul>
    </div>

    <div style="margin-bottom: 1.5rem;">
      <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--text-white);">
        <i class="fa-solid fa-certificate" style="color: var(--accent-amber);"></i> Sertifikasi Profesi yang Didapat:
      </h4>
      <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
        ${data.certifications.map(c => `
          <span style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #FCD34D; font-size: 0.8rem; padding: 0.3rem 0.7rem; border-radius: 6px; font-weight: 600;">
            ${c}
          </span>
        `).join('')}
      </div>
    </div>

    <div class="modal-actions" style="margin-top: 2rem;">
      <button type="button" class="btn btn-secondary" onclick="closeMajorModal()">Tutup</button>
      <a href="https://wa.me/6281234567890?text=Halo%20Admin%20SMK%20Bangun%20Nusa%20Bangsa,%20saya%20tertarik%20konsultasi%20jurusan%20${encodeURIComponent(data.title)}" target="_blank" class="btn btn-primary btn-glow">
        <i class="fa-brands fa-whatsapp"></i> Konsultasi Jurusan Ini
      </a>
    </div>
  `;

  majorModal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeMajorModal() {
  majorModal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

majorModal.addEventListener('click', (e) => {
  if (e.target === majorModal) {
    closeMajorModal();
  }
});

// --- Contact Form Submission Handler ---
function handleContactSubmit(event) {
  event.preventDefault();
  
  const name = document.getElementById('cName').value.trim();
  const phone = document.getElementById('cPhone').value.trim();
  const major = document.getElementById('cMajor').value;
  const message = document.getElementById('cMessage').value.trim();
  const feedback = document.getElementById('formFeedback');

  const adminPhone = '6281234567890';
  const text = `*PERTANYAAN / KONSULTASI - SMK BANGUN NUSA BANGSA*%0A%0A` +
               `*Nama:* ${encodeURIComponent(name)}%0A` +
               `*No. WhatsApp:* ${encodeURIComponent(phone)}%0A` +
               `*Pilihan Jurusan:* ${encodeURIComponent(major)}%0A` +
               `*Pertanyaan/Pesan:*%0A${encodeURIComponent(message)}`;

  const waUrl = `https://wa.me/${adminPhone}?text=${text}`;

  feedback.innerHTML = `<span style="color: var(--primary-emerald-light);"><i class="fa-solid fa-circle-check"></i> Mengalihkan ke WhatsApp Customer Care...</span>`;
  
  setTimeout(() => {
    window.open(waUrl, '_blank');
    document.getElementById('contactForm').reset();
    feedback.innerHTML = '';
  }, 700);
}
