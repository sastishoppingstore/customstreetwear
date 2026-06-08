/**
 * Custom Streetwear - 3D Animated Background
 * Three.js particle field with floating geometric shapes
 */

(function() {
    'use strict';

    let scene, camera, renderer;
    let particles, geometries = [];
    let mouseX = 0, mouseY = 0;
    let clock = new THREE.Clock();

    function init() {
        const container = document.getElementById('hero3d-canvas');
        if (!container) return;

        scene = new THREE.Scene();
        
        camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.z = 30;

        renderer = new THREE.WebGLRenderer({ 
            alpha: true, 
            antialias: true,
            powerPreference: "low-power"
        });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        // Particles
        const particleCount = 800;
        const geometry = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        const colors = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i += 3) {
            positions[i] = (Math.random() - 0.5) * 80;
            positions[i + 1] = (Math.random() - 0.5) * 60;
            positions[i + 2] = (Math.random() - 0.5) * 40 - 10;
            
            const c = new THREE.Color().setHSL(0.33, 0.8, 0.3 + Math.random() * 0.4);
            colors[i] = c.r;
            colors[i + 1] = c.g;
            colors[i + 2] = c.b;
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        const particleMaterial = new THREE.PointsMaterial({
            size: 0.15,
            vertexColors: true,
            transparent: true,
            opacity: 0.6,
            blending: THREE.AdditiveBlending,
            sizeAttenuation: true
        });

        particles = new THREE.Points(geometry, particleMaterial);
        scene.add(particles);

        // Floating geometric shapes
        const shapeGeometries = [
            new THREE.IcosahedronGeometry(0.8, 0),
            new THREE.OctahedronGeometry(0.7, 0),
            new THREE.TorusGeometry(0.6, 0.2, 8, 16),
            new THREE.TetrahedronGeometry(0.7, 0)
        ];

        for (let i = 0; i < 25; i++) {
            const geo = shapeGeometries[Math.floor(Math.random() * shapeGeometries.length)];
            const mat = new THREE.MeshBasicMaterial({
                color: new THREE.Color().setHSL(0.33, 0.7, 0.4 + Math.random() * 0.3),
                wireframe: true,
                transparent: true,
                opacity: 0.15 + Math.random() * 0.2
            });
            const mesh = new THREE.Mesh(geo, mat);
            
            mesh.position.set(
                (Math.random() - 0.5) * 60,
                (Math.random() - 0.5) * 40,
                (Math.random() - 0.5) * 30 - 5
            );
            
            const scale = 0.5 + Math.random() * 1.5;
            mesh.scale.set(scale, scale, scale);
            
            mesh.userData = {
                rotSpeed: { x: (Math.random() - 0.5) * 0.02, y: (Math.random() - 0.5) * 0.02 },
                floatSpeed: 0.2 + Math.random() * 0.3,
                floatAmp: 0.3 + Math.random() * 0.5,
                initialY: mesh.position.y
            };
            
            scene.add(mesh);
            geometries.push(mesh);
        }

        // Mouse tracking
        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth) * 2 - 1;
            mouseY = -(e.clientY / window.innerHeight) * 2 + 1;
        });

        window.addEventListener('resize', onResize);

        animate();
    }

    function animate() {
        requestAnimationFrame(animate);
        
        const delta = clock.getDelta();
        const elapsed = clock.getElapsedTime();

        // Rotate particles slowly
        if (particles) {
            particles.rotation.y += delta * 0.03;
            particles.rotation.x += delta * 0.01;
        }

        // Animate geometric shapes
        geometries.forEach(mesh => {
            mesh.rotation.x += mesh.userData.rotSpeed.x;
            mesh.rotation.y += mesh.userData.rotSpeed.y;
            mesh.position.y = mesh.userData.initialY + Math.sin(elapsed * mesh.userData.floatSpeed) * mesh.userData.floatAmp;
        });

        // Camera follows mouse
        camera.position.x += (mouseX * 3 - camera.position.x) * 0.02;
        camera.position.y += (mouseY * 2 - camera.position.y) * 0.02;
        camera.lookAt(scene.position);
    }

    function onResize() {
        const container = document.getElementById('hero3d-canvas');
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    }

    if (typeof THREE !== 'undefined') {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    }
})();
