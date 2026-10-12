import * as THREE from 'three';

/**
 * Background 3D ringan: partikel bintang + beberapa bangun low-poly yang
 * melayang dan bereaksi terhadap gerakan mouse.
 */
function initBackground() {
    const canvas = document.getElementById('bg-3d');

    if (!canvas) {
        return;
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let renderer;

    try {
        renderer = new THREE.WebGLRenderer({
            canvas,
            alpha: true,
            antialias: window.devicePixelRatio < 2,
            powerPreference: 'high-performance',
        });
    } catch (e) {
        return;
    }

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 100);
    camera.position.z = 16;

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight);

    // ---- Particle field ----
    const particleCount = window.innerWidth < 768 ? 500 : 1100;
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);
    const palette = [
        new THREE.Color('#a78bfa'),
        new THREE.Color('#22d3ee'),
        new THREE.Color('#f472b6'),
        new THREE.Color('#ffffff'),
    ];

    for (let i = 0; i < particleCount; i++) {
        positions[i * 3] = (Math.random() - 0.5) * 60;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 40;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 40;

        const color = palette[Math.floor(Math.random() * palette.length)];
        colors[i * 3] = color.r;
        colors[i * 3 + 1] = color.g;
        colors[i * 3 + 2] = color.b;
    }

    const particlesGeo = new THREE.BufferGeometry();
    particlesGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    particlesGeo.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const particlesMat = new THREE.PointsMaterial({
        size: 0.14,
        vertexColors: true,
        transparent: true,
        opacity: 0.85,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
    });

    const particles = new THREE.Points(particlesGeo, particlesMat);
    scene.add(particles);

    // ---- Floating low-poly shapes ----
    const shapes = [];
    const shapeDefs = [
        { geo: new THREE.IcosahedronGeometry(2.4, 0), color: '#7c3aed', pos: [-11, 5, -4] },
        { geo: new THREE.TorusGeometry(2, 0.6, 12, 32), color: '#06b6d4', pos: [12, -4, -6] },
        { geo: new THREE.OctahedronGeometry(2.2, 0), color: '#ec4899', pos: [10, 7, -8] },
        { geo: new THREE.DodecahedronGeometry(1.9, 0), color: '#8b5cf6', pos: [-12, -6, -5] },
    ];

    shapeDefs.forEach((def) => {
        const mesh = new THREE.Mesh(
            def.geo,
            new THREE.MeshBasicMaterial({
                color: new THREE.Color(def.color),
                wireframe: true,
                transparent: true,
                opacity: 0.35,
            }),
        );
        mesh.position.set(...def.pos);
        mesh.userData.speed = 0.1 + Math.random() * 0.25;
        scene.add(mesh);
        shapes.push(mesh);
    });

    // ---- Interaction ----
    const pointer = { x: 0, y: 0 };
    const target = { x: 0, y: 0 };

    window.addEventListener('pointermove', (event) => {
        target.x = (event.clientX / window.innerWidth - 0.5) * 2;
        target.y = (event.clientY / window.innerHeight - 0.5) * 2;
    });

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    const clock = new THREE.Clock();

    function animate() {
        requestAnimationFrame(animate);

        const elapsed = clock.getElapsedTime();

        pointer.x += (target.x - pointer.x) * 0.05;
        pointer.y += (target.y - pointer.y) * 0.05;

        if (!reduceMotion) {
            particles.rotation.y = elapsed * 0.03;
            particles.rotation.x = elapsed * 0.01;

            shapes.forEach((mesh) => {
                mesh.rotation.x += 0.0025 * (mesh.userData.speed + 0.4) * 4;
                mesh.rotation.y += 0.0035 * (mesh.userData.speed + 0.4) * 4;
                mesh.position.y += Math.sin(elapsed * mesh.userData.speed + mesh.position.x) * 0.004;
            });
        }

        camera.position.x += (pointer.x * 2.2 - camera.position.x) * 0.04;
        camera.position.y += (-pointer.y * 1.6 - camera.position.y) * 0.04;
        camera.lookAt(scene.position);

        renderer.render(scene, camera);
    }

    animate();
}

export default initBackground;
