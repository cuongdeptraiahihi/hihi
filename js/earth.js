// Created by Bjorn Sandvik - thematicmapping.org
(function () {

	var webglEl = document.getElementById('webgl');

	if (!Detector.webgl) {
		Detector.addGetWebGLMessage(webglEl);
		return;
	}

	var width  = window.innerWidth,
		height = window.innerHeight;

	// Earth params
	var radius   = 0.5,
		segments = 32,
		rotation = 6;  

	var scene = new THREE.Scene();

	var camera = new THREE.PerspectiveCamera(45, width / height, 0.01, 1000);
	camera.position.z = 1.5;

	var renderer = new THREE.WebGLRenderer();
	renderer.setSize(width, height);

	scene.add(new THREE.AmbientLight(0x333333));

	var light = new THREE.DirectionalLight(0xffffff, 1);
	light.position.set(5,3,5);
	scene.add(light);

    var sphere = createSphere(radius, segments);
	sphere.rotation.y = rotation; 
	scene.add(sphere);

    var clouds = createClouds(radius, segments);
	clouds.rotation.y = rotation;
	scene.add(clouds);
	
	var night = createNight(radius, segments);
	night.rotation.y = rotation; 
	scene.add(night);

	var stars = createStars(0, 0);
	scene.add(stars);

	var controls = new THREE.TrackballControls(camera);

	webglEl.appendChild(renderer.domElement);

	render();

	function render() {
		controls.update();
		night.rotation.y += 0.0005;
		sphere.rotation.y += 0.0005;
		clouds.rotation.y += 0.0008;		
		requestAnimationFrame(render);
		renderer.render(scene, camera);
	}

	function createSphere(radius, segments) {
		return new THREE.Mesh(
			new THREE.SphereGeometry(radius, segments, segments),
			new THREE.MeshPhongMaterial({
				map:         THREE.ImageUtils.loadTexture('images/diffuse.jpg'),
				bumpMap:     THREE.ImageUtils.loadTexture('images/bump.jpg'),
				bumpScale:   0.005,
				specularMap: THREE.ImageUtils.loadTexture('images/specular.jpg'),
				specular:    new THREE.Color('grey')								
			})
		);
	}

	function createClouds(radius, segments) {
		return new THREE.Mesh(
			new THREE.SphereGeometry(radius + 0.003, segments, segments),			
			new THREE.MeshPhongMaterial({
				map:         THREE.ImageUtils.loadTexture('images/clouds.png'),
				transparent: true
			})
		);		
	}
	
	function createNight(radius, segments) {
		return new THREE.Mesh(
			new THREE.SphereGeometry(radius, segments, segments),			
			new THREE.MeshBasicMaterial({
				map:     THREE.ImageUtils.loadTexture('images/night.jpg'),
				transparent: true
			})
		);		
	}

	function createStars(radius, segments) {
		//var panoramasArray = ["negX.jpg","negY.jpg","negZ.jpg","posX.jpg","posY.jpg","posZ.jpg"];
		//var panoramaNumber = Math.floor(Math.random()*panoramasArray.length);
		
		return new THREE.Mesh(
			new THREE.SphereGeometry(radius, segments, segments), 
			new THREE.MeshBasicMaterial({
				map:  THREE.ImageUtils.loadTexture('images/cities.png'), 
				blending: THREE.AdditiveBlending,
				color: 0xffef87, 
				opacity: 0.9, 
				transparent: true,
				//map: THREE.ImageUtils.loadTexture("images/"+panoramasArray[panoramaNumber]),
				side: THREE.BackSide
			})
		);
	}

}());