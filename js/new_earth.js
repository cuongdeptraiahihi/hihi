// Created by Bjorn Sandvik - thematicmapping.org
(function () {

	var container;

			var camera, scene, renderer, cubeCamera;

			var has_gl = false;

			var delta;
			var time;
			var oldTime;

			var world;
			var clouds;
			var glow;

			var pointLight;

			var mouseX = 0;
			var mouseY = 0;
			var rotX = 0;
			var rotY = 0;

			var distance = -250;
			var flareVisible = true;
			var lens0, lens1, lens2, lens3, lens4, lens5, lens6;

			document.addEventListener('mousemove', onDocumentMouseMove, false);
			document.addEventListener( 'touchmove', onTouchMove, false );

			init();
			animate();

			function init() {

				container = document.createElement( 'div' );
				document.body.appendChild( container );

				scene = new THREE.Scene();
				
				camera = new THREE.PerspectiveCamera( 60, window.innerWidth / window.innerHeight, 1, 100000 );
				camera.position.z = distance;
				camera.lookAt(scene.position);
				scene.add( camera );

				var sphereGeometry = new THREE.SphereGeometry( 100, 64, 48 );
				var sphereMaterial = new THREE.MeshPhongMaterial( {color: 0xffffff, map: THREE.ImageUtils.loadTexture( 'Earth-Color4096.jpg' ), bumpMap: THREE.ImageUtils.loadTexture( 'Earth-Topo4096.jpg' ), bumpScale: 2.75} );
				
				world = new THREE.Mesh(sphereGeometry, sphereMaterial);
				world.rotation.x = -0.15;
				world.rotation.z = -0.15;
				scene.add(world);


				hitMesh = new THREE.Mesh(sphereGeometry, new THREE.MeshBasicMaterial() );
				hitMesh.scale.set(1.1,1.1,1.1);
				hitMesh.visible = false;
				world.add(hitMesh)

				var lightsMaterial = new THREE.MeshBasicMaterial( {map: THREE.ImageUtils.loadTexture( 'cities.png' ), color: 0xffef87, opacity: 0.9, transparent: true, blending: THREE.AdditiveBlending} );
				var lights = new THREE.Mesh(sphereGeometry, lightsMaterial);
				lights.scale.set(1.0001,1.0001,1.0001);
				world.add(lights);

				var cloudMaterial = new THREE.MeshLambertMaterial( {depthWrite: false, map: THREE.ImageUtils.loadTexture( 'clouds.jpg' ), opacity: 0.9, transparent: true, blending: THREE.AdditiveBlending} );
				clouds = new THREE.Mesh(sphereGeometry, cloudMaterial);
				clouds.scale.set(1.01,1.01,1.01);
				world.add(clouds);

				var spriteMap = THREE.ImageUtils.loadTexture( 'glow.png' );
				glow = new THREE.Sprite( { map: spriteMap, useScreenCoordinates: false, color: 0xffffff } );
				glow.scale.set(0.430,0.430,0);
				glow.opacity = 0.35;
				world.add(glow);

				// particles
				var map = THREE.ImageUtils.loadTexture( "flare3.png" );

				attributes = {

					size: {	type: 'f', value: [] }
	
				};

				uniforms = {

					color:     { type: "c", value: new THREE.Color( 0xffffff ) },
					texture:   { type: "t", value: map },
					globalTime:		{ type: "f", value: 0.0 },

				};

				var shaderMaterial = new THREE.ShaderMaterial( {

					uniforms: 		uniforms,
					attributes:     attributes,
					vertexShader:   document.getElementById( 'vertexshader' ).textContent,
					fragmentShader: document.getElementById( 'fragmentshader' ).textContent,

					blending: 		THREE.AdditiveBlending,
					depthTest: 		true,
					transparent:	true,
					
				});


				var geometry = new THREE.Geometry();


				for ( var i = 0; i < 5000; i++ ) {
					var vector = getRandomPointOnSphere(2500 + Math.random()*5000);
					
					geometry.vertices.push( vector );
				}

				particles = new THREE.ParticleSystem( geometry, shaderMaterial );

				var vertices = particles.geometry.vertices;
				var values_size = attributes.size.value;

				for( var v = 0; v < vertices.length; v++ ) {
					values_size[ v ] = 25+Math.random()*125;
				}

				scene.add( particles );

				// Lights
				var ambient = new THREE.AmbientLight( 0x06060b );
				scene.add( ambient );

				pointLight = new THREE.PointLight( 0xfff4c7, 1.2 );
				pointLight.position.set(-300,0,-300);
				scene.add( pointLight );

				// lens flares
				textureFlare0 = THREE.ImageUtils.loadTexture( "lensflare2/lensflare0.png" );
				textureFlare3 = THREE.ImageUtils.loadTexture( "lensflare2/lensflare3.png" );

				lens0 = new THREE.Sprite( { map: textureFlare0, useScreenCoordinates: false, mergeWith3D: false, affectedByDistance: false, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens0.scale.set(1.5,1.5,0);
				lens0.position.set(-2500, 0, -2500);
				lens0.opacity = 0.8;
				scene.add(lens0);				

				lens0b = new THREE.Sprite( { map: textureFlare0, useScreenCoordinates: false, mergeWith3D: false, affectedByDistance: false, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens0b.scale.set(1.4,1.4,0);
				lens0b.position.set(-2400, 0, -2400);
				lens0.opacity = 0.6;
				scene.add(lens0b);				

				lens1 = new THREE.Sprite( { map: textureFlare3, useScreenCoordinates: true, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens1.scale.set(1,1,0);
				lens1.position.set(window.innerWidth/2, window.innerHeight/2, 0);
				scene.add(lens1);				

				lens2 = new THREE.Sprite( { map: textureFlare3, useScreenCoordinates: true, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens2.scale.set(1,1,0);
				lens2.position.set(window.innerWidth/2, window.innerHeight/2, 0);
				scene.add(lens2);				

				lens3 = new THREE.Sprite( { map: textureFlare3, useScreenCoordinates: true, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens3.scale.set(1.5,1.5,0);
				lens3.position.set(window.innerWidth/2, window.innerHeight/2, 0);
				scene.add(lens3);				

				lens4 = new THREE.Sprite( { map: textureFlare3, useScreenCoordinates: true, color: 0xffe7b2, blending: THREE.AdditiveBlending } );
				lens4.scale.set(1,1,0);
				lens4.position.set(window.innerWidth/2, window.innerHeight/2, 0);
				scene.add(lens4);				

				lens1.visible = false;
				lens2.visible = false;
				lens3.visible = false;
				lens4.visible = false;

				try {
					// renderer
					renderer = new THREE.WebGLRenderer({antialias: true, clearColor: 0x030306});
					renderer.setSize( window.innerWidth, window.innerHeight );
					THREEx.WindowResize(renderer, camera);

					container.appendChild( renderer.domElement );
					has_gl = true;
				}
				catch (e) {
					// need webgl
					document.getElementById('info').innerHTML = "<P><BR><B>Note.</B> You need a modern browser that supports WebGL for this to run the way it is intended.<BR>For example. <a href='http://www.google.com/landing/chrome/beta/' target='_blank'>Google Chrome 9+</a> or <a href='http://www.mozilla.com/firefox/beta/' target='_blank'>Firefox 4+</a>.<BR><BR>If you are already using one of those browsers and still see this message, it's possible that you<BR>have old blacklisted GPU drivers. Try updating the drivers for your graphic card.<BR>Or try to set a '--ignore-gpu-blacklist' switch for the browser.</P><CENTER><BR><img src='../general/WebGL_logo.png' border='0'></CENTER>";
					document.getElementById('info').style.display = "block";
					return;
				}

			}


			function getRandomPointOnSphere(r) {
				var angle = Math.random() * Math.PI * 2;
				var u = Math.random() * 2 - 1;
			
				var v = new THREE.Vector3(
					Math.cos(angle) * Math.sqrt(1 - Math.pow(u, 2)) * r,
					Math.sin(angle) * Math.sqrt(1 - Math.pow(u, 2)) * r,
					u * r
				);
				return v;
			}

			function toScreenXY ( position, camera, div ) {

			    var pos = position.clone();
			    projScreenMat = new THREE.Matrix4();
			    projScreenMat.multiply( camera.projectionMatrix, camera.matrixWorldInverse );
			    projScreenMat.multiplyVector3( pos );

			    return { x: ( pos.x + 1 ) * div.width / 2,
			         y: ( - pos.y + 1) * div.height / 2 };

			}

			function runLensFlare () {
				
				var vector = new THREE.Vector3().copy(pointLight.position);
				
				var ray = new THREE.Ray( camera.position, vector.normalize() );

				var intersects = ray.intersectObject( hitMesh );

				if ( intersects.length > 0 ) {

					if (flareVisible) {

						var windowHalfX = window.innerWidth >> 1;
						var time = (windowHalfX - Math.abs(mouseX) )/3;

						var alphaTween = new TWEEN.Tween(lens0)
							.to({opacity: 0}, time)
							.easing(TWEEN.Easing.Linear.EaseNone);
						alphaTween.start();

						var alphaTweenb = new TWEEN.Tween(lens0b)
							.to({opacity: 0}, time)
							.easing(TWEEN.Easing.Linear.EaseNone);
						alphaTweenb.start();

						lens1.visible = false;
						lens2.visible = false;
						lens3.visible = false;
						lens4.visible = false;

					};

					flareVisible = false;

				} else {

					if (!flareVisible) {

						var windowHalfX = window.innerWidth >> 1;
						var time = (windowHalfX - Math.abs(mouseX) )/3;

						var alphaTween = new TWEEN.Tween(lens0)
							.to({opacity: 1}, time)
							.easing(TWEEN.Easing.Linear.EaseNone);
						alphaTween.start();

						var alphaTweenb = new TWEEN.Tween(lens0b)
							.to({opacity: 1}, time)
							.easing(TWEEN.Easing.Linear.EaseNone);
						alphaTweenb.start();

						
						lens1.visible = true;
						lens2.visible = true;
						lens3.visible = true;
						lens4.visible = true;


					};
					

					flareVisible = true;

				}

				var lens0_screen = toScreenXY(lens0.position, camera, renderer.domElement);

				var percentx0 = lens0_screen.x/window.innerWidth;
				var xp = percentx0-0.5;
				var percenty0 = lens0_screen.y/window.innerHeight;
				var yp = percenty0-0.5;

				lens0.rotation = xp*0.7;
				lens0b.rotation = -xp*0.5;


				if (percentx0 > -0.15 && percentx0 < 1.15 && (Math.sin(rotX)+Math.cos(rotX)) < 0) {
					lens1.position.x = (window.innerWidth - lens0_screen.x) + (xp*(window.innerWidth*0.8));
					lens1.position.y = (window.innerHeight - lens0_screen.y) + (yp*(window.innerHeight*0.8));

					lens2.position.x = (window.innerWidth - lens0_screen.x) + (xp*(window.innerWidth*0.6));
					lens2.position.y = (window.innerHeight - lens0_screen.y) + (yp*(window.innerHeight*0.6));

					lens3.position.x = (window.innerWidth - lens0_screen.x) + (xp*(window.innerWidth*0.25));
					lens3.position.y = (window.innerHeight - lens0_screen.y) + (yp*(window.innerHeight*0.25));

					lens4.position.x = (window.innerWidth - lens0_screen.x) + (xp*(window.innerWidth*0.05));
					lens4.position.y = (window.innerHeight - lens0_screen.y) + (yp*(window.innerHeight*0.05));

					if (flareVisible) {
						lens1.visible = true;
						lens2.visible = true;
						lens3.visible = true;
						lens4.visible = true;
					} else {
						lens1.visible = false;
						lens2.visible = false;
						lens3.visible = false;
						lens4.visible = false;						
					};

				} else {
					lens1.visible = false;
					lens2.visible = false;
					lens3.visible = false;
					lens4.visible = false;
				};

			}

			function onDocumentMouseMove(event) {
				
				var windowHalfX = window.innerWidth >> 1;
				var windowHalfY = window.innerHeight >> 1;

				mouseX = ( event.clientX - windowHalfX );
				mouseY = ( event.clientY - windowHalfY );

			}

			function onTouchMove(event) { 
				event.preventDefault();

				var windowHalfX = window.innerWidth >> 1;
				var windowHalfY = window.innerHeight >> 1;

				mouseX = ( event.touches[0].clientX - windowHalfX ) * -1;
				mouseY = ( event.touches[0].clientY - windowHalfY ) * -1;

			}

			function animate() {

				requestAnimationFrame( animate );

				render();

			}

			function render() {

				time = new Date().getTime();
				delta = time - oldTime;
				oldTime = time;

				if (isNaN(delta) || delta > 1000 || delta == 0 ) {
					delta = 1000/60;
				}

				rotX += mouseX/100000;

				camera.position.y += (-150 * Math.sin(mouseY/400) - camera.position.y)/50;

				var minDistance = Math.abs(camera.position.y)*0.3;

				camera.position.x += ( (distance+minDistance) * Math.sin(rotX) - camera.position.x)/10;
				camera.position.z += ( (distance+minDistance) * Math.cos(rotX) - camera.position.z)/10;

				glow.rotation = Math.sin(-rotX*Math.PI/2);

				camera.lookAt(scene.position);

				world.rotation.y += delta/15000;

				clouds.rotation.y -= 0.00004;
				clouds.rotation.z -= 0.00002;

				runLensFlare();

				TWEEN.update();

				if (has_gl) {
					renderer.render( scene, camera );
				}

			}

}());