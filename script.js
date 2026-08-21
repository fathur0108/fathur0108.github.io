/**
 * FATHUR | CYBERSECURITY & NETWORK PORTFOLIO
 * Interactive JavaScript Engine:
 * - Dual-layer Ambient Matrix & Cyber Defense Network Background
 * - Live Threat Interception & Firewall Laser Neutralization
 * - Interactive Mouse Threat Radar & Magnet Conduits
 * - EMP Shield Defense Shockwaves
 * - Web Audio API Synthesis
 * - Interactive CLI Terminal & Modal System
 * - 3D Card Parallax, Magnetic Cursor, & Particle Mechanics
 */

function initCyberPortfolio() {
  // ==========================================================================
  // 1. AUTONOMOUS DIGITAL VECTOR DOTS & CONSTELLATION NEURAL ENGINE
  // ==========================================================================
  const dotsCanvas = document.getElementById('digital-dots-canvas');
  const dotsCtx = dotsCanvas ? dotsCanvas.getContext('2d') : null;

  function resizeVectorCanvas() {
    if (!dotsCanvas) return;
    dotsCanvas.width = window.innerWidth;
    dotsCanvas.height = window.innerHeight;
  }
  resizeVectorCanvas();

  window.addEventListener('resize', () => {
    resizeVectorCanvas();
    initVectorDots();
  });

  const vectorMouse = {
    x: null,
    y: null,
    radius: 170,
    active: false
  };

  let vectorDots = [];
  let vectorPulses = [];
  let vectorShockwaves = [];
  let globalClock = 0;

  const NODE_CLASSES = ['core', 'beacon', 'vertex', 'mesh', 'dust'];
  const COLOR_THEMES = [
    { fill: '#00d2ff', glow: '#00d2ff', light: 'rgba(0, 210, 255, ' },
    { fill: '#38bdf8', glow: '#38bdf8', light: 'rgba(56, 189, 248, ' },
    { fill: '#3b82f6', glow: '#3b82f6', light: 'rgba(59, 130, 246, ' },
    { fill: '#60a5fa', glow: '#60a5fa', light: 'rgba(96, 165, 250, ' },
    { fill: '#e0f2fe', glow: '#e0f2fe', light: 'rgba(224, 242, 254, ' }
  ];

  const TECH_TAGS = ['CORE_01', 'AES_256', 'SYNC_OK', 'ETH_0', 'PING:1ms', 'NET_MESH', 'SEC_NODE', 'BGP_RT'];

  function initVectorDots() {
    if (!dotsCanvas) return;
    const isMobile = window.innerWidth < 768;
    const count = isMobile ? 55 : Math.min(130, Math.floor((window.innerWidth * window.innerHeight) / 10000));
    vectorDots = [];

    for (let i = 0; i < count; i++) {
      const theme = COLOR_THEMES[Math.floor(Math.random() * COLOR_THEMES.length)];
      const nodeClass = NODE_CLASSES[Math.floor(Math.random() * NODE_CLASSES.length)];
      
      let baseRadius = 2.2;
      if (nodeClass === 'core') baseRadius = 4.2;
      else if (nodeClass === 'beacon') baseRadius = 3.6;
      else if (nodeClass === 'vertex') baseRadius = 3.2;
      else if (nodeClass === 'dust') baseRadius = 1.4;

      // Independent smooth flow velocities
      const speed = 0.35 + Math.random() * 0.55;
      const angle = Math.random() * Math.PI * 2;

      vectorDots.push({
        x: Math.random() * dotsCanvas.width,
        y: Math.random() * dotsCanvas.height,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        // Harmonic organic flow wave parameters (moves completely on its own)
        freqX: 0.0008 + Math.random() * 0.0012,
        freqY: 0.0008 + Math.random() * 0.0012,
        ampX: 0.25 + Math.random() * 0.35,
        ampY: 0.25 + Math.random() * 0.35,
        phase: Math.random() * Math.PI * 2,
        baseRadius: baseRadius,
        nodeClass: nodeClass,
        theme: theme,
        pulse: Math.random() * Math.PI * 2,
        pulseSpeed: 0.02 + Math.random() * 0.035,
        sonarRadius: 0,
        sonarMaxRadius: 36 + Math.random() * 20,
        sonarActive: nodeClass === 'beacon',
        orbitAngle: Math.random() * Math.PI * 2,
        orbitSpeed: (Math.random() - 0.5) * 0.05,
        orbitDist: 10 + Math.random() * 8,
        tag: Math.random() > 0.88 ? TECH_TAGS[Math.floor(Math.random() * TECH_TAGS.length)] : null,
        energy: Math.random() * 0.3 + 0.7
      });
    }

    const hudCountEl = document.getElementById('hudNodeCount');
    if (hudCountEl) hudCountEl.textContent = count;
  }
  initVectorDots();

  // Track mouse coordinates solely for illumination (NO pulling/attraction)
  window.addEventListener('mousemove', (e) => {
    vectorMouse.x = e.clientX;
    vectorMouse.y = e.clientY;
    vectorMouse.active = true;
  });

  window.addEventListener('mouseleave', () => {
    vectorMouse.active = false;
  });

  // Spawn high-velocity data pulses between connected nodes
  function spawnVectorPulse(dotA, dotB) {
    if (vectorPulses.length > 45) return;
    vectorPulses.push({
      x: dotA.x,
      y: dotA.y,
      targetX: dotB.x,
      targetY: dotB.y,
      progress: 0,
      speed: 0.018 + Math.random() * 0.025,
      color: Math.random() > 0.5 ? '#00d2ff' : '#e0f2fe',
      size: Math.random() * 1.5 + 2
    });
  }

  // Click shockwave expansion
  function triggerVectorShockwave(x, y) {
    vectorShockwaves.push({
      x: x,
      y: y,
      radius: 10,
      maxRadius: Math.min(500, window.innerWidth * 0.75),
      alpha: 1.0,
      speed: 10
    });

    vectorDots.forEach(dot => {
      const dx = dot.x - x;
      const dy = dot.y - y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 450) {
        dot.energy = 1.0;
        if (dot.nodeClass === 'beacon') dot.sonarRadius = 0;
      }
    });

    playCyberBeep(920, 'sine', 0.1);
  }

  document.addEventListener('click', (e) => {
    if (e.target.closest('a, button, input, textarea, .cyber-modal, .cmd-chip')) return;
    triggerVectorShockwave(e.clientX, e.clientY);
  });

  const hudEmpBtn = document.getElementById('hudEmpBtn');
  if (hudEmpBtn) {
    hudEmpBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      triggerVectorShockwave(window.innerWidth / 2, window.innerHeight / 2);
    });
  }

  // Main 60FPS Autonomous Digital Vector Dots & Constellation Mesh Loop
  function renderDigitalDots() {
    if (!dotsCtx || !dotsCanvas) return;
    dotsCtx.clearRect(0, 0, dotsCanvas.width, dotsCanvas.height);

    globalClock += 1;
    const connectionDist = 140;

    // 1. Draw Autonomous Vector Constellation Links & Neural Triangulation
    for (let i = 0; i < vectorDots.length; i++) {
      const dotA = vectorDots[i];
      for (let j = i + 1; j < vectorDots.length; j++) {
        const dotB = vectorDots[j];
        const dx = dotA.x - dotB.x;
        const dy = dotA.y - dotB.y;
        const dist = Math.sqrt(dx * dx + dy * dy);

        if (dist < connectionDist) {
          let alpha = (1 - dist / connectionDist) * 0.38 * dotA.energy * dotB.energy;

          // Gentle illumination if mouse is nearby (NO dragging)
          if (vectorMouse.active && vectorMouse.x !== null) {
            const midX = (dotA.x + dotB.x) / 2;
            const midY = (dotA.y + dotB.y) / 2;
            const mDist = Math.hypot(midX - vectorMouse.x, midY - vectorMouse.y);
            if (mDist < vectorMouse.radius) {
              alpha = Math.min(0.9, alpha + (1 - mDist / vectorMouse.radius) * 0.45);
            }
          }

          dotsCtx.beginPath();
          dotsCtx.moveTo(dotA.x, dotA.y);
          dotsCtx.lineTo(dotB.x, dotB.y);
          dotsCtx.strokeStyle = dotA.theme.light + alpha + ')';
          dotsCtx.lineWidth = dotA.nodeClass === 'core' || dotB.nodeClass === 'core' ? 1.1 : 0.65;
          dotsCtx.stroke();

          // Triangulation mesh shading for tight 3-node clusters
          for (let k = j + 1; k < vectorDots.length; k++) {
            const dotC = vectorDots[k];
            const dAC = Math.hypot(dotA.x - dotC.x, dotA.y - dotC.y);
            const dBC = Math.hypot(dotB.x - dotC.x, dotB.y - dotC.y);

            if (dAC < connectionDist * 0.72 && dBC < connectionDist * 0.72) {
              dotsCtx.beginPath();
              dotsCtx.moveTo(dotA.x, dotA.y);
              dotsCtx.lineTo(dotB.x, dotB.y);
              dotsCtx.lineTo(dotC.x, dotC.y);
              dotsCtx.closePath();
              dotsCtx.fillStyle = `rgba(0, 210, 255, ${alpha * 0.07})`;
              dotsCtx.fill();
            }
          }

          if (Math.random() < 0.0018 && vectorPulses.length < 40) {
            spawnVectorPulse(dotA, dotB);
          }
        }
      }
    }

    // 2. Render High-Speed Micro Data Packets
    for (let i = vectorPulses.length - 1; i >= 0; i--) {
      const p = vectorPulses[i];
      p.progress += p.speed;

      const currentX = p.x + (p.targetX - p.x) * p.progress;
      const currentY = p.y + (p.targetY - p.y) * p.progress;

      dotsCtx.beginPath();
      dotsCtx.arc(currentX, currentY, p.size, 0, Math.PI * 2);
      dotsCtx.fillStyle = p.color;
      dotsCtx.shadowColor = p.color;
      dotsCtx.shadowBlur = 10;
      dotsCtx.fill();
      dotsCtx.shadowBlur = 0;

      if (p.progress >= 1) {
        vectorPulses.splice(i, 1);
      }
    }

    // 3. Render Vector Shockwaves
    for (let i = vectorShockwaves.length - 1; i >= 0; i--) {
      const sw = vectorShockwaves[i];
      sw.radius += sw.speed;
      sw.alpha = Math.max(0, 1 - sw.radius / sw.maxRadius);

      dotsCtx.beginPath();
      dotsCtx.arc(sw.x, sw.y, sw.radius, 0, Math.PI * 2);
      dotsCtx.strokeStyle = `rgba(0, 210, 255, ${sw.alpha * 0.75})`;
      dotsCtx.lineWidth = 2;
      dotsCtx.shadowColor = '#00d2ff';
      dotsCtx.shadowBlur = 14;
      dotsCtx.stroke();
      dotsCtx.shadowBlur = 0;

      if (sw.radius >= sw.maxRadius) {
        vectorShockwaves.splice(i, 1);
      }
    }

    // 4. Update & Render Autonomous Digital Vector Dots
    for (let i = 0; i < vectorDots.length; i++) {
      const dot = vectorDots[i];

      // Autonomous motion with organic harmonic wave flow
      const waveX = Math.sin(globalClock * dot.freqX + dot.phase) * dot.ampX;
      const waveY = Math.cos(globalClock * dot.freqY + dot.phase) * dot.ampY;
      
      dot.x += dot.vx + waveX;
      dot.y += dot.vy + waveY;

      // Toroidal seamless screen wrapping
      if (dot.x < -15) dot.x = dotsCanvas.width + 15;
      if (dot.x > dotsCanvas.width + 15) dot.x = -15;
      if (dot.y < -15) dot.y = dotsCanvas.height + 15;
      if (dot.y > dotsCanvas.height + 15) dot.y = -15;

      dot.pulse += dot.pulseSpeed;
      const pulseFactor = Math.sin(dot.pulse) * 0.35 + 0.65;
      
      // Check mouse proximity for soft glow boost only (NO attraction)
      let hoverGlow = 0;
      if (vectorMouse.active && vectorMouse.x !== null) {
        const mDist = Math.hypot(dot.x - vectorMouse.x, dot.y - vectorMouse.y);
        if (mDist < vectorMouse.radius) {
          hoverGlow = (1 - mDist / vectorMouse.radius) * 0.6;
        }
      }

      const totalEnergy = Math.min(1.0, dot.energy + hoverGlow);
      const currentRadius = dot.baseRadius * (1 + totalEnergy * 0.25);

      // Node Class Specific Rendering:
      if (dot.nodeClass === 'core') {
        // --- Core Node: Double Pulsating Orbital Halo & Satellite Dot ---
        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, currentRadius + 5 * pulseFactor, 0, Math.PI * 2);
        dotsCtx.strokeStyle = `rgba(0, 210, 255, ${0.45 * pulseFactor * totalEnergy})`;
        dotsCtx.lineWidth = 1.2;
        dotsCtx.stroke();

        // Main Core Dot
        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, currentRadius, 0, Math.PI * 2);
        dotsCtx.fillStyle = dot.theme.fill;
        dotsCtx.shadowColor = dot.theme.glow;
        dotsCtx.shadowBlur = 12 * totalEnergy;
        dotsCtx.fill();
        dotsCtx.shadowBlur = 0;

        // Satellite micro-dot orbiting core
        dot.orbitAngle += dot.orbitSpeed;
        const satX = dot.x + Math.cos(dot.orbitAngle) * dot.orbitDist;
        const satY = dot.y + Math.sin(dot.orbitAngle) * dot.orbitDist;
        dotsCtx.beginPath();
        dotsCtx.arc(satX, satY, 1.4, 0, Math.PI * 2);
        dotsCtx.fillStyle = '#e0f2fe';
        dotsCtx.fill();

      } else if (dot.nodeClass === 'beacon') {
        // --- Beacon Node: Repeating Radar Sonar Wave Echoes ---
        dot.sonarRadius += 0.5;
        if (dot.sonarRadius > dot.sonarMaxRadius) {
          dot.sonarRadius = 0;
        }
        const sonarAlpha = Math.max(0, 1 - dot.sonarRadius / dot.sonarMaxRadius) * 0.5 * totalEnergy;

        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, dot.sonarRadius, 0, Math.PI * 2);
        dotsCtx.strokeStyle = `rgba(56, 189, 248, ${sonarAlpha})`;
        dotsCtx.lineWidth = 1;
        dotsCtx.stroke();

        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, currentRadius, 0, Math.PI * 2);
        dotsCtx.fillStyle = dot.theme.fill;
        dotsCtx.shadowColor = dot.theme.glow;
        dotsCtx.shadowBlur = 10 * totalEnergy;
        dotsCtx.fill();
        dotsCtx.shadowBlur = 0;

      } else if (dot.nodeClass === 'vertex') {
        // --- Vertex Node: Rotating Diamond Shape ---
        dotsCtx.save();
        dotsCtx.translate(dot.x, dot.y);
        dotsCtx.rotate(dot.pulse * 0.5);
        dotsCtx.beginPath();
        dotsCtx.rect(-currentRadius, -currentRadius, currentRadius * 2, currentRadius * 2);
        dotsCtx.fillStyle = dot.theme.fill;
        dotsCtx.shadowColor = dot.theme.glow;
        dotsCtx.shadowBlur = 10 * totalEnergy;
        dotsCtx.fill();
        dotsCtx.shadowBlur = 0;
        dotsCtx.restore();

      } else {
        // --- Standard Neural Vector Dot ---
        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, currentRadius + 3 * pulseFactor, 0, Math.PI * 2);
        dotsCtx.strokeStyle = dot.theme.light + (0.3 * pulseFactor * totalEnergy) + ')';
        dotsCtx.lineWidth = 0.8;
        dotsCtx.stroke();

        dotsCtx.beginPath();
        dotsCtx.arc(dot.x, dot.y, currentRadius, 0, Math.PI * 2);
        dotsCtx.fillStyle = dot.theme.fill;
        dotsCtx.shadowColor = dot.theme.glow;
        dotsCtx.shadowBlur = 8 * totalEnergy;
        dotsCtx.fill();
        dotsCtx.shadowBlur = 0;
      }

      // Tech Tag Readout
      if (dot.tag && totalEnergy > 0.7) {
        dotsCtx.font = '8px "JetBrains Mono", monospace';
        dotsCtx.fillStyle = `rgba(147, 197, 253, ${0.75 * totalEnergy})`;
        dotsCtx.fillText(dot.tag, dot.x + dot.baseRadius + 6, dot.y - 4);
      }

      dot.energy = Math.max(0.6, dot.energy - 0.003);
    }

    requestAnimationFrame(renderDigitalDots);
  }
  if (dotsCanvas) requestAnimationFrame(renderDigitalDots);

  // ==========================================================================
  // 3. SYNTHESIZED CYBER SOUND EFFECTS (Web Audio API)
  // ==========================================================================
  let audioEnabled = true;
  const AudioContext = window.AudioContext || window.webkitAudioContext;
  let audioCtx = null;

  function playCyberBeep(freq = 800, type = 'sine', duration = 0.05) {
    if (!audioEnabled) return;
    try {
      if (!audioCtx) audioCtx = new AudioContext();
      if (audioCtx.state === 'suspended') audioCtx.resume();

      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.type = type;
      osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
      gain.gain.setValueAtTime(0.03, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);

      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + duration);
    } catch (e) {}
  }

  const soundToggleBtn = document.getElementById('soundToggleBtn');
  const soundIcon = document.getElementById('soundIcon');
  if (soundToggleBtn && soundIcon) {
    soundToggleBtn.addEventListener('click', () => {
      audioEnabled = !audioEnabled;
      if (audioEnabled) {
        soundIcon.className = 'fas fa-volume-high';
        soundToggleBtn.querySelector('span').textContent = 'AUDIO: ON';
        playCyberBeep(1200, 'sine', 0.1);
      } else {
        soundIcon.className = 'fas fa-volume-xmark';
        soundToggleBtn.querySelector('span').textContent = 'AUDIO: OFF';
      }
    });
  }

  document.querySelectorAll('a, button, .cmd-chip, .about-tab, .filter-btn').forEach(el => {
    el.addEventListener('click', () => playCyberBeep(600, 'triangle', 0.04));
  });

  // ==========================================================================
  // 4. MOBILE NAVIGATION TOGGLE
  // ==========================================================================
  const mobileToggle = document.getElementById('mobileToggle');
  const navLinks = document.getElementById('navLinks');
  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', () => {
      navLinks.classList.toggle('show');
      playCyberBeep(900, 'square', 0.05);
    });

    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('show');
      });
    });
  }

  // ==========================================================================
  // 5. ABOUT TABS LOGIC
  // ==========================================================================
  const aboutTabs = document.querySelectorAll('.about-tab');
  const tabContents = document.querySelectorAll('.tab-content');

  aboutTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      aboutTabs.forEach(t => t.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));

      tab.classList.add('active');
      const targetId = tab.getAttribute('data-tab');
      const targetContent = document.getElementById(targetId);
      if (targetContent) targetContent.classList.add('active');
    });
  });

  // ==========================================================================
  // 6. SKILLS FILTERING
  // ==========================================================================
  const filterBtns = document.querySelectorAll('.filter-btn');
  const skillCards = document.querySelectorAll('.skill-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');
      skillCards.forEach(card => {
        if (filter === 'all' || card.getAttribute('data-category') === filter) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // ==========================================================================
  // 7. INTERACTIVE CLI TERMINAL ENGINE
  // ==========================================================================
  const cliScreen = document.getElementById('cliScreen');
  const cliInputField = document.getElementById('cliInputField');

  const cliDatabase = {
    'help': `
      <div class="accent-cyan">DAFTAR PERINTAH SHELL TERSEDIA:</div>
      <table style="width:100%; border-collapse:collapse; margin-top:6px;">
        <tr><td style="color:#00d2ff; width:140px;">help</td><td>Menampilkan pesan bantuan ini</td></tr>
        <tr><td style="color:#00d2ff;">whoami</td><td>Informasi identitas Fathur</td></tr>
        <tr><td style="color:#00d2ff;">cat about</td><td>Melihat ringkasan latar belakang & sekolah</td></tr>
        <tr><td style="color:#00d2ff;">nmap skills</td><td>Audit port keahlian & spesialisasi teknis</td></tr>
        <tr><td style="color:#00d2ff;">ls projects</td><td>Daftar proyek jaringan & security lab</td></tr>
        <tr><td style="color:#00d2ff;">ping contact</td><td>Rincian komunikasi, email & GitHub</td></tr>
        <tr><td style="color:#00d2ff;">status</td><td>Mengecek status server & ketersediaan kolaborasi</td></tr>
        <tr><td style="color:#00d2ff;">sudo</td><td>Mencoba akses root level privilege</td></tr>
        <tr><td style="color:#00d2ff;">date</td><td>Menampilkan waktu sistem saat ini</td></tr>
        <tr><td style="color:#00d2ff;">clear</td><td>Membersihkan layar terminal</td></tr>
      </table>
    `,
    'whoami': `
      <div class="accent-green">FATHUR</div>
      <div>Siswa SMK Bangun Nusa Bangsa &bull; Spesialis Server Networking, Penetration Testing, Linux Sysadmin &amp; Network Hardening.</div>
    `,
    'cat about': `
      <div class="accent-cyan">=== ABOUT FATHUR ===</div>
      <p>Siswa yang antusias di SMK Bangun Nusa Bangsa dengan keahlian praktis merancang arsitektur jaringan Cisco & MikroTik, penetration testing (ethical hacking), konfigurasi server Linux (Debian/Arch), serta otomatisasi keamanan jaringan dengan Bash/Python.</p>
    `,
    'nmap skills': `
      <div class="accent-green">Starting Nmap 7.94 ( https://nmap.org ) at local-time</div>
      <div>Nmap scan report for fathur-core.local (127.0.0.1)</div>
      <div class="dim">PORT 8291/tcp OPEN  MikroTik RouterOS (Routing, Queues, WireGuard)</div>
      <div class="dim">PORT 23/tcp   OPEN  Cisco Networking (VLAN, OSPF, ACL, Packet Tracer)</div>
      <div class="dim">PORT 4444/tcp OPEN  Penetration Testing (Nmap, Metasploit, Burp Suite)</div>
      <div class="dim">PORT 22/tcp   OPEN  Linux Sysadmin (Debian, Arch, Hardening, IPTables)</div>
      <div class="dim">PORT 80/tcp   OPEN  Wireshark DPI &amp; Packet Anomaly Analysis</div>
      <div class="accent-cyan">Host is up (0.00012s latency). All specialized skills active.</div>
    `,
    'ls projects': `
      <div class="accent-cyan">=== REPOSITORY PROYEK ===</div>
      <div>1. <span class="accent-green">Multi-VLAN Campus Network</span> (MikroTik RouterOS + Cisco Catalyst)</div>
      <div>2. <span class="accent-green">Automated Vulnerability Scanner</span> (Python 3 + Bash Script)</div>
      <div>3. <span class="accent-green">Hardened Linux Bastion Server</span> (Debian + Fail2ban + WireGuard)</div>
      <div>4. <span class="accent-green">PenTesting Lab & CTF Writeups</span> (Privilege Escalation & Web Security)</div>
    `,
    'ping contact': `
      <div class="accent-green">64 bytes from fathur.sec: icmp_seq=1 ttl=64 time=0.035 ms</div>
      <div>Email: <a href="mailto:fathur.cybersec@gmail.com" class="accent-cyan">fathur.cybersec@gmail.com</a></div>
      <div>GitHub: <a href="https://github.com" target="_blank" class="accent-cyan">github.com/fathur-sec</a></div>
      <div>Institusi: SMK Bangun Nusa Bangsa</div>
    `,
    'status': `
      <div>SYSTEM STATUS: <span class="accent-green">[ONLINE / OPTIMAL]</span></div>
      <div>OPEN TO: <span class="accent-cyan">Internship / Magang, Network Audits, Cyber Security Research</span></div>
    `,
    'sudo': `
      <div class="accent-red">[ACCESS DENIED] User 'guest' is not in the sudoers file. This incident will be reported to Fathur! :)</div>
    `,
    'date': `
      <div>System Time: <span class="accent-cyan">${new Date().toUTCString()}</span></div>
    `
  };

  window.executeCLICommand = function(cmdStr) {
    if (!cliScreen) return;
    const cleanCmd = cmdStr.trim().toLowerCase();
    
    const cmdPromptLine = document.createElement('div');
    cmdPromptLine.className = 'code-line';
    cmdPromptLine.innerHTML = `<span class="prompt-prefix">guest@fathur-sec:~$</span> ${cmdStr}`;
    cliScreen.appendChild(cmdPromptLine);

    if (cleanCmd === 'clear') {
      cliScreen.innerHTML = '';
      return;
    }

    const responseLine = document.createElement('div');
    responseLine.className = 'cli-output';

    if (cliDatabase[cleanCmd]) {
      responseLine.innerHTML = cliDatabase[cleanCmd];
      playCyberBeep(750, 'sine', 0.06);
    } else if (cleanCmd === '') {
      // blank
    } else {
      responseLine.innerHTML = `<span class="accent-red">bash: command not found: ${cleanCmd}</span>. Ketik <span class="accent-cyan">help</span> untuk melihat daftar perintah.`;
      playCyberBeep(250, 'sawtooth', 0.1);
    }

    cliScreen.appendChild(responseLine);
    cliScreen.scrollTop = cliScreen.scrollHeight;
  };

  window.handleCLISubmit = function(e) {
    e.preventDefault();
    if (!cliInputField) return;
    const val = cliInputField.value;
    if (!val) return;
    executeCLICommand(val);
    cliInputField.value = '';
  };

  // ==========================================================================
  // 8. PROJECT DETAILS MODAL
  // ==========================================================================
  const projectDetailsData = {
    'campus-net': {
      title: 'PROJECT_LOG: Multi-VLAN Campus Network & MikroTik Core',
      content: `
        <h3 class="accent-cyan" style="margin-bottom:10px;">Arsitektur Jaringan Kampus Terisolasi</h3>
        <p class="dim" style="margin-bottom:14px;">Implementasi topologi jaringan untuk SMK Bangun Nusa Bangsa dengan segmentasi keamanan tinggi menggunakan Router MikroTik dan Switch Cisco Catalyst.</p>
        
        <h4 class="accent-green" style="margin-bottom:6px;">Fitur Utama:</h4>
        <ul style="padding-left:20px; margin-bottom:16px;" class="dim">
          <li>Segmentasi VLAN 10 (Admin), VLAN 20 (Guru), VLAN 30 (Siswa), VLAN 40 (Public Guest).</li>
          <li>Implementasi Inter-VLAN Routing dengan Access Control Lists (ACL) untuk mencegah siswa mengakses subnet server admin.</li>
          <li>Queue Tree dengan algoritma PCQ (Per Connection Queue) guna memastikan bandwidth terbagi rata tanpa buffering.</li>
          <li>Hotspot Gateway dengan Captive Portal modern dan otentikasi RADIUS Server.</li>
        </ul>

        <h4 class="accent-green" style="margin-bottom:6px;">Status Implementasi:</h4>
        <p class="dim">Pengujian di lingkungan GNS3 dan fisik RouterBOARD RB750Gr3 berjalan 100% stabil dengan latensi rendah.</p>
      `
    },
    'vuln-scanner': {
      title: 'PROJECT_LOG: Automated Network Vulnerability Scanner',
      content: `
        <h3 class="accent-cyan" style="margin-bottom:10px;">Automated Recon &amp; CVE Auditing Toolkit</h3>
        <p class="dim" style="margin-bottom:14px;">Perangkat lunak berbasis Python 3 yang dirancang untuk memindai port jaringan secara cepat, mengidentifikasi versi service, serta mencocokkan banner dengan database kerentanan (CVE).</p>
        
        <h4 class="accent-green" style="margin-bottom:6px;">Spesifikasi Teknis:</h4>
        <ul style="padding-left:20px; margin-bottom:16px;" class="dim">
          <li>Socket programming multi-threaded untuk scanning subnet /24 dalam hitungan detik.</li>
          <li>Pemanfaatan Nmap Scripting Engine (NSE) untuk otomatisasi pengecekan SMB vulnerability, SSL/TLS weakness, dan default credential.</li>
          <li>Output otomatis berformat HTML interaktif dan log audit Markdown.</li>
        </ul>

        <h4 class="accent-green" style="margin-bottom:6px;">Tujuan Proyek:</h4>
        <p class="dim">Mempermudah administrator jaringan dalam melakukan audit berkala sebelum dievaluasi oleh pihak luar.</p>
      `
    },
    'bastion-server': {
      title: 'PROJECT_LOG: Hardened Linux Bastion Server & IDS',
      content: `
        <h3 class="accent-cyan" style="margin-bottom:10px;">Pengerasan Server Gateway &amp; Sistem Deteksi Intrusi</h3>
        <p class="dim" style="margin-bottom:14px;">Konfigurasi server bastion Debian Linux sebagai satu-satunya titik masuk aman (single point of entry) ke infrastruktur server internal.</p>
        
        <h4 class="accent-green" style="margin-bottom:6px;">Langkah-Langkah Hardening:</h4>
        <ul style="padding-left:20px; margin-bottom:16px;" class="dim">
          <li>Firewall IPTables/NFTables dengan kebijakan default DROP untuk semua inbound traffic kecuali port yang disetujui.</li>
          <li>Penonaktifan password login SSH; wajib menggunakan Ed25519 cryptographic key pair.</li>
          <li>Fail2ban dengan auto-jail 24 jam untuk IP yang melakukan invalid authentication sebanyak 3 kali.</li>
          <li>Instalasi Snort IDS untuk memonitor pola serangan port scan dan SYN flood.</li>
        </ul>
      `
    },
    'ctf-writeups': {
      title: 'PROJECT_LOG: Penetration Testing Lab & CTF Writeups',
      content: `
        <h3 class="accent-cyan" style="margin-bottom:10px;">Dokumentasi Eksploitasi Etis &amp; Patching</h3>
        <p class="dim" style="margin-bottom:14px;">Koleksi laporan audit keamanan pada berbagai mesin lab simulasi TryHackMe, HackTheBox, dan Custom VirtualBox Network.</p>
        
        <h4 class="accent-green" style="margin-bottom:6px;">Fokus Pembelajaran:</h4>
        <ul style="padding-left:20px; margin-bottom:16px;" class="dim">
          <li>Reconnaissance mendalam dengan Nmap dan Gobuster untuk direktori web tersembunyi.</li>
          <li>Eksploitasi celah autentikasi, parameter tampering, dan SQL Injection.</li>
          <li>Linux Privilege Escalation via SUID binaries, cron job misconfigurations, dan kernel exploits.</li>
          <li>Penulisan rekomendasi remediasi sistem pertahanan berbasis standar NIST / OWASP.</li>
        </ul>
      `
    }
  };

  const projectModalBackdrop = document.getElementById('projectModalBackdrop');
  const modalTitle = document.getElementById('modalTitle');
  const modalContent = document.getElementById('modalContent');

  window.openProjectModal = function(projectId) {
    const data = projectDetailsData[projectId];
    if (!data || !projectModalBackdrop) return;

    modalTitle.innerHTML = `<i class="fas fa-terminal"></i> ${data.title}`;
    modalContent.innerHTML = data.content;
    projectModalBackdrop.style.display = 'flex';
    playCyberBeep(900, 'sine', 0.08);
  };

  window.closeProjectModalDirect = function() {
    if (projectModalBackdrop) projectModalBackdrop.style.display = 'none';
    playCyberBeep(400, 'triangle', 0.05);
  };

  window.closeProjectModal = function(e) {
    if (e.target === projectModalBackdrop) {
      closeProjectModalDirect();
    }
  };

  // ==========================================================================
  // 9. CONTACT PACKET FORM HANDLER
  // ==========================================================================
  window.handleMessageSubmit = function(e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submitPacketBtn');
    const feedback = document.getElementById('formFeedback');

    const name = document.getElementById('senderName')?.value || 'Guest';
    const email = document.getElementById('senderEmail')?.value || '';
    const subject = document.getElementById('subjectMatter')?.value || 'Inquiry';
    const msg = document.getElementById('messagePayload')?.value || '';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ENCRYPTING &amp; TRANSMITTING...';
    }
    playCyberBeep(880, 'sawtooth', 0.1);

    setTimeout(() => {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-tower-broadcast"></i> SEND ENCRYPTED PACKET';
      }
      if (feedback) {
        feedback.style.display = 'block';
        feedback.className = 'accent-green';
        feedback.innerHTML = `[+] PACKET DISPATCHED SUCCESSFULLY! Terima kasih <strong>${name}</strong>, pesan Anda telah terkirim ke Fathur.`;
      }
      
      playCyberBeep(1200, 'sine', 0.15);
      const form = document.getElementById('contactForm');
      if (form) form.reset();
    }, 900);
  };

  // Set current year
  const yr = document.getElementById('currentYear');
  if (yr) yr.textContent = new Date().getFullYear();

  // ==========================================================================
  // 10. ADVANCED INTERACTIVE ANIMATIONS
  // ==========================================================================

  // --- 10A. Page Load Entrance ---
  document.body.classList.add('page-loading');
  window.addEventListener('load', () => {
    document.body.classList.remove('page-loading');
    document.body.classList.add('page-loaded');
  });

  // --- 10B. Magnetic Cursor Orb & Trail ---
  (function initCursorOrb() {
    if (window.matchMedia('(hover: none)').matches) return;

    const orb = document.createElement('div');
    orb.className = 'cyber-cursor-orb';
    document.body.appendChild(orb);

    const trailCount = 5;
    const trails = [];
    for (let i = 0; i < trailCount; i++) {
      const t = document.createElement('div');
      t.className = 'cyber-cursor-trail';
      t.style.opacity = (0.4 - i * 0.07).toString();
      t.style.width = (6 - i) + 'px';
      t.style.height = (6 - i) + 'px';
      document.body.appendChild(t);
      trails.push({ el: t, x: 0, y: 0 });
    }

    let mouseX = 0, mouseY = 0;
    let orbX = 0, orbY = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
    });

    const hoverTargets = 'a, button, .cmd-chip, .about-tab, .filter-btn, .project-card, .skill-card, .contact-channel, .hero-tag, .metric-box';
    document.addEventListener('mouseover', (e) => {
      if (e.target.closest(hoverTargets)) orb.classList.add('hovering');
    });
    document.addEventListener('mouseout', (e) => {
      if (e.target.closest(hoverTargets)) orb.classList.remove('hovering');
    });

    function animateOrb() {
      orbX += (mouseX - orbX) * 0.15;
      orbY += (mouseY - orbY) * 0.15;
      orb.style.left = orbX + 'px';
      orb.style.top = orbY + 'px';

      let prevX = orbX, prevY = orbY;
      for (let i = 0; i < trails.length; i++) {
        const t = trails[i];
        t.x += (prevX - t.x) * (0.2 - i * 0.03);
        t.y += (prevY - t.y) * (0.2 - i * 0.03);
        t.el.style.left = t.x + 'px';
        t.el.style.top = t.y + 'px';
        prevX = t.x;
        prevY = t.y;
      }

      requestAnimationFrame(animateOrb);
    }
    animateOrb();
  })();

  // --- 10C. Click Ripple Effect ---
  document.addEventListener('click', (e) => {
    const ripple = document.createElement('div');
    ripple.className = 'click-ripple';
    ripple.style.left = e.clientX + 'px';
    ripple.style.top = e.clientY + 'px';
    if (Math.random() > 0.5) {
      ripple.style.borderColor = '#00d2ff';
      ripple.style.boxShadow = '0 0 8px rgba(0,210,255,0.45)';
    } else {
      ripple.style.borderColor = '#38bdf8';
      ripple.style.boxShadow = '0 0 8px rgba(56,189,248,0.45)';
    }
    document.body.appendChild(ripple);
    ripple.addEventListener('animationend', () => ripple.remove());
  });

  // --- 10D. Scroll Reveal with IntersectionObserver ---
  (function initScrollReveal() {
    const revealMap = [
      { sel: '.section-header', cls: 'reveal-element' },
      { sel: '.skill-card', cls: 'reveal-element' },
      { sel: '.project-card', cls: 'reveal-element' },
      { sel: '.metric-box', cls: 'reveal-scale' },
      { sel: '.highlight-item', cls: 'reveal-left' },
      { sel: '.about-card', cls: 'reveal-element' },
      { sel: '.interactive-cli-card', cls: 'reveal-scale' },
      { sel: '.contact-info-card', cls: 'reveal-left' },
      { sel: '.contact-form-card', cls: 'reveal-right' },
      { sel: '.hero-terminal', cls: 'reveal-right' },
      { sel: '.hero-text-col', cls: 'reveal-left' },
      { sel: '.spec-card', cls: 'reveal-scale' },
    ];

    revealMap.forEach(({ sel, cls }) => {
      document.querySelectorAll(sel).forEach((el, i) => {
        if (!el.classList.contains('reveal-element') &&
            !el.classList.contains('reveal-left') &&
            !el.classList.contains('reveal-right') &&
            !el.classList.contains('reveal-scale')) {
          el.classList.add(cls);
        }
        el.style.transitionDelay = (i * 0.08) + 's';
      });
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal-element, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
      observer.observe(el);
    });
  })();

  // --- 10E. 3D Parallax Tilt on Cards ---
  (function initTiltCards() {
    if (window.matchMedia('(hover: none)').matches) return;

    const cards = document.querySelectorAll('.skill-card, .project-card, .metric-box');
    cards.forEach(card => {
      card.classList.add('tilt-card');

      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = ((y - centerY) / centerY) * -8;
        const rotateY = ((x - centerX) / centerX) * 8;
        card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(8px)`;
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) translateZ(0)';
        card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
        setTimeout(() => card.style.transition = '', 500);
      });
    });
  })();

  // --- 10F. Hero Title Glitch Flicker ---
  (function initHeroGlitchFlicker() {
    const glitchEl = document.querySelector('.glitch-text');
    if (!glitchEl) return;

    function triggerGlitchBurst() {
      glitchEl.style.animation = 'none';
      glitchEl.offsetHeight;
      glitchEl.style.textShadow = `
        ${(Math.random() * 10 - 5)}px ${(Math.random() * 4 - 2)}px 0 rgba(0,210,255,0.7),
        ${(Math.random() * -10 + 5)}px ${(Math.random() * 4 - 2)}px 0 rgba(59,130,246,0.6)
      `;

      setTimeout(() => {
        glitchEl.style.textShadow = '0 0 15px rgba(0,210,255,0.35)';
      }, 150);

      setTimeout(triggerGlitchBurst, 2000 + Math.random() * 5000);
    }
    setTimeout(triggerGlitchBurst, 3000);
  })();

  // --- 10G. Skill Progress Bars Animate on Scroll ---
  (function initSkillBarAnimation() {
    const fills = document.querySelectorAll('.skill-progress-fill');
    const widthMap = new Map();

    fills.forEach(fill => {
      widthMap.set(fill, fill.style.width);
      fill.classList.add('animate-zero');
    });

    const barObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const fill = entry.target;
          fill.classList.remove('animate-zero');
          fill.style.width = widthMap.get(fill);
          barObserver.unobserve(fill);
        }
      });
    }, { threshold: 0.5 });

    fills.forEach(fill => barObserver.observe(fill));
  })();

  // --- 10H. Text Scramble Effect on Hover ---
  (function initTextScramble() {
    const scrambleChars = '!@#$%^&*()_+-=[]{}|;:,.<>?/~ABCDEFabcdef0123456789';
    const targets = document.querySelectorAll('.skill-name, .project-title, .highlight-title, .metric-label');

    targets.forEach(el => {
      el.classList.add('scramble-target');
      const originalText = el.textContent;
      let isScrambling = false;

      el.addEventListener('mouseenter', () => {
        if (isScrambling) return;
        isScrambling = true;
        let iteration = 0;
        const maxIterations = originalText.length;

        const interval = setInterval(() => {
          el.textContent = originalText
            .split('')
            .map((char, idx) => {
              if (idx < iteration) return originalText[idx];
              if (char === ' ') return ' ';
              return scrambleChars[Math.floor(Math.random() * scrambleChars.length)];
            })
            .join('');

          iteration += 1 / 2;
          if (iteration >= maxIterations) {
            clearInterval(interval);
            el.textContent = originalText;
            isScrambling = false;
          }
        }, 35);
      });
    });
  })();

  // --- 10I. Floating Binary & Cyber Particles ---
  (function initBinaryParticles() {
    const binaryChars = ['0', '1', '0x1F', '{ }', '< >', 'SYN', 'ACK', 'SEC', '1010', '#', 'ETH', '01'];

    function spawnBinary() {
      const particle = document.createElement('div');
      particle.className = 'binary-particle';
      particle.textContent = binaryChars[Math.floor(Math.random() * binaryChars.length)];
      particle.style.left = (Math.random() * 100) + 'vw';
      particle.style.bottom = '-20px';
      particle.style.animationDuration = (8 + Math.random() * 12) + 's';
      if (Math.random() > 0.6) particle.style.color = '#38bdf8';
      document.body.appendChild(particle);

      particle.addEventListener('animationend', () => particle.remove());
    }

    setInterval(spawnBinary, 1500);
    for (let i = 0; i < 6; i++) setTimeout(spawnBinary, i * 300);
  })();

  // --- 10J. Navbar Active Section Highlight on Scroll ---
  (function initNavHighlight() {
    const sections = document.querySelectorAll('section[id]');
    const navLinksAll = document.querySelectorAll('.nav-link');

    function highlightNav() {
      const scrollY = window.scrollY + 120;

      sections.forEach(section => {
        const top = section.offsetTop;
        const height = section.offsetHeight;
        const id = section.getAttribute('id');

        if (scrollY >= top && scrollY < top + height) {
          navLinksAll.forEach(link => {
            link.classList.remove('section-active');
            if (link.getAttribute('href') === '#' + id) {
              link.classList.add('section-active');
            }
          });
        }
      });
    }

    window.addEventListener('scroll', highlightNav, { passive: true });
    highlightNav();
  })();

  // --- 10K. Project Card Spark Explosion on Click ---
  (function initCardSparks() {
    document.querySelectorAll('.project-btn-primary').forEach(btn => {
      btn.addEventListener('click', () => {
        const rect = btn.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;

        for (let i = 0; i < 12; i++) {
          const spark = document.createElement('div');
          spark.className = 'card-spark';
          const angle = (Math.PI * 2 * i) / 12;
          const dist = 40 + Math.random() * 60;
          spark.style.left = cx + 'px';
          spark.style.top = cy + 'px';
          spark.style.setProperty('--sx', (Math.cos(angle) * dist) + 'px');
          spark.style.setProperty('--sy', (Math.sin(angle) * dist) + 'px');
          spark.style.background = Math.random() > 0.5 ? '#00d2ff' : '#38bdf8';
          spark.style.boxShadow = `0 0 6px ${spark.style.background}`;
          document.body.appendChild(spark);
          spark.addEventListener('animationend', () => spark.remove());
        }
      });
    });
  })();

  // --- 10L. Section Divider Glow Lines ---
  (function initSectionDividers() {
    const sections = document.querySelectorAll('section');
    sections.forEach((section, i) => {
      if (i < sections.length - 1) {
        const divider = document.createElement('div');
        divider.className = 'section-divider';
        section.appendChild(divider);
      }
    });
  })();

  // --- 10M. Hero Terminal Typing Animation ---
  (function initHeroTyping() {
    const termBody = document.getElementById('heroTypingTerm');
    if (!termBody) return;

    const lines = termBody.querySelectorAll('.code-line');

    lines.forEach(line => {
      line.style.opacity = '0';
      line.style.transform = 'translateX(-10px)';
    });

    function revealLine(index) {
      if (index >= lines.length) return;
      const line = lines[index];
      line.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      line.style.opacity = '1';
      line.style.transform = 'translateX(0)';
      playCyberBeep(300 + index * 80, 'sine', 0.03);
      setTimeout(() => revealLine(index + 1), 180 + Math.random() * 120);
    }

    const heroObs = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        setTimeout(() => revealLine(0), 600);
        heroObs.disconnect();
      }
    }, { threshold: 0.3 });
    heroObs.observe(termBody);
  })();

  // --- 10N. Smooth Counter Animation for Metric Values ---
  (function initMetricCounters() {
    const metrics = document.querySelectorAll('.metric-val');

    const counterObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const text = el.textContent.trim();
        const numMatch = text.match(/^(\d+)(\+?)$/);

        if (numMatch) {
          const target = parseInt(numMatch[1]);
          const suffix = numMatch[2] || '';
          let current = 0;
          const step = Math.max(1, Math.floor(target / 40));

          function count() {
            current += step;
            if (current >= target) {
              current = target;
              el.textContent = current + suffix;
              return;
            }
            el.textContent = current + suffix;
            requestAnimationFrame(count);
          }
          el.textContent = '0' + suffix;
          count();
        }
        counterObs.unobserve(el);
      });
    }, { threshold: 0.5 });

    metrics.forEach(m => counterObs.observe(m));
  })();

  // --- 10O. Keyboard Easter Egg (Konami Code) ---
  (function initEasterEgg() {
    const code = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight'];
    let pos = 0;

    document.addEventListener('keydown', (e) => {
      if (e.key === code[pos]) {
        pos++;
        if (pos >= code.length) {
          pos = 0;
          document.body.style.transition = 'filter 0.3s';
          document.body.style.filter = 'hue-rotate(90deg) saturate(2)';
          playCyberBeep(1500, 'sine', 0.3);
          setTimeout(() => playCyberBeep(1800, 'sine', 0.2), 100);
          setTimeout(() => playCyberBeep(2100, 'sine', 0.15), 200);
          setTimeout(() => {
            document.body.style.filter = '';
          }, 2000);

          for (let i = 0; i < 30; i++) {
            setTimeout(() => {
              const p = document.createElement('div');
              p.className = 'binary-particle';
              p.textContent = Math.random() > 0.5 ? 'ACCESS GRANTED' : 'ROOT';
              p.style.left = (Math.random() * 100) + 'vw';
              p.style.bottom = '-20px';
              p.style.animationDuration = (4 + Math.random() * 6) + 's';
              p.style.fontSize = '14px';
              p.style.color = ['#00d2ff', '#3b82f6', '#38bdf8', '#60a5fa'][Math.floor(Math.random() * 4)];
              document.body.appendChild(p);
              p.addEventListener('animationend', () => p.remove());
            }, i * 80);
          }
        }
      } else {
        pos = 0;
      }
    });
  })();
}

// Ensure execution whether DOM is already ready or loading
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCyberPortfolio);
} else {
  initCyberPortfolio();
}
