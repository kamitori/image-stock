<?php
    if(isset($_FILES['file'])){
	move_uploaded_file($_FILES['file']['tmp_name'],'test.jpg');
    list($width, $height) = getimagesize('test.jpg');
    $image = new Imagick('test.jpg');
    $image->cropImage(($width - $width* 0.15 * 2),($height * 0.15), ($width* 0.15), 0);
    $image->writeImage('test-top.jpg');

    $image = new Imagick('test.jpg');
    $image->cropImage(($width* 0.15),($height - $height*0.15 *2), 0, ($height * 0.15));
    $image->writeImage('test-left.jpg');

    $image = new Imagick('test.jpg');
    $image->cropImage(($width* 0.15),($height - $height*0.15 *2), ($width - $width* 0.15), ($height * 0.15));
    $image->writeImage('test-right.jpg');

    $image = new Imagick('test.jpg');
    $image->cropImage(($width - $width* 0.15 * 2),($height * 0.15), ($width* 0.15), ($height - $height*0.15 ));
    $image->writeImage('test-bottom.jpg');


    $image = new Imagick('test.jpg');
    $image->cropImage(($width - $width* 0.15 * 2),($height - $height*0.15 *2), ($width* 0.15), ($height * 0.15));
    $image->writeImage('test-center.jpg');

    list($width3d, $height3d) = getimagesize('test-center.jpg');
    $ratio = $width3d/$height3d;
    $ratio_depth = $width3d/$widthdepth;
    while($width3d > 12)
        $width3d = round($width3d / 2);
    list($widthdepth, $heightdepth) = getimagesize('test-left.jpg');
    $height3d= round($width3d / $ratio);
    $widthdepth= round($width3d / $ratio_depth);
    if($widthdepth < 0.5)
        $widthdepth = 0.5;
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="three.min.js"></script>
        <script type="text/javascript" src="requestAnimFrame.js"></script>
        <script type="text/javascript" src="OrbitControls.js"></script>
        <script type="text/javascript" src="Detector.js"></script>
        <!--make sure this is last-->
        <style>
            body { margin: 0; padding: 0; font-family: "Georgia", serif; color: #444; }
        </style>
    </head>
    <body>
        Original Image
        <img src="test.jpg" <?php if($width > 640) echo 'style="width:640px;"' ?> />
        <hr />
        <a href="http://vi.anvyonline.com/canvas3d/">Back</a>
        <div id="container"></div>
        <script type="text/javascript">

            if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

            var container;

            var camera, controls, scene, renderer;
            var WebGLSupported = isWebGLSupported();
            init();
            render();
            
            function animate() {

                requestAnimationFrame(animate);
                controls.update();

            }

            function init() {

                camera = new THREE.PerspectiveCamera( 60, window.innerWidth / window.innerHeight, 1, 1000 );
                camera.position.z = 10;

                controls = new THREE.OrbitControls( camera );
                controls.damping = 0.2;
                controls.addEventListener( 'change', render );

                scene = new THREE.Scene();
                scene.fog = new THREE.FogExp2( 0xcccccc, 0.002 );

                // world
                var materialClass = WebGLSupported ? THREE.MeshLambertMaterial : THREE.MeshBasicMaterial;
                var left    = new materialClass( { map: THREE.ImageUtils.loadTexture( 'test-left.jpg'  )} );
                var right   = new materialClass( { map: THREE.ImageUtils.loadTexture( 'test-right.jpg' ) } );
                var top     = new materialClass( { map: THREE.ImageUtils.loadTexture( 'test-top.jpg'   )} );
                var bottom  = new materialClass( { map: THREE.ImageUtils.loadTexture( 'test-bottom.jpg' )  } );
                var center  = new materialClass( { map: THREE.ImageUtils.loadTexture( 'test-center.jpg' ) } );
                var back    = new materialClass( { color: 0x333333 } );
                var materials = [
                    right,      // Right side
                    left,       // Left side
                    top,        // Top side
                    bottom,     // Bottom side
                    center,     // Center side
                    back,       // Back side
                  ];

                var Pic3D =  new THREE.Mesh( new THREE.BoxGeometry( <?php echo $width3d; ?>, <?php echo $height3d; ?>, 1, 4, 4, 1 ) ,  new THREE.MeshFaceMaterial( materials ));
                scene.add( Pic3D );

                // lights

                light = new THREE.PointLight( 0xffffff, .4 );
                light.position.set( 10, 10, 10 );
                scene.add( light );

                ambientLight = new THREE.AmbientLight( 0xbbbbbb );
                scene.add( ambientLight );


                // renderer

                // var renderer = WebGLSupported ? new THREE.WebGLRenderer({ antialias: false }) : new THREE.CanvasRenderer();
                // renderer.setSize( window.innerWidth, window.innerHeight );

                renderer = new THREE.WebGLRenderer( { antialias: false } );
                renderer.setClearColor( scene.fog.color, 0 );
                renderer.setSize( window.innerWidth, window.innerHeight );

                container = document.getElementById( 'container' );
                container.appendChild( renderer.domElement );

                //

                window.addEventListener( 'resize', onWindowResize, false );

                controls.addEventListener('change', render);
                animate();

            }

            function onWindowResize() {

                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize( window.innerWidth, window.innerHeight );

                render();

            }

            function render() {
                renderer.render( scene, camera );

            }
            function isWebGLSupported() {

              var cvs = document.createElement('canvas');
              var contextNames = ["webgl","experimental-webgl","moz-webgl","webkit-3d"];
              var ctx;

              if ( navigator.userAgent.indexOf("MSIE") >= 0 ) {
                try {
                  ctx = WebGLHelper.CreateGLContext(cvs, 'canvas');
                } catch(e) {}
              } else {
                for ( var i = 0; i < contextNames.length; i++ ) {
                  try {
                    ctx = cvs.getContext(contextNames[i]);
                    if ( ctx ) break;
                  } catch(e){}
                }
              }

              if ( ctx ) return true;
              return false;
            }

        </script>
    </body>
</html>
<?php
    } else {
    ?>
<form method="post" enctype="multipart/form-data">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file"><br>
    <input type="submit" name="submit" value="Submit">
</form>
<?php } ?>
