/* ============================================================
   SMK BANGUN NUSA BANGSA - MAIN JAVASCRIPT
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // PRELOADER
  // ============================================================
  const preloader = document.getElementById('preloader');
  if (preloader) {
    window.addEventListener('load', function () {
      setTimeout(function () {
        preloader.classList.add('hidden');
      }, 800);
    });
    // Fallback
    setTimeout(function () {
      preloader.classList.add('hidden');
    }, 3000);
  }

  // ============================================================
  // HEADER SCROLL EFFECT
  // ============================================================
  const header = document.getElementById('header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 60) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }

  // ============================================================
  // HAMBURGER MOBILE MENU
  // ============================================================
  const hamburger = document.querySelector('.hamburger');
  const mobileNav = document.querySelector('.mobile-nav');
  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      mobileNav.classList.toggle('open');
    });
    // Close on link click
    mobileNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('open');
      });
    });
    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('open');
      }
    });
  }

  // ============================================================
  // ACTIVE NAV LINK
  // ============================================================
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  const allNavLinks = document.querySelectorAll('.nav-links a, .mobile-nav a');
  allNavLinks.forEach(function (link) {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  // ============================================================
  // SCROLL REVEAL ANIMATION
  // ============================================================
  const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    revealElements.forEach(function (el) {
      revealObserver.observe(el);
    });
  }

  // ============================================================
  // SCROLL TO TOP
  // ============================================================
  const scrollTop = document.getElementById('scroll-top');
  if (scrollTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        scrollTop.classList.add('visible');
      } else {
        scrollTop.classList.remove('visible');
      }
    });
    scrollTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ============================================================
  // COUNTER ANIMATION
  // ============================================================
  function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-target'), 10);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(function () {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      const suffix = el.getAttribute('data-suffix') || '';
      el.textContent = Math.floor(current).toLocaleString() + suffix;
    }, 16);
  }

  const counters = document.querySelectorAll('.counter');
  if (counters.length > 0) {
    const counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
          entry.target.classList.add('counted');
          animateCounter(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (counter) {
      counterObserver.observe(counter);
    });
  }

  // ============================================================
  // GALLERY LIGHTBOX
  // ============================================================
  const galleryItems = document.querySelectorAll('.gallery-item');
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const lightboxClose = document.querySelector('.lightbox-close');

  if (galleryItems.length > 0 && lightbox) {
    galleryItems.forEach(function (item) {
      item.addEventListener('click', function () {
        const img = item.querySelector('img');
        if (img && lightboxImg) {
          lightboxImg.src = img.src;
          lightboxImg.alt = img.alt;
        }
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    function closeLightbox() {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeLightbox();
    });
  }

  // ============================================================
  // CONTACT FORM SUBMISSION (Simulated)
  // ============================================================
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn = contactForm.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      btn.disabled = true;

      setTimeout(function () {
        btn.innerHTML = '<i class="fas fa-check"></i> Pesan Terkirim!';
        btn.style.background = 'linear-gradient(135deg, #27ae60, #1e8449)';
        contactForm.reset();
        setTimeout(function () {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.disabled = false;
        }, 3000);
      }, 1800);
    });
  }

  // ============================================================
  // SMOOTH ANCHOR SCROLLING
  // ============================================================
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 90;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  // ============================================================
  // PARTICLES BACKGROUND (Subtle)
  // ============================================================
  function createParticles(container) {
    if (!container) return;
    const count = 18;
    for (let i = 0; i < count; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.animationDuration = (Math.random() * 15 + 10) + 's';
      p.style.animationDelay = (Math.random() * 10) + 's';
      p.style.width = (Math.random() * 3 + 1) + 'px';
      p.style.height = p.style.width;
      p.style.opacity = (Math.random() * 0.4 + 0.1).toString();
      container.appendChild(p);
    }
  }

  document.querySelectorAll('.particles').forEach(createParticles);

  // ============================================================
  // NEWS TABS (for berita page)
  // ============================================================
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');
  if (tabBtns.length > 0) {
    tabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const target = this.getAttribute('data-tab');
        tabBtns.forEach(function (b) { b.classList.remove('active'); });
        tabContents.forEach(function (c) { c.classList.remove('active'); });
        this.classList.add('active');
        const content = document.getElementById('tab-' + target);
        if (content) content.classList.add('active');
      });
    });
  }

  // ============================================================
  // GALLERY FILTER
  // ============================================================
  const filterBtns = document.querySelectorAll('.filter-btn');
  const filterItems = document.querySelectorAll('.filter-item');
  if (filterBtns.length > 0) {
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const filter = this.getAttribute('data-filter');
        filterBtns.forEach(function (b) { b.classList.remove('active'); });
        this.classList.add('active');
        filterItems.forEach(function (item) {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = '';
            setTimeout(function () { item.style.opacity = '1'; item.style.transform = 'scale(1)'; }, 50);
          } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.9)';
            setTimeout(function () { item.style.display = 'none'; }, 300);
          }
        });
      });
    });
  }

  // ============================================================
  // HERO TYPED TEXT EFFECT
  // ============================================================
  const typedEl = document.getElementById('typed-text');
  if (typedEl) {
    const texts = ['Berkarakter', 'Kompeten', 'Berprestasi', 'Inovatif'];
    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function typeEffect() {
      const current = texts[textIndex];
      if (!isDeleting) {
        typedEl.textContent = current.substring(0, charIndex + 1);
        charIndex++;
        if (charIndex === current.length) {
          isDeleting = true;
          setTimeout(typeEffect, 2000);
          return;
        }
      } else {
        typedEl.textContent = current.substring(0, charIndex - 1);
        charIndex--;
        if (charIndex === 0) {
          isDeleting = false;
          textIndex = (textIndex + 1) % texts.length;
        }
      }
      setTimeout(typeEffect, isDeleting ? 60 : 100);
    }
    typeEffect();
  }

  // ============================================================
  // ACCORDION (for FAQ or profil)
  // ============================================================
  document.querySelectorAll('.accordion-header').forEach(function (header) {
    header.addEventListener('click', function () {
      const item = this.parentElement;
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.accordion-item').forEach(function (i) {
        i.classList.remove('open');
        const body = i.querySelector('.accordion-body');
        if (body) body.style.maxHeight = null;
      });
      if (!isOpen) {
        item.classList.add('open');
        const body = item.querySelector('.accordion-body');
        if (body) body.style.maxHeight = body.scrollHeight + 'px';
      }
    });
  });

}); // end DOMContentLoaded