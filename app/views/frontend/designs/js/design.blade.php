<script type="text/javascript">
var Design = function() {
	var draw;
	var defs;
	var seletedImage;
	var last = {
		image: '{{ URL.'/assets/images/default.jpg' }}',
		filter: 'original',
		zoom: 1,
		flipX: 1,
		flipY: 1,
		rotate: 0
	};
	var defaultImg = '{{ URL.'/assets/images/default.jpg' }}';
	var imageWrap = ['m_wrap', 'natural'];
	var wrapAttribute = {
		'black': {'fill': 'black', 'fill-opacity': 1, 'stroke': null, 'stroke-width': null},
		'white': {'fill': 'white', 'fill-opacity': 1, 'stroke': '#333333', 'stroke-width': 0.5},
		'natural': {'fill': 'white', 'fill-opacity': 0.4, 'stroke': null, 'stroke-width': null},
		'none': {'fill': null, 'fill-opacity': null, 'stroke': null, 'stroke-width': null},
	}
	var svgSetup = {
		main: {
			// prefixViewBox: (-{{$product['layout']['bleed'] or 0}}) +' '+ (-{{$product['layout']['bleed'] or 0}}),
			prefixViewBox: '0 0',
			wrap: '{{ $product['layout']['wrap'] or 'white' }}',
			bleed: {{ $product['layout']['bleed'] or 0 }},
			width: {{ $product['layout']['real_width'] or 0 }},
			height: {{ $product['layout']['real_height'] or 0 }},
			view_dpi: {{ $product['layout']['view_dpi'] or 0 }},
			zoom: 1
		},
		elements: [
		]
	};

	function rotate(r, element)
	{
		var offset = element.image.offset;
		seletedImage.rotate(r, offset.minX+(offset.maxX-offset.minX)/2, offset.minY+(offset.maxY-offset.minY)/2);
		element.image.rotate = r;
		last.rotate = r;
		ColorPicker.reDraw = true;
	}

	function flipX(element)
	{
		if( element.image.flipX == 1 ) {
			element.image.flipX = -1;
		} else {
			element.image.flipX = 1;
		}
		last.flipX = element.image.flipX;
        __flip(seletedImage, element);
	}

	function flipY(element)
	{
		if( element.image.flipY == 1 ) {
			element.image.flipY = -1;
		} else {
			element.image.flipY = 1;
		}
		last.flipY = element.image.flipY;
        __flip(seletedImage, element);
	}

	function __flip(img, element)
	{
		var x, y;
		var id = img.node.getAttribute('id').replace('image-', '');
		if( $.inArray(wrap, imageWrap) != -1 ) {
			var box = SVG.get('shape-path-'+ id);
		} else {
			var box = SVG.get('bleed-'+ id);
		}
		var boxX = box.x();
		var boxY = box.y();
		var boxWidth = box.width();
		var boxHeight = box.height();
		var scale = __parseScale(img.node.getAttribute('transform'));
		if( element.image.flipX == -1 && scale.x != -1 ) {
			element.image.x = (2*boxX - element.image.x + boxWidth) * -1;
		}
		if( element.image.flipY == -1 && scale.y != -1 ) {
			element.image.y = (2*boxY - element.image.y + boxHeight) * -1;
		}
		if( scale.x == -1 && element.image.flipX != -1 ) {
			element.image.x = 2*boxX + element.image.x + boxWidth;
		}
		if( scale.y == -1 && element.image.flipY != -1 ) {
			element.image.y = 2*boxY + element.image.y + boxHeight;
		}
		var offset = element.image.offset;
		var tmpImage = new Image();
		tmpImage.src = element.image.url;
		size = __fitBox(tmpImage.width, tmpImage.height, offset.maxX - offset.minX, offset.maxY - offset.minY);
		var width = size['w'] * element.image.zoom;
		var height = size['h'] * element.image.zoom;
		img.width(width);
		img.height(height);;
		minX = offset.maxX - width;
		minY = offset.maxY - height;
		maxX = offset.minX + width;
		maxY = offset.minY + height;
		img.draggable({
			minX: minX,
			minY: minY,
			maxX: maxX,
			maxY: maxY,
			flipX: element.image.flipX,
			flipY: element.image.flipY,
		});
		img.scale(element.image.flipX, element.image.flipY);
		x = element.image.x;
		y = element.image.y;
		if( element.image.flipX == -1 ) {
			x *= -1;
		}
		img.x( x );
		if( element.image.flipY == -1 ) {
			y *= -1;
		}
		img.y( y );
		ColorPicker.reDraw = true;
	}

	function zoom(z, element, img)
	{
		if( img == undefined ) {
			img = seletedImage;
		}
		element.image.zoom = z;
		__flip(img, element)
	}

	function zoomInAll()
	{
		var zoom = svgSetup.main.zoom;
		zoom += 0.2;
		if( zoom > 2 ) {
			return false;
		}
		__zoomAll(zoom);
	}

	function zoomOutAll()
	{
		var zoom = svgSetup.main.zoom;
		zoom -= 0.2;
		if( zoom < 0.2 ) {
			return false;
		}
		__zoomAll(zoom);
	}

	function resetZoom()
	{
		__zoomAll(1);
	}

	function __zoomAll(zoom)
	{
		if( $('#svg-main .shape-path.active').length ) {
			$('#svg-main .shape-path.active')[0].instance.removeClass('active');
			seletedImage = undefined;
		}
		var width = svgSetup.main.width;
		var height = svgSetup.main.height;
		SVG.get('svg-main').attr({
			'width': width*zoom,
			'height': height*zoom,
			'viewBox': svgSetup.main.prefixViewBox +' '+ width +' '+ height
		});
		svgSetup.main.zoom = zoom;
		Main.zoomAll(zoom);
	}

	function wrap(wrap)
	{
		if( wrap == 'm_wrap' ) {
			return false;
		}
		var change = false;
		var attribute = {};
		if( $.inArray(wrap, ['white', 'black', 'natural']) != -1 ) {
			attribute = wrapAttribute[wrap];
		} else if( wrap != 'm_wrap' ) {
			var tmpAttribute = $.extend({}, wrapAttribute.black);
			attribute = $.extend(tmpAttribute, {fill: wrap});
		}
		if( wrap != svgSetup.main.wrap ) {
			change = true;
			if( wrap.indexOf('#') != -1 && svgSetup.main.wrap.indexOf('#') != -1 ) {
				change = false;
			}
		}
		if( change ) {
			var imageWrapUsing = $.inArray(wrap, imageWrap) != -1 ? true : false;
			for(var i in svgSetup.elements) {
				if( svgSetup.elements[i].empty ) {
					continue;
				}
				if( imageWrapUsing ) {
					svgSetup.elements[i].image.offset = __findMinMax(svgSetup.elements[i].allPoints.bleedPoints);
				} else {
					svgSetup.elements[i].image.offset = __findMinMax(svgSetup.elements[i].allPoints.points);
				}
				__loadImage(SVG.get('image-'+i), {changeWrap: true});
			}
		}
		if( wrap == 'm_wrap' ) {
			$('#svg-main g.group-bleed').each(function() {
				// this.instance.hide();
			});
			if( $('#svg-main g.group-mirror-bleed').length ) {
				$('#svg-main g.group-mirror-bleed').each(function() {
					this.instance.show();
				});
			} else {
				__drawMirror();
			}
		} else {
			$('#svg-main g.group-mirror-bleed').each(function() {
				this.instance.hide();
			});
			$('#svg-main g.group-bleed').each(function() {
				$('.bleed', this).each(function(){
					this.instance.attr(attribute)
				});
				this.instance.show();
			});
		}
		svgSetup.main.wrap = wrap;
	}

	function changeImage(src, element, dataId)
	{
		element.image.url = src;
		element.image.fresh = true
		if( dataId ) {
			seletedImage.attr('data-id', dataId);
		}
		__loadImage(seletedImage);
		ColorPicker.reDraw = true;
	}

	function filter(filter, img)
	{
		last.filter = filter;
		img.unfilter(true);
		if(filter == 'grayscale') {
		    img.filter(function(add) {
		      add.colorMatrix('saturate', 0);
		    });
		} else if(filter == 'sepia') {
		    img.filter(function(add) {
		      add.colorMatrix('matrix', [ .543, .669, .119, 0, 0
		                                , .249, .626, .130, 0, 0
		                                , .172, .334, 0.2, 0, 0
		                                , .000, .000, .000, 1, 0 ]);
		    });
		}
		var id = img.node.getAttribute('id');
		var element = svgSetup.elements[ id.replace('image-', '') ];
		element.image.filter = filter;
	}

	function drawSVG()
	{
		$('#svg_div').html('');
		draw = SVG('svg_div').size(svgSetup.main.width, svgSetup.main.height).attr({'id': 'svg-main', 'viewBox': svgSetup.main.prefixViewBox +' '+ svgSetup.main.width +' '+ svgSetup.main.height});
		defs = draw.defs().attr('id', 'main-defs');
		// draw shape base on svgSetup
		for( var i in svgSetup.elements ) {
			var element = svgSetup.elements[i];
			var d;
			d = element.d;
			var attribute = $.extend({id: 'polygon-'+ i}, element, {image: null, width: null, height: null, x: null, y: null});
			// draw temporate clip path
			var path = draw.path(d)
								.attr(attribute);
			if( element.empty ) {
				path.attr({
					fill: '#ffffff'
				});
				continue;
			}
			var pathArray = path.array.value;
			path.remove();
			// remove it
			// get point of shape, and bleed point
			var allPoints = __bleedPoints(pathArray, element);
			svgSetup.elements[i].allPoints = allPoints;
			// draw new clip path using above points and bleed points
			var clip = draw.clip()
							.attr('id', 'clip-'+ i)
							.add(__drawClipPath(allPoints, {
									'id': 'clip-path-'+ i,
									'class': 'clip-path'
								}));
			defs.add(clip);
			// find min max x, y for dragging
			if( $.inArray(svgSetup.main.wrap, imageWrap) != -1 ) {
				svgSetup.elements[i].image.offset = __findMinMax(allPoints.bleedPoints);
			} else {
				svgSetup.elements[i].image.offset = __findMinMax(allPoints.points);
			}
			var attribute = $.extend({id: 'image-'+ i, 'class': 'main-image'}, element.image, {offset: null, allPoints: null, zoom: null, rotate: null, flipX: null, flipY: null, fresh: null});
			var image = draw.image( element.image.url )
							.attr(attribute)
							.loaded(function(){
								__loadImage(this, {imageExtraOption: true});
							})
							.on('click', function(event){
								if( !$('#preview_box').is(':visible') ) {
									seletedImage = this;
									var id = this.node.getAttribute('id');
										id = id.replace('image-', '');
									var element = svgSetup.elements[ id ];
									if( $('#svg-main .shape-path.active').length ) {
										$('#svg-main .shape-path.active')[0].instance.removeClass('active');
									}
									SVG.get('shape-path-'+ id).addClass('active');
									Main.filter(element.image.filter);
									Main.rotate(element.image.rotate);
									Main.zoom(element.image.zoom);
									Main.showSlider();
								}
							});
			// add image to group (hack confict if using filter), with above clip path
			draw.group()
				.clipWith(clip)
				.add(image)
				.attr('id', 'group-image-'+ i);
			// draw bleed shape, and add bleed shape and image group to 1 group
			var group = draw.group()
				.add( SVG.get('group-image-'+ i) )
				.attr('id', 'group-'+ i)
			__drawBleed(allPoints, group, svgSetup.main.wrap, i);
		}
	}

	function __loadImage(img, options)
	{
		var x;
		var y;
		var id;
		var size;
		var minX;
		var minY;
		var maxX;
		var maxY;
		var width;
		var height;
		var offset;
		var element;
		if( options == undefined ) {
			options = {};
		}
		options = $.extend({imageExtraOption: false, changeWrap: false}, options);
		id = img.node.getAttribute('id').replace('image-', '');
		element = svgSetup.elements[ id ];
		offset = element.image.offset;
		img.attr('href', element.image.url);
		var tmpImage = new Image();
		tmpImage.src = element.image.url;
		size = __fitBox(tmpImage.width, tmpImage.height, offset.maxX - offset.minX, offset.maxY - offset.minY);
		width = size['w'] * element.image.zoom;
		height = size['h'] * element.image.zoom;
		img.width(width);
		img.height(height);
		minX = offset.maxX - width;
		minY = offset.maxY - height;
		maxX = offset.minX + width;
		maxY = offset.minY + height;
		img.draggable({
			minX: minX,
			minY: minY,
			maxX: maxX,
			maxY: maxY,
			flipX: element.image.flipX,
			flipY: element.image.flipY,
		});
		if( element.image.fresh || options.changeWrap ) {
			x = minX;
		} else {
			x = Number(element.image.x);
		}

		if( element.image.fresh || options.changeWrap) {
			y = minY;
		} else {
			y = Number(element.image.y);
		}
		delete element.image.fresh;
		element.image.x = x;
		element.image.y = y;
		img.x( x );
		img.y( y );
		if( options.imageExtraOption ) {
			img.beforedrag = function() {
				if( $('#preview_box').is(':visible') ) {
					return false;
				}
				return true;
			};
			img.dragmove = function() {
				var id = this.node.getAttribute('id');
					id = id.replace('image-', '');
				var element = svgSetup.elements[ id ];
				element.image.x = this.x();
				element.image.y = this.y();
				ColorPicker.reDraw = true;
			};
			img.rotate(element.image.rotate, offset.minX+(offset.maxX-offset.minX)/2, offset.minY+(offset.maxY-offset.minY)/2);
			filter(element.image.filter, img);
		}
		img.scale(element.image.flipX, element.image.flipY);
	}

	function __drawClipPath(allPoints, attribute)
	{
		var path = '';
		for(var pathPosition in allPoints.points) {
			var start = 0;
			for(var i in allPoints.points[pathPosition]) {
				i = Number(i);
				var point = allPoints.points[pathPosition][i];
				var bleedPoint = allPoints.bleedPoints[pathPosition][i];
				if( !start ) {
					path += 'M'+ point.x +' '+point.y;
				} else {
					path += 'L'+ point.x +' '+point.y;
				}
				path += 'L'+ bleedPoint[0].x +' '+ bleedPoint[0].y;
				path += 'L'+ bleedPoint[1].x +' '+ bleedPoint[1].y;
				next = i + 1;
				if( next > allPoints.points[pathPosition].length - 1 ) {
					next = next - allPoints.points[pathPosition].length;
				}
				path += 'L'+ allPoints.points[pathPosition][next].x +' '+ allPoints.points[pathPosition][next].y;
				start++;
			}
			path += 'Z';
		}
		return draw.path(path).attr(attribute);
	}

	function __drawBleed(allPoints, group, wrap, position)
	{
		var shapePath = '';
		var bleedPath = '';
		var g = draw.group()
						.attr({'id': 'group-bleed-'+ position, 'class': 'group-bleed'})
		for(var pathPosition in allPoints.points) {
			var start = 0;
			if( shapePath != '' ) {
				shapePath += 'Z ';
			}
			for( var i in allPoints.points[pathPosition] ) {
				i = Number(i);
				var point = allPoints.points[pathPosition][i];
				var bleedPoint = allPoints.bleedPoints[pathPosition][i];
				if( !start ) {
					shapePath += 'M'+ point.x +' '+point.y;
				} else {
					shapePath += 'L'+ point.x +' '+point.y;
				}
				bleedPath += 'M'+ point.x +' '+point.y;
				bleedPath += 'L'+ bleedPoint[0].x +' '+ bleedPoint[0].y;
				bleedPath += 'L'+ bleedPoint[1].x +' '+ bleedPoint[1].y;
				next = i + 1;
				if( next > allPoints.points[pathPosition].length - 1 ) {
					next = next - allPoints.points[pathPosition].length;
				}
				bleedPath += 'L'+ allPoints.points[pathPosition][next].x +' '+ allPoints.points[pathPosition][next].y +'Z';
				start++;
			}

		}
		var attribute = {
						'id': 'bleed-'+ position,
						'class': 'bleed'
					};

		if( $.inArray(wrap, ['white', 'black', 'natural']) != -1 ) {
			attribute = $.extend(attribute, wrapAttribute[wrap]);
		} else if( wrap != 'm_wrap' ) {
			var tmpAttribute = $.extend({}, wrapAttribute.black);
			attribute = $.extend(attribute, tmpAttribute, {fill: wrap});
		}
		group.add(
				g.add(
					draw.path(bleedPath)
					.attr(attribute)
					)
				)
			.add(
				draw.path(shapePath +'Z')
				.attr({'class' : 'shape-path', 'id': 'shape-path-'+ position, fill: 'none'})
			);

		if( wrap == 'm_wrap' ) {
			wrap(wrap);
		}
	}

	function __drawMirror()
	{
		for( var i in svgSetup.elements ) {
			console.log(svgSetup.elements[i]);
			var allPoints = svgSetup.elements[i].allPoints;
			var revertBleedPoints = [];
			for(var pathPosition in allPoints.points) {
				//Loop array top of a path
				for( var current in allPoints.points[pathPosition] ) {
					var current = parseInt(current);
					var next = current + 1;
					var last = current + 2;
					if( next > allPoints.points[pathPosition].length - 1 ) {
						next = next - allPoints.points[pathPosition].length;
					}
					if( last > allPoints.points[pathPosition].length - 1 ) {
						last = last - allPoints.points[pathPosition].length;
					}
					console.log(last,current,next);
					// find reverse bleed point
					var m1 = Pointer.find(svgSetup.main.bleed, allPoints.points[pathPosition][current], allPoints.points[pathPosition][next], allPoints.points[pathPosition][last], 'reverse');
					var m2 = Pointer.find(svgSetup.main.bleed, allPoints.points[pathPosition][next], allPoints.points[pathPosition][current], allPoints.points[pathPosition][last], 'reverse');
					// use for debug
					//draw.rect(3, 3).x(m1.x).y(m1.y).fill('red');
					//draw.rect(3,3).x(m2.x).y(m2.y).fill('yellow');
					revertBleedPoints.push([
						m1,
						m2
					]);
				}//End loop array top of a path
				if( allPoints.revertBleedPoints == undefined ) {
					allPoints.revertBleedPoints = {};
				}
				allPoints.revertBleedPoints[pathPosition] = revertBleedPoints;
			}
			var g = draw.group()
						.attr({'id': 'group-mirror-bleed-'+ i, 'class': 'group-mirror-bleed'});
			var useGroup = draw.group()
								.attr({'id': 'use-mirror-bleed-'+ i, 'class': 'use-mirror-bleed'})
			var shapePath = SVG.get('shape-path-'+ i);
			var centerX = shapePath.cx();
			var centerY = shapePath.cy();
			//Loop array top of a path
			var arr_color = ['red','blue','yellow','green'];
			for(var pathPosition in allPoints.points) {
				for( var j in allPoints.points[pathPosition] ) {
					j = Number(j);
					var point = allPoints.points[pathPosition][j];
					var reverseBleedPoint = allPoints.revertBleedPoints[pathPosition][j];
					var path = 'M'+ point.x +' '+ point.y;
					next = j + 1;
					if( next > allPoints.points[pathPosition].length - 1 ) {
						next = next - allPoints.points[pathPosition].length;
					}
					path += 'L'+ allPoints.points[pathPosition][next].x +' '+ allPoints.points[pathPosition][next].y;
					path += 'L'+ reverseBleedPoint[1].x +' '+ reverseBleedPoint[1].y;
					path += 'L'+ reverseBleedPoint[0].x +' '+ reverseBleedPoint[0].y +'Z';

					var group = draw.group()
									.attr('id', 'use-'+ i +'.'+ j +'-'+ next);
					var pathitem = draw.path(path).attr({'fill':arr_color[j],'stroke':arr_color[j], 'stroke-width': 0});
					var clipPath = draw.clip()
										.add(pathitem)
										.attr('id', 'clip-use-'+ i +'.'+ j +'-'+ next);

						group.add(draw.use(SVG.get('image-'+ i))
									.clipWith(clipPath)
									.x(0)
									.y(0)
									.scale(1,1)
								)
								.add(clipPath);
						useGroup.add(group);
						// Toa do moi cu use mirror
						var ux = point.x-reverseBleedPoint[0].x;
						var uy = point.y-reverseBleedPoint[0].y;
						var PointerInAxisX = {x: point.x, y: 0};
						var angleXclockDirect  = Pointer.angle(PointerInAxisX, point, allPoints.points[pathPosition][next]);
						var angleX = parseFloat(angleXclockDirect%90);
						var rotate = 2*(90-angleX);
						if(angleXclockDirect>90 && angleXclockDirect<270){
							rotate = -1*rotate;
						}
						var sx=1; var sy=1;
						if((angleXclockDirect>45 && angleXclockDirect<135) || (angleXclockDirect>225 && angleXclockDirect<315) ){
							sy=-1;
							uy = -1*(point.x+svgSetup.main.bleed);
						}
						else{
							sx=-1;
							ux = -1*(point.y+svgSetup.main.bleed);
						}
						var RotateCenter={x:ux,y:uy};

						console.log(arr_color[j],angleX);
						g.add(
								draw.use(group)
									.attr({
										'class': 'bleed',
										'x':ux,
										'y':uy,
										'transform':'scale('+sx+','+sy+') rotate('+rotate+','+RotateCenter.x+','+RotateCenter.y+')'
									})
							);
					// var prevPoint = {x: reverseBleedPoint[1].x, y: reverseBleedPoint[0].y};
					// var angle  = Pointer.angle(prevPoint, reverseBleedPoint[1], reverseBleedPoint[0]);

					// 	draw.path('M'+prevPoint.x+' '+prevPoint.y+'L'+reverseBleedPoint[1].x+' '+reverseBleedPoint[1].y+'L'+reverseBleedPoint[0].x+' '+reverseBleedPoint[0].y).attr({'fill': 'none', 'stroke': arr_color[j], 'stroke-width': 2});


				}
			}
			defs.add(useGroup);
			SVG.get('group-'+ i).add(g);
		}
	}

	function __findMinMax(array, number)
	{
		if( number == undefined ) {
			number = {};
		}
		for(var i in array) {
			if( array[i].length ) {
				number = __findMinMax(array[i], number);
			} else {
				if( number.minX == undefined ) {
					number.minX = array[i].x;
				}
				if( number.maxX == undefined ) {
					number.maxX = array[i].x;
				}
				if( number.minY == undefined ) {
					number.minY = array[i].y;
				}
				if( number.maxY == undefined ) {
					number.maxY = array[i].y;
				}
				if( number.minX > array[i].x ) {
					number.minX = array[i].x;
				}
				if( number.minY > array[i].y ) {
					number.minY = array[i].y;
				}
				if( number.maxX < array[i].x ) {
					number.maxX = array[i].x;
				}
				if( number.maxY < array[i].y ) {
					number.maxY = array[i].y;
				}
			}
		}
		return number;
	}

	function __bleedPoints(pathArray, element)
	{
		var pathPosition = 0;
		var bleedPoints = {};
		var points = {};
		for( var p in pathArray ) {
			var array = pathArray[p];
			// M, x, y
			// L, x, y
			// Z
			// we only need M, x, y or L, x, y
			if( array.length != 3 ) {
				pathPosition++;
				continue;
			}
			element.rotate = Number(element.rotate);
			// if it was rotated, must find new potision after rotate
			if( element.rotate ) {
				// draw temporate line, from point of shape to point of rotation
				// then rotate it
				// and find new position
				var transform = element.transform.replace('rotate('+element.rotate+' ', '').replace(')', '').split(' ');
				transform[0] = Number(transform[0]);
				transform[1] = Number(transform[1]);
				var tmpPoint = draw.path('M'+array[1]+' '+array[2] +'L'+ transform[0] +' '+transform[1]);
			  	tmpPoint.rotate(element.rotate, transform[0], transform[1]);
			  	var point = __getMetrics(tmpPoint.node);
			  	tmpPoint.remove();
			  	// new point is on the same line with point of rotation, so find point of rotation
			  	// then find it, it will be 0-2 or 1-3
			  	for(var i in point.newp) {
			  		point.newp[i].x = Number(point.newp[i].x);
			  		point.newp[i].y = Number(point.newp[i].y);
			  		if( point.newp[i].x == transform[0] && point.newp[i].y == transform[1] ) {
			  			if( i % 2 == 0 ) {
			  				array[1] = point.newp[2 - i].x;
			  				array[2] = point.newp[2 - i].y;
			  			} else {
			  				array[1] = point.newp[4 - i].x;
			  				array[2] = point.newp[4 - i].y;
			  			}
			  			break;
			  		}
			  	}
			}
			if( points['path_'+ pathPosition] == undefined ) {
				points['path_'+ pathPosition] = [];
			}
			points['path_'+ pathPosition].push({x: Number(array[1]), y: Number(array[2])});
		}
		for( var pathPosition in points ) {
			for( current in points[pathPosition]) {
				var current = Number(current);
				var next = current + 1;
				var last = current + 2;
				if( next > points[pathPosition].length - 1 ) {
					next = next - points[pathPosition].length;
				}
				if( last > points[pathPosition].length - 1 ) {
					last = last - points[pathPosition].length;
				}
				// find bleed point
				var m1 = Pointer.find(Number(svgSetup.main.bleed), points[pathPosition][current], points[pathPosition][next], points[pathPosition][last]);
				var m2 = Pointer.find(Number(svgSetup.main.bleed), points[pathPosition][next], points[pathPosition][current], points[pathPosition][last]);
				// use for debug
				/*draw.rect(5, 5).x(m1.x).y(m1.y).fill('red');
				draw.rect(5,5).x(m2.x).y(m2.y).fill('red');*/
				if( bleedPoints[pathPosition] == undefined ) {
					bleedPoints[pathPosition] = [];
				}
				bleedPoints[pathPosition].push([
						m1,
						m2
					]);
			}
		}
		return {'bleedPoints' : bleedPoints, 'points': points};
	}

	function __fitBox(img_w,img_h,box_w,box_h)
	{
	    var ret = {};
	    if(box_w/box_h > img_w/img_h){ //fit width
	        ret['w'] = box_w;
	        ret['h'] = Math.round(img_h/(img_w/box_w));
	        ret['scale'] = img_w/box_w;
	        ret['scale_by'] = 'W';
	    }else{
	        ret['w'] = Math.round(img_w/(img_h/box_h));
	        ret['h'] = box_h;
	        ret['scale'] = img_h/box_h;
	        ret['scale_by'] = 'H';
	    }
	    return ret;
	}

	function __parseScale (transform)
	{
		var scale  = /scale\(\s*([^\s,)]+)[ ,]([^\s,)]+)/.exec(transform);
		if( scale ) {
			return {x: Number(scale[1]), y: Number(scale[2])};
		} else {
			return {x: 0, y: 0};
		}
	}

	function __getMetrics(el)
	{
	    function pointToLineDist(A, B, P)
	    {
	        var nL = Math.sqrt((B.x - A.x) * (B.x - A.x) + (B.y - A.y) * (B.y - A.y));
	        return Math.abs((P.x - A.x) * (B.y - A.y) - (P.y - A.y) * (B.x - A.x)) / nL;
	    }

	    function dist(point1, point2)
	    {
	        var xs = 0,
	            ys = 0;
	        xs = point2.x - point1.x;
	        xs = xs * xs;
	        ys = point2.y - point1.y;
	        ys = ys * ys;
	        return Math.sqrt(xs + ys);
	    }
	    var b = el.getBBox(),
	        objDOM = el,
	        svgDOM = objDOM.ownerSVGElement;
	    // Get the local to global matrix
	    var matrix = svgDOM.getTransformToElement(objDOM).inverse(),
	        oldp = [[b.x, b.y], [b.x + b.width, b.y], [b.x + b.width, b.y + b.height], [b.x, b.y + b.height]],
	        pt, newp = [],
	        obj = {},
	        i, pos = Number.POSITIVE_INFINITY,
	        neg = Number.NEGATIVE_INFINITY,
	        minX = pos,
	        minY = pos,
	        maxX = neg,
	        maxY = neg;

	    for (i = 0; i < 4; i++) {
	        pt = svgDOM.createSVGPoint();
	        pt.x = oldp[i][0];
	        pt.y = oldp[i][1];
	        newp[i] = pt.matrixTransform(matrix);
	        if (newp[i].x < minX) minX = newp[i].x;
	        if (newp[i].y < minY) minY = newp[i].y;
	        if (newp[i].x > maxX) maxX = newp[i].x;
	        if (newp[i].y > maxY) maxY = newp[i].y;
	    }
	    // The next refers to the transformed object itself, not bbox
	    // newp[0] - newp[3] are the transformed object's corner
	    // points in clockwise order starting from top left corner
	    obj.newp = newp; // array of corner points
	    obj.width = pointToLineDist(newp[1], newp[2], newp[0]) || 0;
	    obj.height = pointToLineDist(newp[2], newp[3], newp[0]) || 0;
	    obj.toplen = dist(newp[0], newp[1]);
	    obj.rightlen = dist(newp[1], newp[2]);
	    obj.bottomlen = dist(newp[2], newp[3]);
	    obj.leftlen = dist(newp[3], newp[0]);
	    // The next refers to the transformed object's bounding box
	    obj.BBx = minX;
	    obj.BBy = minY;
	    obj.BBx2 = maxX;
	    obj.BBy2 = maxY;
	    obj.BBwidth = maxX - minX;
	    obj.BBheight = maxY - minY;
	    return obj;
	}

	function getDPIOption(true_w, true_h, view_w, view_h)
	{
		if(true_w/view_w > true_h/view_h) {
			return view_w/true_w;
		} else {
			return view_h/true_h;
		}
	}

	return {
		svgSetup: function(setup) { //setter or getter
			if( arguments.length == 1 && typeof setup == 'object') {
				svgSetup = setup;
			} else {
				return svgSetup;
			}
		},
		resetSetup: function(layout, shapes) {
			svgSetup = {
				main: {
					prefixViewBox: '0 0',
					wrap: layout.wrap || 'white',
					bleed: layout.bleed || 0,
					width: layout.real_width || 0,
					height: layout.real_height || 0,
					view_dpi: layout.view_dpi || 0,
					zoom: 1
				},
				elements: [
				]
			};
			if( typeof shapes == 'object' ) {
				for(var i in shapes) {
					var object = {
								x: shapes[i].coor_x,
								y: shapes[i].coor_y,
								width: shapes[i].width,
								height: shapes[i].height,
								d: shapes[i].d,
								image: {
									fresh: true,
									url: last.image,
									x: 0,
									y: 0,
									zoom: last.zoom,
									rotate: last.rotate,
									flipX: last.flipX,
									flipY: last.flipY,
									filter: last.filter,
								}
							};
					if( shapes[i].empty == 1 ) {
						object.empty = true;
					}
					svgSetup.elements.push(object);
				}
			}
			drawSVG();
		},
		resize: function(sizew, sizeh) {
			if( sizew ) {
				var sizew = parseFloat(sizew);
				svgSetup.main.realSizew = sizew;
			} else {
				sizew = svgSetup.main.realSizew;
			}
			if( sizeh ) {
				var sizeh = parseFloat(sizeh);
				svgSetup.main.realSizeh = sizeh;
			} else {
				sizeh = svgSetup.main.realSizeh;
			}
			var dpi = 72;
			var max_w = 1000;
			var max_h= 500;
			var svg_bleed = svgSetup.main.realBleed;

			var svg_w = (sizew + 2*svg_bleed) * dpi;
			var svg_h = (sizeh + 2*svg_bleed) * dpi;
			var view_dpi = getDPIOption(svg_w, svg_h, max_w, max_h);
			var svg_bleed_pt = svg_bleed*dpi*view_dpi; //pt
			var layout = {};

			wall_w =  sizew * dpi;
			wall_h =  sizeh * dpi;
			if( wall_w / max_w > wall_h / max_h ) {
				w = max_w;
				view_dpi  = wall_w / w;
				h = wall_h / view_dpi;
			} else {
				h = max_h;
				view_dpi = wall_h / h;
				w = wall_w / view_dpi;
			}
			svgSetup.main.width = w + 2*svg_bleed_pt;
			svgSetup.main.height = h + 2*svg_bleed_pt;
			svgSetup.main.bleed = svg_bleed_pt;

			var x = svg_bleed_pt;
			var y = svg_bleed_pt;
			var width = svgSetup.main.width - 4*x;
			var height = svgSetup.main.height - 4*y;
			var d = 'M'+ x +' '+ y +'L'+ (width-x) +' '+ y +'L'+ (width-x) +' '+ (height-y) +'L'+ x + ' '+ (height-y) +'Z';
			// if( reset ) {s
				svgSetup.elements = [{
					x: x,
					y: y,
					width: width,
					height: height,
					d: d,
					image: {
						fresh: true,
						url: last.image,
						x: 0,
						y: 0,
						zoom: last.zoom,
						rotate: last.rotate,
						flipX: last.flipX,
						flipY: last.flipY,
						filter: last.filter,
					}
				}];
			/*} else {
				svgSetup.elements[0].width = width;
				svgSetup.elements[0].height = height;
				svgSetup.elements[0].bleed = bleed;
				svgSetup.elements[0].view_dpi = view_dpi;
				svgSetup.elements[0].d = d;
			}*/
			drawSVG();
		},
		flipX: function() {
			if( seletedImage == undefined ) {
				SVG.get('image-0').fire('click');
			}
			var id = seletedImage.node.getAttribute('id');
			var element = svgSetup.elements[ id.replace('image-', '') ];
			flipX(element);
		},
		flipY: function() {
			if( seletedImage == undefined ) {
				SVG.get('image-0').fire('click');
			}
			var id = seletedImage.node.getAttribute('id');
			var element = svgSetup.elements[ id.replace('image-', '') ];
			flipY(element);
		},
		rotate: function(r) {
			if( seletedImage == undefined ) {
				SVG.get('image-0').fire('click');
			}
			var id = seletedImage.node.getAttribute('id');
			var element = svgSetup.elements[ id.replace('image-', '') ];
			if( arguments.length == 0) {
				r = element.image.rotate;
				r = Math.round(r/90)*90+90;
				if(r >= 360) {
				    r = 0;
				}
			}
			rotate(r, element);
		},
		zoom: function(z) {
			if( seletedImage == undefined ) {
				SVG.get('image-0').fire('click');
			}
			var id = seletedImage.node.getAttribute('id');
			var element = svgSetup.elements[ id.replace('image-', '') ];
			if( arguments.length == 0) {
				z = element.image.zoom;
				z += 0.2;
				if(r > 3.6) {
				    return false;
				}
			}
			zoom(z, element);
		},
		zoomInAll: function() {
			zoomInAll();
		},
		zoomOutAll: function() {
			zoomOutAll();
		},
		resetZoom: function() {
			resetZoom();
		},
		filter: function(f) {
			
			if( seletedImage == undefined ) {
				if( !$('#image-0').length ) {
					return false;
				}				
			}
			SVG.get('image-0').fire('click');
			filter(f, seletedImage);
		},
		wrap: function(w) {
			wrap(w);
		},
		changeImage: function(obj) {
						
			var data = $(obj).data();
			$('#order_image_id').val(data.id);
			$('#order_image_name').val(data.name);
			$('#path_thumb').val(data.path_thumb);
			
			var src = data.link;
			last.image = src;

			if( seletedImage == undefined ) {				
				if( !$('#image-0').length ) {
					return false;
				}				
			}
			
			SVG.get('image-0').fire('click');
			
			if( src == null || src == undefined ) {
				src = $(obj).attr('src');		
			}
			var id = seletedImage.node.getAttribute('id');
//			console.log('id:'+id);
			var element = svgSetup.elements[ id.replace('image-', '') ];

			changeImage(src, element, data.id);
		},
		getDraw: function() {
			return draw;
		},
		draw: function() {
			drawSVG();
		},
		get: function() {
	    	return (new XMLSerializer()).serializeToString(document.getElementById("svg-main"));
		},
		setDefaultImage: function(src) {
			defaultImg = src;
			last.image = src;
		},
		setBleed: function(bleed) {
			svgSetup.main.realBleed = bleed;
		},
		setWrap: function(w) {
			svgSetup.main.wrap = w;
		}
	};
}();
</script>