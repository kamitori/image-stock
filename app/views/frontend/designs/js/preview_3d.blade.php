<script type="text/javascript">
var Preview3D = function() {
	var container;
	var camera, controls, scene, renderer;

	function build3D(OBJECT){
		  // scene size
		var WIDTH = window.innerWidth;
		var HEIGHT = window.innerHeight;
		// camera
		var VIEW_ANGLE = 45;
		var ASPECT = WIDTH / HEIGHT;
		var NEAR = 1;
		var FAR = 500;
		var GROUP = new THREE.Group();

		scene = new THREE.Scene();
		scene.fog = new THREE.FogExp2( 0xcccccc, 0.0002);
		camera = new THREE.PerspectiveCamera( 30, 1000/550, 0.1,  80000 );
		camera.position.z = Math.max(OBJECT.width,OBJECT.height)*1.2;
		camera.position.x = Math.max(OBJECT.width,OBJECT.height)*-1.5;

		// lights
		light = new THREE.PointLight( 0xffffff);
		light.position.set( Math.max(OBJECT.width,OBJECT.height)*2, -Math.max(OBJECT.width,OBJECT.height)*2 , Math.max(OBJECT.width,OBJECT.height)*2 );
		scene.add( light );

		light2 = new THREE.PointLight( 0xffffff);
		light2.position.set(- Math.max(OBJECT.width,OBJECT.height)*2,Math.max(OBJECT.width,OBJECT.height)*2, -Math.max(OBJECT.width,OBJECT.height)*2 );
		scene.add( light2 );

		// ambientLight = new THREE.AmbientLight( 0x888888 );
		// scene.add( ambientLight );

		var directionalLightTopLeft = new THREE.DirectionalLight( 0xffffff,0.8);
		directionalLightTopLeft.position.set( -1, 1, 0 );
		scene.add( directionalLightTopLeft);

		var directionalLightBotRight = new THREE.DirectionalLight( 0xeeeeee,0.8);
		directionalLightBotRight.position.set( 1, -1, 0);
		scene.add( directionalLightBotRight);


		var directionalLightFront = new THREE.DirectionalLight( 0xffffff,0.6);
		directionalLightFront.position.set( 0,0,1);
		scene.add( directionalLightFront);

		// renderer
		renderer = new THREE.WebGLRenderer( { antialias: true, alpha: false } ) ;
		renderer.setClearColor( scene.fog.color, 0 );
		renderer.setSize( 1000, 550 );

		container = document.getElementById( 'preview_content' );
		container.appendChild( renderer.domElement );

		controls = new THREE.OrbitControls( camera, container );
		controls.damping = 7;
		controls.minDistance = 100;
		controls.maxDistance = 5 * OBJECT.width;

		var finish = 0;
		var medium = findMedium(OBJECT.shapes);
		for(var position in OBJECT.shapes ) {
			var currentShape = OBJECT.shapes[position];
			//Front
			var shape = new THREE.Shape();
			var points = currentShape.center.points;
			var shapePoint = [];
			for(i = 0; i < points.length; i++) {
				var point = points[i];
				if( point.y > medium ) {
					point.y = point.y - (point.y - medium)*2;
				} else if( point.y < medium ) {
					point.y = point.y + (medium - point.y)*2;
				}
				if( !i ) {
					shape.moveTo( point.x, point.y );
				} else {
					shape.lineTo( point.x, point.y );
				}
				shapePoint[i] = {x: point.x, y: point.y};
			}
			var geometry = new THREE.ExtrudeGeometry(shape, { amount: 0,
															  bevelEnabled: false,
															  material: 0,
															  extrudeMaterial: 1});
			/*var texture = THREE.ImageUtils.loadTexture( currentShape.center.image, {}, function(){
				render();
				renderComplete(OBJECT.imageTotal, ++finish);
			} );*/
			var texture = new THREE.Texture( document.getElementById(currentShape.center.image) );
			texture.needsUpdate = true;
			var materialFront = new THREE.MeshBasicMaterial({ ambient: 0xffffff, transparent: false, map: texture });
			var color = OBJECT.color || 0xff0000;
			var materialSide = new THREE.MeshBasicMaterial({color: color, ambient: color});
			var materialBack = new THREE.MeshBasicMaterial({color: 0xffffff, ambient: 0xffffff});
			var materials = [materialFront, materialSide, materialBack];
			var material = new THREE.MeshFaceMaterial(materials);
			var mesh = new THREE.Mesh(geometry, material);
			for ( var face in mesh.geometry.faces ) {
				if (mesh.geometry.faces[ face ].normal.z < 0){
					mesh.geometry.faces[ face ].materialIndex = 2;
				}
			}
			geometry.computeBoundingBox();
			var max     = geometry.boundingBox.max;
			var min     = geometry.boundingBox.min;

			var offset  = new THREE.Vector2(0 - min.x, 0 - min.y);
			var range   = new THREE.Vector2(max.x - min.x, max.y - min.y);

			OBJECT = setMinMax(OBJECT, min, max);

			geometry.faceVertexUvs[0] = [];
			var faces = geometry.faces;
			for (i = 0; i < geometry.faces.length ; i++) {
				var v1 = geometry.vertices[faces[i].a];
				var v2 = geometry.vertices[faces[i].b];
				var v3 = geometry.vertices[faces[i].c];
				geometry.faceVertexUvs[0].push([
					new THREE.Vector2( ( v1.x + offset.x ) / range.x , ( v1.y + offset.y ) / range.y ),
					new THREE.Vector2( ( v2.x + offset.x ) / range.x , ( v2.y + offset.y ) / range.y ),
					new THREE.Vector2( ( v3.x + offset.x ) / range.x , ( v3.y + offset.y ) / range.y )
				]);

			}
			geometry.uvsNeedUpdate = true;
			GROUP.add(mesh);

			//Back
			var meshBack = mesh.clone();
			meshBack.position.set( 0, 0, -OBJECT.bleed );
			GROUP.add(meshBack);

			// Sides
			for(var side in currentShape) {
				if( side == 'center' ) continue;
				var sideShape = currentShape[side];
				var g = new THREE.BoxGeometry(1, 1, 0);
				var m = {
				        color:0xffffff,
				        ambient: 0xffffff
				    };
				if( OBJECT.color ) {
					m.color = OBJECT.color;
				    m.ambient = OBJECT.color;
				} else if( sideShape.image ) {
					var texture = THREE.ImageUtils.loadTexture( sideShape.image, {}, function(){
				        render();
						renderComplete(OBJECT.imageTotal, ++finish);
				    });
				    m.map = texture;
				}
				var id = Number(side.replace('bleed_', ''));
				var current = id;
				var next = current + 1;
				if( next > shapePoint.length - 1 ) {
					next -= shapePoint.length;
				}
				g.vertices[0].x = g.vertices[2].x = shapePoint[ current ].x;
				g.vertices[0].y = g.vertices[2].y = shapePoint[ current ].y;
				g.vertices[1].x = g.vertices[3].x = shapePoint[ next ].x;
				g.vertices[1].y = g.vertices[3].y = shapePoint[ next ].y;
				g.vertices[2].z = g.vertices[3].z = -OBJECT.bleed;
				var mesh = new THREE.Mesh(g, new THREE.MeshBasicMaterial(m));
                GROUP.add(mesh);
			}
			scene.add(GROUP);
		}
		controls.target.x = OBJECT.max.x / 2;
		controls.target.y = (OBJECT.min.y + OBJECT.max.y) / 2;
		controls.target.z = 0;
		controls.addEventListener('change', render);

		//
		container.addEventListener( 'resize', onWindowResize, false );
		animate();

		return false;
	}

	function bleedKey()
	{

	}

	function setMinMax(OBJECT, min, max)
	{
		if( OBJECT.max == undefined ) {
			OBJECT.max = {x: max.x, y: max.y};
		}
		if( OBJECT.max.x < max.x ) {
			OBJECT.max.x = max.x;
		}
		if( OBJECT.max.y < max.y ) {
			OBJECT.max.y = max.y;
		}

		if( OBJECT.min == undefined ) {
			OBJECT.min = {x: min.x, y: min.y};
		}
		if( OBJECT.min.x > min.x ) {
			OBJECT.min.x = min.x;
		}
		if( OBJECT.min.y > min.y ) {
			OBJECT.min.y = min.y;
		}
		return OBJECT;
	}

	function onWindowResize()
	{
		camera.aspect = 1000 / 550;
		camera.updateProjectionMatrix();
		renderer.setSize( 1000, 550 );
		render();
	}

	function animate()
	{
		requestAnimationFrame(animate);
		controls.update();
	}

	function render()
	{
		renderer.render( scene, camera );
	}

	function renderComplete(imageTotal, number)
	{
		if( number == imageTotal ) {
			Main.previewRenderFinished = true;
		}
	}

	function findMedium(shapes)
	{
		var max, min;
		for(var i in shapes) {
			var points = shapes[i].center.points;
			for(var j = 0; j < points.length; j++) {
				var point = points[j];
				point.x = Number(point.x);
				point.y = Number(point.y);
				if( min == null ) {
					min = point.y;
				}
				if( max == null ) {
					max = point.y;
				}
				if( min > point.y ) {
					min = point.y;
				}
				if( max < point.y ) {
					max = point.y;
				}
			}
		}
		return Number((max+min)/2);
	}

	return {
		draw: function(OBJECT) {
			build3D(OBJECT);
		},
		render: function() {
			render();
		}
	}
}();
</script>