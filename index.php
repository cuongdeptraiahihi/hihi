<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("model/is_mobile.php");
	$color=array("#2C84BD","#69b42e","blue","red","yellow");
	$shadow=array("#04344C","#246E2C","blue","brown","orange");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Mother Earth</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, target-densitydpi=device-dpi">
        <meta name="google-site-verification" content="JvlmqF4fpxzH1FcpDInX26Bpye0GSBdmkYfmtr1ClcM" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
		<style type="text/css">
			#viewport,body{overflow:hidden}*{margin:0;padding:0}body{background:#000;font-family:Tahoma,Geneva,sans-serif;letter-spacing:.5px;width:100%;height:100%}a{text-decoration:none}.my-form{position:absolute;z-index:9;width:250px;border:2px solid #12C8F0;padding:7px}.my-form .my-in{padding:0 10px;height:60px;background:rgba(18,200,240,.3);text-align:center}.my-form .my-in:hover a{color:#FFF}.my-form .my-in a{color:#12C8F0;font-size:12px;text-transform:uppercase;font-weight:600;line-height:60px;display:block}.my-conner{background-color:transparent;width:20px;height:20px;position:absolute;z-index:99}.conner1{top:-4px;left:-4px;border-left:6px solid #12C8F0;border-top:6px solid #12C8F0}#viewport,#world{left:0;top:0;position:absolute}.conner2{bottom:-4px;right:-4px;border-right:6px solid #12C8F0;border-bottom:6px solid #12C8F0}.jtap{-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0);-o-transform:translateZ(0);transform:translateZ(0)}#world,#world div{-webkit-transform-style:preserve-3d;-moz-transform-style:preserve-3d;-o-transform-style:preserve-3d}.jtap.jtap-cursor.jtap-idle:after{-moz-animation:blink 1.1s steps(5,start) infinite;-webkit-animation:blink 1.1s steps(5,start) infinite;animation:blink 1.1s steps(5,start) infinite}@-moz-keyframes blink{to{visibility:hidden}}@-webkit-keyframes blink{to{visibility:hidden}}@keyframes blink{to{visibility:hidden}} #viewport{bottom:0;perspective:400;right:0}#world{height:100%;width:100%}.cloudBase{position:absolute;left:256px;top:256px;width:20px;height:20px;margin-left:-10px;margin-top:-10px}.cloudLayer{position:absolute;left:0;top:0;width:256px;height:256px;margin-left:256px;-webkit-transition:opacity .5s ease-out;-moz-transition:opacity .5s ease-out;-o-transition:opacity .5s ease-out;opacity:.05}
            #SMS {  border-top-left-radius:10px;  border-top-right-radius:10px;  position:fixed;  z-index:999;  bottom:0;  left:33px;  background:#365899;  width:130px;  height:25px;  padding:5px 10px;  }  #SMS a {  display:block;  font-size:22px;  margin-left:5px;  }  #SMS a i {  line-height:26px;  color:#FFF;  }  #SMS a span {  font-weight:600;  color:#FFF;  font-size:14px;  margin-left:20px;  line-height:26px;}
        </style>
        <script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
        <script src="https://localhost/www/TDUONG/js/jtap.js"></script>
        <script>
			$(document).ready(function() {
				var vid = document.getElementById("back-music");
				vid.volume = 0.1;
			
				$(".my-form .my-in a.text").jtap({
 					cursor: true,
  					startDelay: 500,
  					speed: 40,
  					backspaceSpeed: 200,
 					humanize: true,
  					skipTags: true
				});
			});
		</script>
	</head>

	<body>

		<div id="BODY">
        	<!--<div id="viewport" onclick="setHalf()">-->
        	<div id="viewport">
                <div id="world" ></div>
            </div>
        	<div class="my-form animated bounceInRight" style="right:5%;top:10%;">
            	<div class="my-conner conner1"></div>
                <div class="my-in hvr-shutter-out-horizontal">
                <?php
					if (!isset($_SESSION["ID_HS"]) || !isset($_SESSION["fullname"])) {
						echo"<a href='/dang-nhap' class='text'>Login</a>";
					} else {
						echo"<a href='/mon' class='text'>Homepage</a>";
					}
				?>
                </div>
                <div class="my-conner conner2"></div>
          	</div>
