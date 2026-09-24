/**
 * SMK BANGUN NUSA BANGSA - INTERACTIVE 3D HERO CANVAS
 * Built with Three.js (Responsive 3D Space & Interactive Particle Hologram)
 */

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('threejs-hero-canvas');
  if (!container || typeof THREE === 'undefined') return;

  // 1. Scene, Camera, Renderer
  const scene = new THREE.Scene();
  
  const camera = new THREE.PerspectiveCamera(
    60,
    container.clientWidth / container.clientHeight,
    0.1,
    1000
  );
  camera.position.z = 30;

  const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  container.appendChild(renderer.domElement);

  // 2. Lighting
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
  scene.add(ambientLight);

  const bluePointLight = new THREE.PointLight(0x2563eb, 3, 60);
  bluePointLight.position.set(15, 10, 15);
  scene.add(bluePointLight);

  const cyanPointLight = new THREE.PointLight(0x06b6d4, 3, 60);
  cyanPointLight.position.set(-15, -10, 15);
  scene.add(cyanPointLight);

  // 3. Central 3D Futuristic Holographic Object (Tech Polyhedron + Inner Core)
  const group = new THREE.Group();

  // Outer wireframe icosahedron
  const outerGeo = new THREE.IcosahedronGeometry(7, 1);
  const outerMat = new THREE.MeshStandardMaterial({
    color: 0x3b82f6,
    wireframe: true,
    transparent: true,
    opacity: 0.65,
    roughness: 0.2,
    metalness: 0.8
  });
  const outerMesh = new THREE.Mesh(outerGeo, outerMat);
  group.add(outerMesh);

  // Inner glowing solid octahedron
  const innerGeo = new THREE.OctahedronGeometry(4, 0);
  const innerMat = new THREE.MeshStandardMaterial({
    color: 0x06b6d4,
    roughness: 0.1,
    metalness: 0.9,
    emissive: 0x0284c7,
    emissiveIntensity: 0.3
  });
  const innerMesh = new THREE.Mesh(innerGeo, innerMat);
  group.add(innerMesh);

  // Orbiting Torus Ring
  const torusGeo = new THREE.TorusGeometry(10, 0.15, 16, 100);
  const torusMat = new THREE.MeshBasicMaterial({
    color: 0x60a5fa,
    transparent: true,
    opacity: 0.5
  });
  const torusMesh = new THREE.Mesh(torusGeo, torusMat);
  torusMesh.rotation.x = Math.PI / 3;
  group.add(torusMesh);

  // 4. Floating Small Satellites (Floating Tech Cubes & Diamonds)
  const satellites = [];
  const satGeo = new THREE.BoxGeometry(0.8, 0.8, 0.8);
  const satMat = new THREE.MeshStandardMaterial({
    color: 0x38bdf8,
    metalness: 0.8,
    roughness: 0.2
  });

  for (let i = 0; i < 18; i++) {
    const sat = new THREE.Mesh(satGeo, satMat);
    const radius = 11 + Math.random() * 8;
    const theta = Math.random() * Math.PI * 2;
    const phi = Math.acos(Math.random() * 2 - 1);
    
    sat.position.x = radius * Math.sin(phi) * Math.cos(theta);
    sat.position.y = radius * Math.sin(phi) * Math.sin(theta);
    sat.position.z = radius * Math.cos(phi);
    
    sat.rotation.x = Math.random() * Math.PI;
    sat.rotation.y = Math.random() * Math.PI;
    
    sat.userData = {
      orbitSpeed: (Math.random() * 0.008 + 0.003) * (Math.random() > 0.5 ? 1 : -1),
      axis: new THREE.Vector3(Math.random(), Math.random(), Math.random()).normalize()
    };
    
    group.add(sat);
    satellites.push(sat);
  }

  // Adjust group position slightly to right on desktop
  if (window.innerWidth > 992) {
    group.position.x = 8;
  }
  scene.add(group);

  // 5. Ambient Background Particle Stars
  const particleCount = 400;
  const particleGeo = new THREE.BufferGeometry();
  const particlePositions = new Float32Array(particleCount * 3);

  for (let i = 0; i < particleCount * 3; i += 3) {
    particlePositions[i] = (Math.random() - 0.5) * 120;
    particlePositions[i + 1] = (Math.random() - 0.5) * 120;
    particlePositions[i + 2] = (Math.random() - 0.5) * 80;
  }

  particleGeo.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
  const particleMat = new THREE.PointsMaterial({
    color: 0x93c5fd,
    size: 0.6,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
  });

  const particleSystem = new THREE.Points(particleGeo, particleMat);
  scene.add(particleSystem);

  // 6. Interactive Mouse Tracking
  let mouseX = 0;
  let mouseY = 0;
  let targetX = 0;
  let targetY = 0;

  const windowHalfX = window.innerWidth / 2;
  const windowHalfY = window.innerHeight / 2;

  window.addEventListener('mousemove', (e) => {
    mouseX = (e.clientX - windowHalfX) * 0.0012;
    mouseY = (e.clientY - windowHalfY) * 0.0012;
  });

  // 7. Window Resize Listener
  window.addEventListener('resize', () => {
    const width = container.clientWidth;
    const height = container.clientHeight;
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
    
    if (window.innerWidth <= 992) {
      group.position.x = 0;
      group.position.y = 4;
      group.scale.set(0.75, 0.75, 0.75);
    } else {
      group.position.x = 8;
      group.position.y = 0;
      group.scale.set(1, 1, 1);
    }
  });

  // Trigger initial resize check
  if (window.innerWidth <= 992) {
    group.position.x = 0;
    group.position.y = 4;
    group.scale.set(0.75, 0.75, 0.75);
  }

  // 8. Animation Loop
  const clock = new THREE.Clock();

  function animate() {
    requestAnimationFrame(animate);
    const elapsedTime = clock.getElapsedTime();

    // Smooth mouse follow (easing)
    targetX += (mouseX - targetX) * 0.05;
    targetY += (mouseY - targetY) * 0.05;

    // Rotations
    outerMesh.rotation.y = elapsedTime * 0.2 + targetX * 1.5;
    outerMesh.rotation.x = elapsedTime * 0.15 + targetY * 1.5;

    innerMesh.rotation.y = -elapsedTime * 0.35 + targetX;
    innerMesh.rotation.z = elapsedTime * 0.2;

    torusMesh.rotation.z = elapsedTime * 0.1;

    // Orbit satellites
    satellites.forEach(sat => {
      sat.rotateOnAxis(sat.userData.axis, sat.userData.orbitSpeed);
      sat.position.applyAxisAngle(new THREE.Vector3(0, 1, 0), sat.userData.orbitSpeed);
    });

    // Subtle floating breath effect
    group.position.y += Math.sin(elapsedTime * 1.5) * 0.015;
    particleSystem.rotation.y = elapsedTime * 0.02;

    renderer.render(scene, camera);
  }

  animate();
});
