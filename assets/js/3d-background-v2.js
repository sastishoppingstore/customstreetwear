/**
 * Custom Streetwear v2 - Enhanced 3D Animated Background
 * Viper-template inspired with advanced particle effects
 */

(function() {
    'use strict';

    let scene, camera, renderer;
    let particles, geometries = [], floatingTexts = [];
    let mouseX = 0, mouseY = 0, targetX = 0, targetY = 0;
    let clock = new THREE.Clock();
    let isLowPerf = false;

    function init() {
        const container = document.getElementById('hero3d-canvas');
        if (!container) return;

        // Performance check
        isLowPerf = window.navigator.hardwareConcurrency <= 4 || /Mobi|Android/i.test(navigator.userAgent);

        scene = new THREE.Scene();
        
        camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.z = 30;

        renderer = new THREE.WebGLRenderer({ 
            alpha: true, 
            antialias: !isLowPerf,
            powerPreference: "high-performance"
        });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, isLowPerf ? 1 : 2));
        container.appendChild(renderer.domElement);

        createParticles(isLowPerf ? 400 : 800);
        createGeometricShapes(isLowPerf ? 12 : 25);
        createFloatingGrid();

        // Mouse tracking with smooth interpolation
        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth) * 2 - 1;
            mouseY = -(e.clientY / window.innerHeight) * 2 + 1;
        });

        // Touch support
        document.addEventListener('touchmove', (e) => {
            if (e.touches.length > 0) {
                mouseX = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
                mouseY = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
            }
        }, { passive: true });

        window.addEventListener('resize', onResize);

        animate();
    }

    function createParticles(count) {
        const geometry = new THREE.BufferGeometry();
        const positions = new Float32Array(count * 3);
        const colors = new Float32Array(count * 3);
        const sizes = new Float32Array(count);
        const velocities = new Float32Array(count * 3);

        for (let i = 0; i < count; i++) {
            const i3 = i * 3;
            positions[i3] = (Math.random() - 0.5) * 100;
            positions[i3 + 1] = (Math.random() - 0.5) * 70;
            positions[i3 + 2] = (Math.random() - 0.5) * 50 - 10;
            
            const hue = 0.30 + Math.random() * 0.08; // Green spectrum
            const c = new THREE.Color().setHSL(hue, 0.6 + Math.random() * 0.3, 0.3 + Math.random() * 0.5);
            colors[i3] = c.r;
            colors[i3 + 1] = c.g;
            colors[i3 + 2] = c.b;
            
            sizes[i] = 0.05 + Math.random() * 0.2;
            velocities[i3] = (Math.random() - 0.5) * 0.005;
            velocities[i3 + 1] = (Math.random() - 0.5) * 0.005;
            velocities[i3 + 2] = (Math.random() - 0.5) * 0.003;
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        geometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1));

        const particleMaterial = new THREE.PointsMaterial({
            size: 0.12,
            vertexColors: true,
            transparent: true,
            opacity: 0.7,
            blending: THREE.AdditiveBlending,
            sizeAttenuation: true,
            depthWrite: false
        });

        particles = new THREE.Points(geometry, particleMaterial);
        particles.userData.velocities = velocities;
        scene.add(particles);
    }

    function createGeometricShapes(count) {
        const shapeGeos = [
            new THREE.IcosahedronGeometry(0.6, 0),
            new THREE.OctahedronGeometry(0.5, 0),
            new THREE.TorusGeometry(0.4, 0.15, 6, 12),
            new THREE.TetrahedronGeometry(0.5, 0),
            new THREE.DodecahedronGeometry(0.5, 0)
        ];

        for (let i = 0; i < count; i++) {
            const geo = shapeGeos[Math.floor(Math.random() * shapeGeos.length)];
            const hue = 0.30 + Math.random() * 0.1;
            const mat = new THREE.MeshBasicMaterial({
                color: new THREE.Color().setHSL(hue, 0.6, 0.5 + Math.random() * 0.3),
                wireframe: true,
                transparent: true,
                opacity: 0.1 + Math.random() * 0.2
            });
            const mesh = new THREE.Mesh(geo, mat);
            
            mesh.position.set(
                (Math.random() - 0.5) * 70,
                (Math.random() - 0.5) * 50,
                (Math.random() - 0.5) * 40 - 5
            );
            
            const scale = 0.5 + Math.random() * 2;
            mesh.scale.set(scale, scale, scale);
            
            mesh.userData = {
                rotSpeed: { x: (Math.random() - 0.5) * 0.015, y: (Math.random() - 0.5) * 0.015, z: (Math.random() - 0.5) * 0.01 },
                floatSpeed: 0.1 + Math.random() * 0.4,
                floatAmp: 0.3 + Math.random() * 0.8,
                initialY: mesh.position.y,
                initialX: mesh.position.x,
                driftX: (Math.random() - 0.5) * 0.01
            };
            
            scene.add(mesh);
            geometries.push(mesh);
        }
    }

    function createFloatingGrid() {
        const gridSize = 20;
        const divisions = 20;
        const geo = new THREE.BufferGeometry();
        const positions = new Float32Array((divisions + 1) * (divisions + 1) * 3);
        
        let idx = 0;
        for (let i = 0; i <= divisions; i++) {
            for (let j = 0; j <= divisions; j++) {
                positions[idx] = (i / divisions - 0.5) * gridSize;
                positions[idx + 1] = -12 + Math.sin(i * 0.5) * 0.3;
                positions[idx + 2] = (j / divisions - 0.5) * gridSize - 15;
                idx += 3;
            }
        }
        
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        
        const mat = new THREE.PointsMaterial({
            color: 0x39ff14,
            size: 0.04,
            transparent: true,
            opacity: 0.15,
            blending: THREE.AdditiveBlending
        });
        
        const gridPoints = new THREE.Points(geo, mat);
        scene.add(gridPoints);
    }

    function animate() {
        requestAnimationFrame(animate);
        
        const delta = clock.getDelta();
        const elapsed = clock.getElapsedTime();

        // Smooth camera tracking
        targetX += (mouseX * 3 - targetX) * 0.02;
        targetY += (mouseY * 2 - targetY) * 0.02;
        camera.position.x += (targetX - camera.position.x) * 0.02;
        camera.position.y += (targetY - camera.position.y) * 0.02;
        camera.lookAt(scene.position);

        // Animate particles
        if (particles) {
            const pos = particles.geometry.attributes.position.array;
            const vel = particles.userData.velocities;
            const count = pos.length / 3;
            
            for (let i = 0; i < count; i++) {
                const i3 = i * 3;
                pos[i3] += vel[i3] + Math.sin(elapsed * 0.1 + i) * 0.002;
                pos[i3 + 1] += vel[i3 + 1] + Math.cos(elapsed * 0.08 + i) * 0.002;
                pos[i3 + 2] += vel[i3 + 2];
                
                // Wrap around
                if (Math.abs(pos[i3]) > 50) pos[i3] *= -0.9;
                if (Math.abs(pos[i3 + 1]) > 35) pos[i3 + 1] *= -0.9;
                if (Math.abs(pos[i3 + 2]) > 30) pos[i3 + 2] *= -0.9;
            }
            
            particles.geometry.attributes.position.needsUpdate = true;
            particles.rotation.y += delta * 0.02;
            particles.rotation.x += delta * 0.005;
        }

        // Animate geometric shapes
        geometries.forEach(mesh => {
            mesh.rotation.x += mesh.userData.rotSpeed.x;
            mesh.rotation.y += mesh.userData.rotSpeed.y;
            mesh.rotation.z += mesh.userData.rotSpeed.z;
            
            const floatY = Math.sin(elapsed * mesh.userData.floatSpeed + mesh.userData.initialY) * mesh.userData.floatAmp;
            mesh.position.y = mesh.userData.initialY + floatY;
            mesh.position.x = mesh.userData.initialX + Math.sin(elapsed * 0.1 + mesh.userData.initialY) * 2;
        });
    }

    function onResize() {
        const container = document.getElementById('hero3d-canvas');
        if (!container) return;
        const w = container.clientWidth;
        const h = container.clientHeight;
        if (w === 0 || h === 0) return;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    }

    if (typeof THREE !== 'undefined') {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    }
})();