<!--            <div class="my-form animated bounceInLeft" style="left:5%;bottom:10%;">-->
<!--            	<div class="my-conner conner1"></div>-->
<!--                <div class="my-in hvr-shutter-out-horizontal"><a href='https://localhost/www/TDUONG/codepen.html' class='text'>Solar System</a></div>-->
<!--                <div class="my-conner conner2"></div>-->
<!--          	</div>-->
            <div id="SMS">
                <a href='http://m.me/Bgo.edu.vn' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>
            </div>
		<script type="text/javascript" src="https://localhost/www/TDUONG/js/three.min.js"></script>
		<script type="text/javascript" src="https://localhost/www/TDUONG/js/Tween.js"></script>
		<script type="text/javascript" src="https://localhost/www/TDUONG/js/RequestAnimationFrame.js"></script>
		<script type="text/javascript" src="https://localhost/www/TDUONG/js/THREEx.WindowResize.js"></script>
		<script type="text/javascript" src="https://localhost/www/TDUONG/js/info.js"></script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/OrbitControls.js"></script>
        <script type="text/javascript" src='https://localhost/www/TDUONG/js/loaders/OBJLoader.js'></script>
        <script type="text/javascript" src='https://localhost/www/TDUONG/js/loaders/MTLLoader.js'></script>
        <script type="text/javascript" src='https://localhost/www/TDUONG/js/loaders/OBJMTLLoader.js'></script>
        <!--<script type="text/javascript" src="https://localhost/www/TDUONG/js/threex.spaceships.js"></script>-->
        <script>
		</script>

		<script type="x-shader/x-vertex" id="vertexshader">

			uniform float amplitude;
			attribute float size;
			attribute vec3 customColor;
			uniform float globalTime;

			void main() {

				vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );

				gl_PointSize = min(150.0, size * ( 150.0 / length( mvPosition.xyz ) ) );

				gl_Position = projectionMatrix * mvPosition;

			}

		</script>

		<script type="x-shader/x-fragment" id="fragmentshader">

			uniform vec3 color;
			uniform sampler2D texture;

			void main() {
				gl_FragColor = vec4( color, 1.0 );
				gl_FragColor = gl_FragColor * texture2D( texture, gl_PointCoord );
			}

		</script>

		<script>
			function init() {

				container = document.createElement("div"), document.getElementById("BODY").appendChild(container), scene = new THREE.Scene, camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 1e5), camera.position.z = distance, camera.lookAt(scene.position), scene.add(camera), controls = new THREE.OrbitControls(camera), controls.addEventListener("change", render);
                var e = new THREE.SphereGeometry(100, 64, 64),
					n = new THREE.MeshPhongMaterial({
						color: 16777215,
						map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/diffuse.jpg"),
						bumpMap: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/bump.jpg"),
						bumpScale: 2.75,
						specularMap: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/specular.jpg"),
						specular: new THREE.Color("grey"),
					});
				world = new THREE.Mesh(e, n), world.rotation.x = -.15, world.rotation.z = -.15, scene.add(world), hitMesh = new THREE.Mesh(e, new THREE.MeshBasicMaterial), hitMesh.scale.set(1.1, 1.1, 1.1), hitMesh.visible = !1, world.add(hitMesh);
				var t = new THREE.MeshBasicMaterial({
						map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/night.jpg"),
						color: 16776960,
						opacity: .4,
						transparent: !0,
						blending: THREE.AdditiveBlending
					}),
					i = new THREE.Mesh(e, t);
				i.scale.set(1.0001, 1.0001, 1.0001), world.add(i);
				var a = new THREE.MeshPhongMaterial({
					depthWrite: !1,
					map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/clouds.png"),
					opacity: .8,
					transparent: !0,
					color: 16777215,
					blending: THREE.AdditiveBlending
				});
				clouds = new THREE.Mesh(e, a), clouds.scale.set(1.003, 1.003, 1.003), world.add(clouds), glow = new THREE.Sprite({
					map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/glow.png"),
					useScreenCoordinates: !1,
					color: 16777215
				}), glow.scale.set(.43, .43, 0), glow.opacity = .4, world.add(glow);
				var o = new THREE.MeshPhongMaterial({
						color: 16777215,
						map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/moon.jpg"),
						bumpMap: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/moon_bump.jpg"),
						bumpScale: 2.75
					}),
					s = new THREE.SphereGeometry(25, 54, 54);
				world2 = new THREE.Mesh(s, o), world2.rotation.x = -.15, world2.rotation.z = -.15, world2.position.set(-220, 0, 0), scene.add(world2), hitMesh2 = new THREE.Mesh(s, new THREE.MeshBasicMaterial), hitMesh2.scale.set(1, 1, 1), hitMesh2.visible = !1, world2.add(hitMesh2);
				var r = new THREE.MeshPhongMaterial({
						color: 16777215,
						map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/spacesif.jpg")
					}),
					l = new THREE.SphereGeometry(6, 32, 32, 0, 7, 0, 1.8);
				world3 = new THREE.Mesh(l, r), world3.position.set(-100, 60, 0), scene.add(world3), hitMesh3 = new THREE.Mesh(l, new THREE.MeshBasicMaterial), hitMesh3.scale.set(1, 1, 1), hitMesh3.visible = !1, world3.add(hitMesh3);
				var d = new THREE.TorusGeometry(7, 2, 3, 100, 7),
					w = new THREE.MeshPhongMaterial({
						color: 16777215,
						map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/space_ring.jpg"),
						side: THREE.DoubleSide,
						opacity: 1
					});
				rings = new THREE.Mesh(d, w), rings.rotation.x = -Math.PI / 2, world3.add(rings);
				var h = 1.5,
					c = 50,
					p = 1.5,
					E = 16639;
				light1 = new THREE.PointLight(E, h, c, p), light1.add(new THREE.Mesh(new THREE.SphereGeometry(2, 16, 8), new THREE.MeshPhongMaterial({
					map: THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/spacesif.jpg")
				}))), world3.add(light1), createPoint(1500, 30, 3e3);
				var m = new THREE.AmbientLight(394763);
				scene.add(m), pointLight = new THREE.PointLight(16774343, 1.2), pointLight.position.set(-300, 0, -300), scene.add(pointLight), textureFlare0 = THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/lensflare0.png"), textureFlare3 = THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/flare3.jpg"), textureFlare2 = THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/flare2.jpg"), textureFlare4 = THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/flare4.jpg"), lens0 = new THREE.Sprite({
					map: textureFlare0,
					useScreenCoordinates: !1,
					affectedByDistance: !1,
					color: 16777215,
					blending: THREE.AdditiveBlending,
					transparent: !0
				}), lens0.scale.set(1.8, 1.8, 0), lens0.position.set(-2500, 0, -2500), lens0.opacity = .8, scene.add(lens0), lens0b = new THREE.Sprite({
					map: textureFlare0,
					useScreenCoordinates: !1,
					mergeWith3D: !1,
					affectedByDistance: !1,
					color: 16777215,
					blending: THREE.AdditiveBlending
				}), lens0b.scale.set(1.4, 1.4, 0), lens0b.position.set(-2400, 0, -2400), lens0b.opacity = .8, scene.add(lens0b), lensa = new THREE.Sprite({
					map: textureFlare3,
					useScreenCoordinates: !0,
					color: 16777215,
					blending: THREE.AdditiveBlending
				}), lensa.scale.set(.3, .3, 0), lensa.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), lensa.opacity = .8, scene.add(lensa), lensb = new THREE.Sprite({
					map: textureFlare2,
					useScreenCoordinates: !0,
					color: 16777215,
					blending: THREE.AdditiveBlending
				}), lensb.scale.set(.55, .55, 0), lensb.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), lensb.opacity = .8, scene.add(lensb), lens1 = new THREE.Sprite({
					map: textureFlare2,
					useScreenCoordinates: !0,
					color: 16777215,
					blending: THREE.AdditiveBlending
				}), lens1.scale.set(.4, .4, 0), lens1.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), lens1.opacity = .6, scene.add(lens1), lens2 = new THREE.Sprite({
					map: textureFlare2,
					useScreenCoordinates: !0,
					color: 255,
					blending: THREE.AdditiveBlending
				}), lens2.scale.set(1.2, 1.2, 0), lens2.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), lens2.opacity = .6, scene.add(lens2), lens3 = new THREE.Sprite({
					map: textureFlare3,
					useScreenCoordinates: !0,
					color: 255,
					blending: THREE.AdditiveBlending
				}), lens3.scale.set(.7, .7, 0), lens3.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), scene.add(lens3), lens4 = new THREE.Sprite({
					map: textureFlare4,
					useScreenCoordinates: !0,
					color: 16770994,
					blending: THREE.AdditiveBlending
				}), lens4.scale.set(2.4, 2.4, 0), lens4.position.set(window.innerWidth / 2, window.innerHeight / 2, 0), scene.add(lens4), lensb.visible = !1, lensa.visible = !1, lens1.visible = !1, lens2.visible = !1, lens3.visible = !1, lens4.visible = !1;
				try {
					renderer = new THREE.WebGLRenderer({
						antialias: !0,
						clearColor: 197382
					}), renderer.setSize(window.innerWidth, window.innerHeight), THREEx.WindowResize(renderer, camera), container.appendChild(renderer.domElement), has_gl = !0
				} catch (g) {
					return document.getElementById("info").innerHTML = "<P><BR><B>Note.</B> You need a modern browser that supports WebGL for this to run the way it is intended.<BR>For example. <a href='http://www.google.com/landing/chrome/beta/' target='_blank'>Google Chrome 9+</a> or <a href='http://www.mozilla.com/firefox/beta/' target='_blank'>Firefox 4+</a>.<BR><BR>If you are already using one of those browsers and still see this message, it's possible that you<BR>have old blacklisted GPU drivers. Try updating the drivers for your graphic card.<BR>Or try to set a '--ignore-gpu-blacklist' switch for the browser.</P><CENTER><BR><img src='i.png' border='0'></CENTER>", void(document.getElementById("info").style.display = "block")
				}
				
				//createShip('include/Shuttle01/Shuttle01.obj','include/Shuttle01/Shuttle01.mtl','include/Shuttle01/S01_512.jpg',0.05, [90,90,-90],[0,0,0]);
				//createShip('include/SpaceFighter01/SpaceFighter01.obj','include/SpaceFighter01/SpaceFighter01.mtl','include/SpaceFighter01/F01_512.jpg',0.05, [100,100,-20],[Math.PI/8,0,Math.PI/8]);
				
				x_obj=[90,100,120];
				y_obj=[90,100,120];
				z_obj=[90,20,50];
				/*world.visible = false;
				clouds.visible = false;
				i.visible = false;*/
				
				worldList.push(world);
				worldList.push(world2);
				worldList.push(world3);
				
				createOnePoint(0xfca530, [-10,0,-100]);
				
				/*createOnePoint(0xfca530,[1000,0,0]); // Da cam - x
				createOnePoint(0xa40007,[0,1000,0]); // Do - y
				createOnePoint(0x16beeb,[0,0,1000]); // Xanh - z
				
				createLine([0,0,0],[1000,0,0]);
				createLine([0,0,0],[0,1000,0]);
				createLine([0,0,0],[0,0,1000]);
				//createLine([0,0,0],[-80,80,-80]);
				createLine([0,0,0],[80,80,-80]);
				
				C = Math.sqrt(3);
				D = Math.sqrt(2);
				A = 80*1/D+80*1/D;
				B = 80*(-1/D)+80*1/D;
				createLine([0,0,0],[80,A,B]);
				//createLine([0,0,0],[138.5641,0,0]);
				//createLine([0,0,0],[80,A,80*(-1/D)+80*1/D]);*/
				
				mycen = scene;
				
				projector = new THREE.Projector();
				
				document.addEventListener( 'mousedown', onDocumentMouseDown, false );
			}
			
			function createDeco() {
				var url		= 'https://localhost/www/TDUONG/images/lensflare0_alpha.png';
				var texture	= THREE.ImageUtils.loadTexture(url);
				// do the material	
				var geometry	= new THREE.PlaneGeometry(10,10)
				var material	= new THREE.MeshBasicMaterial({
					color		: 0x00ffff,
					map		: texture,
					side		: THREE.DoubleSide,
					blending	: THREE.AdditiveBlending,
					opacity		: 2,
					depthWrite	: false,
					transparent	: true
				})
				var mesh	= new THREE.Mesh(geometry, material)
				mesh.scale.multiplyScalar(0.75)
				return mesh;
			}
			
			function createShoot() {
				// your code goes here
				var canvas	= generateShootCanvas();
				var texture	= new THREE.Texture( canvas );
				texture.needsUpdate = true;
			
				// do the material	
				var material	= new THREE.MeshBasicMaterial({
					color		: 0xffaacc,
					map		: texture,
					side		: THREE.DoubleSide,
					blending	: THREE.AdditiveBlending,
					depthWrite	: false,
					transparent	: true
				})
			
				var container	= new THREE.Object3D
				container.rotation.set(0,Math.PI/2,0)
				container.scale.multiplyScalar(1/2)
				var nPlanes	= 4;
				for(var i = 0; i < nPlanes; i++){
					var geometry	= new THREE.PlaneGeometry(20,20)
					var mesh	= new THREE.Mesh(geometry, material)
					mesh.material	= material
					mesh.rotation.set(i*Math.PI/nPlanes,0,0)
					container.add(mesh)
				}
				
				return container	
					
				
			}
			
			function generateShootCanvas(){
					// init canvas
					var canvas	= document.createElement( 'canvas' );
					var context	= canvas.getContext( '2d' );
					canvas.width	= 16;
					canvas.height	= 64;
					// set gradient
					var gradient	= context.createRadialGradient(
						canvas.width/2, canvas.height /2, 0,
						canvas.width/2, canvas.height /2, canvas.width /2
					);		
					gradient.addColorStop( 0  , 'rgba(255,255,255,1)' );
					gradient.addColorStop( 0.5, 'rgba(192,192,192,1)' );
					gradient.addColorStop( 0.8, 'rgba(128,128,128,0.7)' );
					gradient.addColorStop( 1  , 'rgba(0,0,0,0)' );
			
					// fill the rectangle
					context.fillStyle	= gradient;
					context.fillRect(0,0, canvas.width, canvas.height);
					// return the just built canvas 
					return canvas;	
				};
			
			function createLine(point1, point2) {
			
				var material = new THREE.LineBasicMaterial({
					color: 0xffffff
				});
				
				var geometry = new THREE.Geometry();
				geometry.vertices.push(
					new THREE.Vector3( point1[0], point1[1], point1[2] ),
					new THREE.Vector3( point2[0], point2[1], point2[2] )
				);
				
				var line = new THREE.Line( geometry, material );
				scene.add( line );
			}
			
			function createOnePoint(color, position) {
			
				var h = 1.5,
					c = 50,
					p = 1.5,
					E = 16639;
				//O = new THREE.PointLight(E, h, c, p);
				O = new THREE.Mesh(new THREE.SphereGeometry(2, 46, 46), new THREE.MeshBasicMaterial({
					color: color
				}));
				O.position.set(position[0],position[1],position[2]);
				world.add(O);
				
				Parr.push(O);
			}
			
			function createShip(urlObj, urlMtl , urlTex, scale, position, rotate) {
			
				var texture = new THREE.Texture();

				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {

					texture.image = event.content;
					texture.needsUpdate = true;

				} );
				loader.load( urlTex );

				// model
				var loader = new THREE.OBJMTLLoader();
				loader.addEventListener( 'load', function ( event ) {

					var object = event.content;

					for ( var i = 0, l = object.children.length; i < l; i ++ ) {

						object.children[ i ].material = new THREE.MeshBasicMaterial({
							map: texture,
						});
					}

					object.position.set(position[0],position[1],position[2]);
					object.scale.set(scale,scale,scale);
					object.rotation.set(rotate[0],rotate[1],rotate[2]);
					
					scene.add( object );
					
					worldList.push(object);
				});
				loader.load( urlObj, urlMtl );
			}
			
			function createPoint(e, n, t) {
				var i = THREE.ImageUtils.loadTexture("https://localhost/www/TDUONG/images/flare_trans.png");
				attributes = {
					size: {
						type: "f",
						value: []
					}
				}, uniforms = {
					color: {
						type: "c",
						value: new THREE.Color(16777215)
					},
					texture: {
						type: "t",
						value: i
					},
					globalTime: {
						type: "f",
						value: 0
					}
				};
				for (var a = new THREE.ShaderMaterial({
						uniforms: uniforms,
						attributes: attributes,
						vertexShader: document.getElementById("vertexshader").textContent,
						fragmentShader: document.getElementById("fragmentshader").textContent,
						blending: THREE.AdditiveBlending,
						depthTest: !0,
						transparent: !0
					}), o = new THREE.Geometry, s = 0; e > s; s++) {
					var r = getRandomPointOnSphere(t + 5e3 * Math.random());
					o.vertices.push(r)
				}
				particles = new THREE.ParticleSystem(o, a);
				for (var l = particles.geometry.vertices, d = attributes.size.value, w = 0; w < l.length; w++) d[w] = n + 100 * Math.random();
				scene.add(particles)
			}
			
			function getRandomPointOnSphere(e) {
				var n = Math.random() * Math.PI * 2,
					t = 2 * Math.random() - 1,
					i = new THREE.Vector3(Math.cos(n) * Math.sqrt(1 - Math.pow(t, 2)) * e, Math.sin(n) * Math.sqrt(1 - Math.pow(t, 2)) * e, t * e);
				return i
			}
			
			function toScreenXY(e, n, t) {
				var i = e.clone();
				return projScreenMat = new THREE.Matrix4, projScreenMat.multiply(n.projectionMatrix, n.matrixWorldInverse), projScreenMat.multiplyVector3(i), {
					x: (i.x + 1) * t.width / 2,
					y: (-i.y + 1) * t.height / 2
				}
			}
			
			function runLensFlare() {
				var e = (new THREE.Vector3).copy(pointLight.position),
					n = new THREE.Ray(camera.position, e.normalize()),
					t = n.intersectObject(hitMesh),
					i = n.intersectObject(hitMesh2),
					a = n.intersectObject(hitMesh3);
				if (t.length > 0 || i.length > 0 || a.length > 0) {
					if (flareVisible) {
						var o = window.innerWidth >> 1,
							s = (o - Math.abs(mouseX)) / 3,
							r = new TWEEN.Tween(lens0).to({
								opacity: 0
							}, s).easing(TWEEN.Easing.Linear.EaseNone);
						r.start();
						var l = new TWEEN.Tween(lens0b).to({
							opacity: 0
						}, s).easing(TWEEN.Easing.Linear.EaseNone);
						l.start(), lensb.visible = !1, lensa.visible = !1, lens1.visible = !1, lens2.visible = !1, lens3.visible = !1, lens4.visible = !1
					}
					flareVisible = !1
				} else {
					if (!flareVisible) {
						var o = window.innerWidth >> 1,
							s = (o - Math.abs(mouseX)) / 3,
							r = new TWEEN.Tween(lens0).to({
								opacity: 1
							}, s).easing(TWEEN.Easing.Linear.EaseNone);
						r.start();
						var l = new TWEEN.Tween(lens0b).to({
							opacity: 1
						}, s).easing(TWEEN.Easing.Linear.EaseNone);
						l.start(), lensb.visible = !0, lensa.visible = !0, lens1.visible = !0, lens2.visible = !0, lens3.visible = !0, lens4.visible = !0
					}
					flareVisible = !0
				}
				var d = toScreenXY(lens0.position, camera, renderer.domElement),
					w = d.x / window.innerWidth,
					h = w - .5,
					c = d.y / window.innerHeight,
					p = c - .5,
					E = !1;
				lens0.rotation = .7 * h, lens0b.rotation = .5 * -h, (camera.position.x > -130 && camera.position.z > 240 || camera.position.z > -120 && camera.position.x > 0) && (E = !0), w > -.15 && 1.15 > w && E ? (lensb.position.x = window.innerWidth - d.x + h * (2 * window.innerWidth), lensb.position.y = 140, lensa.position.x = window.innerWidth - d.x + h * (1.8 * window.innerWidth), lensa.position.y = window.innerHeight - d.y + p * (1.8 * window.innerHeight), lens1.position.x = window.innerWidth - d.x + h * (1.5 * window.innerWidth), lens1.position.y = window.innerHeight - d.y + p * (1.5 * window.innerHeight), lens2.position.x = window.innerWidth - d.x + h * (.8 * window.innerWidth), lens2.position.y = window.innerHeight - d.y + p * (.8 * window.innerHeight), lens3.position.x = window.innerWidth - d.x + h * (.6 * window.innerWidth), lens3.position.y = window.innerHeight - d.y + p * (.6 * window.innerHeight), lens4.position.x = window.innerWidth - d.x + h * (.05 * window.innerWidth), lens4.position.y = window.innerHeight - d.y + p * (.05 * window.innerHeight), flareVisible ? (lensb.visible = !0, lensa.visible = !0, lens1.visible = !0, lens2.visible = !0, lens3.visible = !0, lens4.visible = !0) : (lensb.visible = !1, lensa.visible = !1, lens1.visible = !1, lens2.visible = !1, lens3.visible = !1, lens4.visible = !1)) : (lensb.visible = !1, lensa.visible = !1, lens1.visible = !1, lens2.visible = !1, lens3.visible = !1, lens4.visible = !1)
			}
			
			function animate() {
				requestAnimationFrame(animate), controls.update(), render()
			}
			
			function render() {
				
				
				
				
				
				
				// Code quỹ đạo của tàu
				/*d += 0.001 * Math.PI;
				C = Math.sqrt(3);
				D = Math.sqrt(2);
				for(i=3;i<=4;i++) {
					worldList[i].position.x=(x_obj[i-3]*Math.cos(d))/3 + (y_obj[i-3]*D*Math.sin(d))/3 - z_obj[i-3]*D*(Math.sin(d)/3 - (D*Math.cos(d))/3);
					worldList[i].position.y=(x_obj[i-3]*C*((C*D*Math.cos(d))/6 - (D*Math.sin(d))/2))/3 - y_obj[i-3]*D*((C*((D*Math.cos(d))/2 + (C*D*Math.sin(d))/6))/3 - (D*C*((C*D*Math.cos(d))/6 - (D*Math.sin(d))/2))/3) + (z_obj[i-3]*D*C*((D*Math.cos(d))/2 + (C*D*Math.sin(d))/6))/3;
					worldList[i].position.z=(x_obj[i-3]*C*((C*D*Math.cos(d))/6 + (D*Math.sin(d))/2))/3 + y_obj[i-3]*D*((C*((D*Math.cos(d))/2 - (C*D*Math.sin(d))/6))/3 + (D*C*((C*D*Math.cos(d))/6 + (D*Math.sin(d))/2))/3) - (z_obj[i-3]*D*C*((D*Math.cos(d))/2 - (C*D*Math.sin(d))/6))/3;
					worldList[i].rotation.x=d+Math.PI/2;
				}*/
				
				
				
				
				
				time = (new Date).getTime(), delta = time - oldTime, oldTime = time, (isNaN(delta) || delta > 1e3 || 0 == delta) && (delta = 1e3 / 60), rotX += mouseX / 7e4;
				.3 * Math.abs(camera.position.y);
				camera.lookAt(mycen.position), glow.rotation = Math.sin(-rotX * Math.PI / 2), world.rotation.y += 5e-4, phi += .0005 * Math.PI, world2.position.x = 220 * Math.cos(phi), world2.position.z = 220 * Math.sin(phi), world3.rotation.y -= .005, clouds.rotation.y += 3e-4, clouds.rotation.z += 2e-4,runLensFlare(), TWEEN.update(), renderer.render(scene, camera);
				
			}
			
			function updateView() {
				var e = "translateX( " + d * Math.random() * 10 + "px ) translateZ( " + d * Math.random() * 10 + "px ) rotateX( " + worldXAngle + "deg) rotateY( " + worldYAngle + "deg)";
				worldc.style.webkitTransform = e, worldc.style.MozTransform = e, worldc.style.oTransform = e, worldc.style.transform = e
			}
			
			function generate() {
				if (objects = [], layers = [], worldc.hasChildNodes())
					for (; worldc.childNodes.length >= 1;) worldc.removeChild(worldc.firstChild);
				for (var e = 0; 5 > e; e++) objects.push(createCloud())
			}
			
			function createCloud() {
				var e = document.createElement("div");
				e.className = "cloudBase";
				var n = dodai - 512 * Math.random(),
					t = dodai - 512 * Math.random(),
					i = dodai - 512 * Math.random(),
					a = "translateX( " + n + "px ) translateY( " + t + "px ) translateZ( " + i + "px )";
				e.style.webkitTransform = a, e.style.MozTransform = a, e.style.oTransform = a, worldc.appendChild(e);
				for (var o = 0; o < 2 + Math.round(10 * Math.random()); o++) {
					var s = document.createElement("img");
					src_temp = Math.floor(7 * Math.random()) + 1, s.setAttribute("src", "https://localhost/www/TDUONG/images/may" + src_temp + ".png"), s.className = "cloudLayer";
					var n = dodai - 512 * Math.random(),
						t = dodai - 512 * Math.random(),
						i = dodai / 5 - 200 * Math.random(),
						r = 360 * Math.random(),
						l = .25 + Math.random();
					n *= .2, t *= .2, s.data = {
						x: 10 * n,
						y: 10 * t,
						z: 10 * i,
						a: r,
						s: l
					};
					var a = "translateX( " + n + "px ) translateY( " + t + "px ) translateZ( " + i + "px ) rotateZ( " + r + "deg ) scale( " + l + " )";
					s.style.webkitTransform = a, s.style.MozTransform = a, s.style.oTransform = a, e.appendChild(s), layers.push(s)
				}
				return e
			}
			
			function update2() {
				for (var e = 0; e < layers.length; e++) {
					var n = layers[e];
					n.data.a += n.data.speed;
					var t = "translateX( " + n.data.x + "px ) 						translateY( " + n.data.y + "px ) 						translateZ( " + n.data.z + "px ) 						rotateY( " + -worldYAngle + "deg ) 						rotateX( " + -worldXAngle + "deg ) 						scale( " + n.data.s + ")";
					n.style.webkitTransform = t, n.style.MozTransform = t, n.style.oTransform = t, n.style.transform = t
				}
			}
			function onDocumentMouseDown( event ) {

				event.preventDefault();
				
				if(event.which==1) {

					var vector = new THREE.Vector3( ( event.clientX / window.innerWidth ) * 2 - 1, - ( event.clientY / window.innerHeight ) * 2 + 1, 0.5 );
					projector.unprojectVector( vector, camera );
	
					var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );
	
					var intersects = ray.intersectObjects( worldList, true );
					if ( intersects.length > 0 ) {
	
						//intersects[ 0 ].object.material.color.setHex( Math.random() * 0xffffff );
	
						/*var particle = new THREE.Particle( particleMaterial );
						particle.position = intersects[ 0 ].point;
						particle.scale.x = particle.scale.y = 8;
						scene.add( particle );*/
						if(intersects[0].object.id==12 || intersects[0].object.id==10) {
							window.location="https://localhost/www/TDUONG/tai-lieu-cong-cong/";
							dem=0;
						} 
						/*else if(intersects[0].object.id==4) {
							if(dem==5) {
								window.location="https://localhost/www/TDUONG/codepen.html";
							}
							dem++;
						} else {
							dem=0;
						}*/
						
						//alert("kiet");
						mycen = intersects[0].object;
						//console.log(intersects[0].object.id);
						console.log(dem);
	
					}
					
					var intersects2 = ray.intersectObjects( Parr, true );
					if ( intersects2.length > 0 ) {
						alert("Kiet");
					}
				}
			}
			var group, text, plane, windowHalfX2 = window.innerWidth / 2,
				windowHalfY2 = window.innerHeight / 2,
				finalRotationY, container, camera, scene, renderer, cubeCamera, controls, projector, has_gl = !1,
				delta, time, oldTime, phi = 0, d = 0,
				world, world2, world3, clouds, glow, pointLight, mouseX = 0,
				ship01, ship02, ship03,
				mouseY = 0,
				rotX = 0,
				rotY = 0,
				mycen,
				distance = -270,
				flareVisible = !0,
				userRoll = !0,
				dem = 0,
				lens0, lens1, lens2, lens3, lens4, lens5, lens6, lensa, lensb, temp, STATE = 0,
				dodai = 256,
				worldc = document.getElementById("world");
				viewport = document.getElementById("viewport"), worldXAngle = 0, worldYAngle = 0, d = 0;
				
				var objects = [],
					layers = [],
					x_obj = [], y_obj = [], z_obj = [],
					shoot_arr = [],
					Parr = [],
					worldList = [];
				init(), animate();
		</script>
        <audio id="back-music" loop autoplay style="display:none;">
          <source src="https://localhost/www/TDUONG/include/back.mp3" type="audio/mpeg">
          Trình duyệt không hỗ trợ
        </audio>
        </div>
	</body>
</html>


<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>
