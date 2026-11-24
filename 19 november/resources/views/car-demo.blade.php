<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Red Bull Car Experience</title>
    <style>
        body { margin: 0; overflow: hidden; background-color: #111; font-family: 'Arial', sans-serif; }
        #canvas-container { width: 100vw; height: 100vh; display: block; }
        
        #ui-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; /* Let clicks pass through to 3D canvas where needed */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
        }

        .controls {
            pointer-events: auto;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        button {
            background: rgba(255, 0, 50, 0.8);
            color: white;
            border: 2px solid white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255,0,50,0.4);
        }
        button:hover {
            background: rgba(255, 0, 50, 1);
            transform: scale(1.05);
        }
        
        #status-text {
            color: #00ffcc;
            font-size: 18px;
            text-align: center;
            background: rgba(0,0,0,0.5);
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            pointer-events: auto;
            align-self: center;
        }

        #intro-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            pointer-events: auto;
        }
    </style>
</head>
<body>

    <!-- Intro overlay to capture interaction for Audio Context & Speech -->
    <div id="intro-overlay">
        <button id="start-btn" style="font-size: 24px; padding: 20px 40px;">Enter Experience</button>
    </div>

    <!-- 3D Canvas -->
    <div id="canvas-container"></div>

    <!-- UI Overlay -->
    <div id="ui-layer">
        <div id="status-text">Waiting to start...</div>
        <div class="controls">
            <button id="recall-btn">Recall Car</button>
            <button id="mic-btn">🎤 AI Girl (Hold to Speak)</button>
        </div>
    </div>

    <!-- Include Three.js, OrbitControls, GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script>
        // --- GLOBALS ---
        let scene, camera, renderer, controls;
        let carGroup;
        let particles = [];
        let audioCtx;
        let isEngineRunning = false;
        
        // Setup Web Speech API
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = SpeechRecognition ? new SpeechRecognition() : null;
        if(recognition) {
            recognition.continuous = false;
            recognition.lang = 'hi-IN'; // Hindi + English mix
            recognition.interimResults = false;
        }

        function speakAI(text) {
            if(!window.speechSynthesis) return;
            const synth = window.speechSynthesis;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.pitch = 1.2;
            utterance.rate = 1.0;
            
            // Try to find a female voice
            const voices = synth.getVoices();
            const femaleVoice = voices.find(v => v.name.includes('Female') || v.name.includes('Google UK English Female') || v.name.includes('Zira') || v.name.includes('Google हिन्दी'));
            if(femaleVoice) utterance.voice = femaleVoice;
            
            synth.speak(utterance);
        }

        // --- INIT THREE.JS ---
        function init3D() {
            const container = document.getElementById('canvas-container');
            
            scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0x111111, 0.002);
            
            camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
            
            renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.shadowMap.enabled = true;
            container.appendChild(renderer.domElement);
            
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.maxDistance = 500;
            
            // Lights
            const ambient = new THREE.AmbientLight(0xffffff, 0.4);
            scene.add(ambient);
            
            const dirLight = new THREE.DirectionalLight(0xffffff, 1);
            dirLight.position.set(100, 200, 50);
            dirLight.castShadow = true;
            dirLight.shadow.camera.top = 200;
            dirLight.shadow.camera.bottom = -200;
            dirLight.shadow.camera.left = -200;
            dirLight.shadow.camera.right = 200;
            scene.add(dirLight);

            // Ground
            const groundGeo = new THREE.PlaneGeometry(2000, 2000);
            const groundMat = new THREE.MeshStandardMaterial({ color: 0x222222, roughness: 0.8 });
            const ground = new THREE.Mesh(groundGeo, groundMat);
            ground.rotation.x = -Math.PI / 2;
            ground.receiveShadow = true;
            scene.add(ground);
            
            // Grid
            const grid = new THREE.GridHelper(2000, 100, 0x444444, 0x111111);
            scene.add(grid);

            createCarModel();

            // Set Initial Camera for entry
            camera.position.set(0, 50, -300); // Looking from back
            camera.lookAt(0, 0, 0);

            window.addEventListener('resize', onWindowResize, false);
            
            animate();
        }

        function createCarModel() {
            carGroup = new THREE.Group();
            
            // Programmatic "F1" Style Car using Primitives (Red Bull Colors: Blue, Red, Yellow)
            const bodyMat = new THREE.MeshStandardMaterial({ color: 0x000088, metalness: 0.6, roughness: 0.3 }); // Blue body
            const redMat = new THREE.MeshStandardMaterial({ color: 0xcc0000 }); // Red accents
            const yellowMat = new THREE.MeshStandardMaterial({ color: 0xffcc00 }); // Yellow accents
            const blackMat = new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 0.9 }); // Tires

            // Main Body
            const bodyGeo = new THREE.BoxGeometry(10, 6, 40);
            const body = new THREE.Mesh(bodyGeo, bodyMat);
            body.position.y = 5;
            body.castShadow = true;
            carGroup.add(body);

            // Nose
            const noseGeo = new THREE.BoxGeometry(6, 4, 15);
            const nose = new THREE.Mesh(noseGeo, yellowMat);
            nose.position.set(0, 4, 27.5);
            nose.castShadow = true;
            carGroup.add(nose);

            // Front Wing
            const fWingGeo = new THREE.BoxGeometry(18, 1, 4);
            const fWing = new THREE.Mesh(fWingGeo, redMat);
            fWing.position.set(0, 2.5, 33);
            fWing.castShadow = true;
            carGroup.add(fWing);

            // Rear Wing
            const rWingGeo = new THREE.BoxGeometry(16, 1, 6);
            const rWing = new THREE.Mesh(rWingGeo, redMat);
            rWing.position.set(0, 12, -18);
            rWing.castShadow = true;
            carGroup.add(rWing);
            
            const rWingSupportGeo = new THREE.BoxGeometry(8, 8, 2);
            const rWingSupport = new THREE.Mesh(rWingSupportGeo, bodyMat);
            rWingSupport.position.set(0, 8, -18);
            carGroup.add(rWingSupport);

            // Wheels
            const wheelGeo = new THREE.CylinderGeometry(5, 5, 4, 32);
            wheelGeo.rotateZ(Math.PI / 2);
            
            const positions = [
                [-8, 5, 20], [8, 5, 20],   // Front
                [-9, 6, -15], [9, 6, -15]  // Rear (slightly bigger)
            ];

            positions.forEach((pos, i) => {
                const w = new THREE.Mesh(wheelGeo, blackMat);
                w.position.set(...pos);
                if(i > 1) w.scale.set(1.2, 1, 1.2); // Rear bigger
                w.castShadow = true;
                carGroup.add(w);
            });
            
            carGroup.position.set(0, 0, -500); // Start way back
            scene.add(carGroup);
        }

        // --- AUDIO SYNTHESIS ---
        function initAudio() {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }

        function playEngineSound() {
            if(!audioCtx) return;
            isEngineRunning = true;
            // Synthetic engine roar
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(50, audioCtx.currentTime);
            osc.frequency.linearRampToValueAtTime(150, audioCtx.currentTime + 2); // rev up
            
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.3, audioCtx.currentTime + 0.5);
            
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            
            // Store to stop later if needed, but we'll let it fade
            setTimeout(() => {
                if(!isEngineRunning) return;
                gain.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 1);
                setTimeout(() => osc.stop(), 1000);
            }, 5000);
        }

        function playCrackerSound() {
            if(!audioCtx) return;
            // Noise burst
            const bufferSize = audioCtx.sampleRate * 0.5;
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = Math.random() * 2 - 1;
            }
            
            const noise = audioCtx.createBufferSource();
            noise.buffer = buffer;
            const filter = audioCtx.createBiquadFilter();
            filter.type = 'highpass';
            filter.frequency.value = 1000;
            
            const gain = audioCtx.createGain();
            gain.gain.setValueAtTime(1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
            
            noise.connect(filter);
            filter.connect(gain);
            gain.connect(audioCtx.destination);
            noise.start();
        }

        // --- PARTICLES (CRACKERS) ---
        function createCrackers(x, y, z) {
            playCrackerSound();
            const count = 200;
            const geometry = new THREE.BufferGeometry();
            const positions = new Float32Array(count * 3);
            const velocities = [];
            const colors = new Float32Array(count * 3);
            
            const colorObj = new THREE.Color();
            
            for(let i=0; i<count; i++) {
                positions[i*3] = x;
                positions[i*3+1] = y;
                positions[i*3+2] = z;
                
                velocities.push({
                    x: (Math.random() - 0.5) * 4,
                    y: (Math.random()) * 5 + 2,
                    z: (Math.random() - 0.5) * 4
                });
                
                colorObj.setHSL(Math.random(), 1.0, 0.5);
                colors[i*3] = colorObj.r;
                colors[i*3+1] = colorObj.g;
                colors[i*3+2] = colorObj.b;
            }
            
            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
            
            const material = new THREE.PointsMaterial({
                size: 2,
                vertexColors: true,
                transparent: true,
                opacity: 1
            });
            
            const particleSystem = new THREE.Points(geometry, material);
            scene.add(particleSystem);
            
            particles.push({ system: particleSystem, velocities: velocities, life: 1.0 });
        }

        // --- ANIMATION SEQUENCE ---
        function startEntrySequence() {
            carGroup.position.set(0, 0, -800);
            playEngineSound();
            
            const tl = gsap.timeline();
            
            // View 1: From back chasing
            camera.position.set(0, 30, -900);
            camera.lookAt(carGroup.position);
            
            // Car moves forward
            tl.to(carGroup.position, {
                z: -300, duration: 2, ease: "power2.inOut",
                onUpdate: () => camera.lookAt(carGroup.position)
            });
            
            // View 2: Side view flying by
            tl.add(() => {
                camera.position.set(-150, 20, -150);
            });
            
            tl.to(carGroup.position, {
                z: 0, duration: 2, ease: "none",
                onUpdate: () => camera.lookAt(carGroup.position)
            });
            
            // View 3: Top Down View
            tl.add(() => {
                camera.position.set(0, 200, 50);
            });
            
            tl.to(carGroup.position, {
                z: 150, duration: 2, ease: "power2.out",
                onUpdate: () => camera.lookAt(carGroup.position)
            });
            
            // View 4: Front Cinematic stop
            tl.add(() => {
                camera.position.set(50, 15, 250);
            });
            
            tl.to(carGroup.position, {
                z: 200, duration: 2, ease: "power3.out",
                onUpdate: () => camera.lookAt(carGroup.position),
                onComplete: () => {
                    // STOP and Crackers
                    createCrackers(20, 0, 200);
                    createCrackers(-20, 0, 200);
                    setTimeout(() => createCrackers(0, 10, 180), 500);
                    
                    document.getElementById('status-text').innerText = "Car arrived! Waiting 10s...";
                    speakAI("Sir, the Red Bull car has arrived in style.");
                    
                    // Exit after 10s
                    setTimeout(exitSequence, 10000);
                }
            });
        }

        function exitSequence() {
            document.getElementById('status-text').innerText = "Car leaving...";
            playEngineSound();
            speakAI("Zooming out!");
            
            gsap.to(carGroup.position, {
                z: 1500, duration: 1.5, ease: "power2.in"
            });
            
            // Camera follow
            gsap.to(camera.position, {
                z: 1600, duration: 2, ease: "power2.in"
            });
        }

        function recallCar() {
            document.getElementById('status-text').innerText = "Recalling car...";
            speakAI("Recalling the car now, sir.");
            startEntrySequence();
        }

        // --- RENDER LOOP ---
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }

        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            
            // Update particles
            for(let i=particles.length-1; i>=0; i--) {
                const p = particles[i];
                const positions = p.system.geometry.attributes.position.array;
                
                for(let j=0; j<positions.length/3; j++) {
                    positions[j*3] += p.velocities[j].x;
                    positions[j*3+1] += p.velocities[j].y;
                    positions[j*3+2] += p.velocities[j].z;
                    p.velocities[j].y -= 0.1; // gravity
                }
                
                p.system.geometry.attributes.position.needsUpdate = true;
                p.life -= 0.02;
                p.system.material.opacity = p.life;
                
                if(p.life <= 0) {
                    scene.remove(p.system);
                    particles.splice(i, 1);
                }
            }
            
            renderer.render(scene, camera);
        }

        // --- DOM EVENTS ---
        document.getElementById('start-btn').addEventListener('click', () => {
            document.getElementById('intro-overlay').style.display = 'none';
            initAudio();
            init3D();
            
            // Dummy voices fetch to load them
            window.speechSynthesis.getVoices();
            
            startEntrySequence();
        });

        document.getElementById('recall-btn').addEventListener('click', recallCar);

        // --- AI VOICE CONTROL ---
        const micBtn = document.getElementById('mic-btn');
        const statusText = document.getElementById('status-text');
        
        micBtn.addEventListener('mousedown', () => {
            if(recognition) {
                recognition.start();
                micBtn.innerText = "🔴 Listening...";
            } else {
                statusText.innerText = "Speech API not supported in this browser.";
            }
        });

        micBtn.addEventListener('mouseup', () => {
            if(recognition) {
                recognition.stop();
                micBtn.innerText = "🎤 AI Girl (Hold to Speak)";
            }
        });

        if(recognition) {
            recognition.onresult = (event) => {
                const command = event.results[0][0].transcript.toLowerCase();
                statusText.innerText = "You said: " + command;
                
                if(command.includes('aage') || command.includes('forward')) {
                    speakAI("Moving forward, sir.");
                    gsap.to(carGroup.position, { z: carGroup.position.z + 100, duration: 1 });
                } 
                else if(command.includes('piche') || command.includes('back')) {
                    speakAI("Moving backwards.");
                    gsap.to(carGroup.position, { z: carGroup.position.z - 100, duration: 1 });
                }
                else if(command.includes('left') || command.includes('baaye')) {
                    speakAI("Turning left.");
                    gsap.to(carGroup.position, { x: carGroup.position.x - 50, duration: 1 });
                }
                else if(command.includes('right') || command.includes('daaye')) {
                    speakAI("Turning right.");
                    gsap.to(carGroup.position, { x: carGroup.position.x + 50, duration: 1 });
                }
                else if(command.includes('wapas') || command.includes('recall') || command.includes('bulao')) {
                    recallCar();
                }
                else {
                    speakAI("I didn't understand that command, sir.");
                }
            };
            
            recognition.onerror = (e) => {
                statusText.innerText = "Mic Error: " + e.error;
                micBtn.innerText = "🎤 AI Girl (Hold to Speak)";
            };
        }

    </script>
</body>
</html>
