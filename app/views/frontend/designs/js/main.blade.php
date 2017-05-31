<script type="text/javascript">
var Main = function (){
	var previewZoom = 1;
	var backgroundImage = '{{ $systemBackgrounds[0] or '' }}';
	var images = [];

	function bind()
	{
		$("#dialog").on( "dialogclose", function( ) {
		    $("#dialog #modal-search").hide();
		} );
		$('#fileup').filer({
			limit: 10
		});
		$('#bgfileup').filer({
			limit: 10
		});
		$('.jFiler-input').hide();

		$("#import_mpc").on('click',function(){
			$("#fileup").click();
		});
		$(".nicebt").on('click',function(){
			$("#bgfileup").click();
		});

		$(".dsbt").click(function(){
			var cont = $(this).attr('id');
			cont = cont.replace("dsbt_","content_");
			$(".ds_button").removeClass('ds_active');
			$(this).addClass('ds_active');
			$(".content_list").css("display","none");
			$("#"+cont).css("display","table");
		});

		$("#btnChooseColorFromImg").click(function(){
			ColorPicker.pick();
		});

		$('.paletteLabel').on('click',function(){
			$('.paletteContent').removeClass('active');
			$('.paletteLabel').removeClass('active');
			$(this).addClass('active');
			$("#"+$(this).attr('data-label-for')).addClass('active');
		});

		$("#getPicturesBtnLarge").on('click',function(){
			$("#dlg-container").show();
		});

		$("#dsbt_filter").on('click',function(){
			$('.paletteContent').removeClass('active');
			$('.paletteLabel').removeClass('active');
			$("#paletteContentFilters").addClass('active');
			$("#paletteLabelFilters").addClass('active');
		});

		$( "#slider-vertical" ).slider({
			orientation: "vertical",
			range: "max",
			step: 5,
			min: 0,
			max: 360,
			value: 0,
			slide: function( event, ui ) {
				$( "#amount" ).val( ui.value);
				Design.rotate(ui.value);
			}
		});
		$( "#amount" ).val( $( "#slider-vertical" ).slider( "value" ) );
		$( "#amount" ).change(function(){
			var val = $( "#amount" ).val();
			$( "#slider-vertical" ).slider('value', val);
			Design.rotate(val);
		});
		$("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: 1,
            slide: function( event, ui ) {
				Design.zoom(ui.value);
			}
        });
		$("#amount").keydown(function (e) {
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				(e.keyCode == 65 && e.ctrlKey === true) ||
				(e.keyCode >= 35 && e.keyCode <= 39)) {
					 return;
			}
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});

		$('#import_vi').click(function(){
			var page = $(this).data('page') || 1;
		    getImages(page);
		});
		$('#paletteLabelImages').click(function(){
			var page = $(this).data('page') || 1;
			$("#searchlib_text").val('');
		    getImages(page);
		});

		$('#searchByTag').submit(function(){
		    var keyword = $('#searchlib_text').val();
		    getImages(1, keyword);
		});

		$('#background-upload').change(function(){
			var data = new FormData();
			data.append('background', $(this)[0].files[0]);
			$.ajax({
				url: '{{ URL }}/design/put-background',
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				success: function(result){
					console.log('result: '+result);
					if( result.url ) {
						$('.paletteBgThumbnail.selected').removeClass('selected');
						var html = '<div class="background">';
						html += '<div class="backgroundCategory" onclick="Main.changeBackgound(this)" >' +
										'<div class="assetCategoryLabel"></div>' +
										'<img src="'+ result.url +'" class="paletteBgThumbnail" style="width:100%;height:auto;" />' +
									'</div>';
						html += '<div style="float:right;"><a onclick="Main.removeBackground(this)" class="glyphicon glyphicon-remove" title="Remove this item" style="text-decoration:none; font-size:70%; cursor:pointer;"></a></div>';
						html += '</div>';
						$('#user-background').append(html);
						$('#user-background .backgroundCategory:last img').load(function(){
							$(this).parent().trigger('click');
						});
					}
				}
			})
		});

		$('input[type=radio][name=depth]').change(function() {
			var value = $(this).val();
			var bleed = 0;
			switch(value) {
				case '05d':
					bleed = 0.5;
					break;
				case '1d':
					bleed = 1;
					break;
				case '15d':
					bleed = 1.5;
					break;
				case '2d':
					bleed = 2;
					break;
			}
			Design.setBleed(bleed);
			Design.resize();
			
			Order.caculatePrice();
		});

		$('input[type=radio][name=wrap_option]').change(function() {
			var wrap = $(this).val();
			Design.wrap(wrap);
			
			Order.caculatePrice();
		});



		$('#paletteContentProducts a').click(function() {
			$('#paletteContentProducts a.active').removeClass('active');
			$(this).addClass('active');
			var id = $(this).data('id');
			$('.option-tabs').hide();
			$('.option-tabs#tab-'+ id).show();

			var width = parseFloat($('#paletteContentSizes [name=width]').val());
			var height = parseFloat($('#paletteContentSizes [name=height]').val());

			Design.setBleed(0);

			$('.paletteContent input[type=radio][name!=filter_type]').prop('checked', false);
			$('.paletteContent input[type=radio][name!=filter_type]').prop('disabled', true);
			$('.option-tabs#tab-'+ id +' .paletteLabel').each(function(){
				var id = $(this).attr('id').replace('paletteLabel', '');
				$('#paletteContent'+ id +' input[type=radio]').prop('disabled', false);
				$('#paletteContent'+ id +' input[type=radio]:first').prop('checked', true);
			});
			var value = $('input[type=radio][name=depth]:checked').val();
			var bleed;
			switch(value) {
				case '05d':
					bleed = 0.5;
					break;
				case '1d':
					bleed = 1;
					break;
				case '15d':
					bleed = 1.5;
					break;
				case '2d':
					bleed = 2;
					break;
				default:
					bleed = 0;
					break;
			}
			Design.setBleed(bleed);

			var wrap = $('input[type=radio][name=wrap_option]:checked').val() || 'black';
			Design.setWrap(wrap);

			Design.resize(width, height);
			
			Order.caculatePrice();
		});

		$('#paletteContentSizes .sizes').change(function(){
			var width = parseFloat($('#paletteContentSizes [name=width]').val());
			var height = parseFloat($('#paletteContentSizes [name=height]').val());

			Design.resize(width, height);
			
			Order.caculatePrice();
		});

		$('#quantity').change(function(){
			
			Order.caculatePrice();
		});
		
		$('#addToCartLink').click(function( event ) {
			event.preventDefault();
			Order.addToCart();
		});
		

		$('#paletteContentProducts a:first').trigger('click');
	}

	function getImages(page, keyword)
	{
		var data = {};
	    if( keyword ) {
	        data['keyword'] = keyword;
	    }
	    if( page ) {
	        data['page'] = page;
	    }
		 $.ajax({
				url: "{{ URL.'/design/get-images' }}",
				type: 'POST',
				data: data,
				success: function(result) {
					var html = '';
					var data = result['data'];
					if( data.length ) {
					for(var i in data) {
						html += '<div class="large-2 columns block_album">' +
								  '<div class="block_image" id="block_image_'+ data[i].id +'" style="height:130px;width:auto;overflow:hidden;">' +
									  '<img class="cover_album" data-check="0" data-source="'+ data[i].link +'" src="'+ data[i].thumb +'" data-store="'+ data[i].store +'" data-id="'+ data[i].id +'" data-name="'+ data[i].name +'" data-path_thumb="'+ data[i].path_thumb +'" onclick="Main.choice('+ data[i].id +')" data-ext="'+ data[i].ext +'" />' +
									  '<div class="icon_close5" onclick="Main.removeChoice('+ data[i].id +')"  style="display:none;"></div>'+
								  '</div>' + 
								  '<div class="name"><a href="{{URL}}/pic-'+ data[i].id +'/'+ data[i].short_name +'.html" title="Go to '+ data[i].name +'">'+ data[i].name +'</a></div>' +
							  '</div>';
					}
				}
				$("#loading_import").hide();
				$(".of_album").hide();
				$("[text ='List Album']").hide();
				$("#loading_import").hide();
				
				//$("#dialog").dialog({width: 1200,height: 600,modal: true}).dialog("open");
				$("#modal-search").show();
				
				
	//			$("#list_image").css('max-height','500px').css('height','478px')
	//					  .html(html);
				$("#list_image").css('height','auto').css('max-height','390px')
						  .html(html);
						  
				//Paging
				$("input[name=page]").val(page);
				Order.createPage(result['total_image'], result['total_page']);

			}
			
			
			
		});
	}

	function getWH()
	{
		var tmpImage = new Image();
		tmpImage.src = backgroundImage;
		var width = tmpImage.width;
		var height = tmpImage.height;
		var ratio = width / height;
		if( width > 1000 ) {
			width = 1000;
			height = width / ratio;
		}
		if( height > 450 ) {
			height = 450;
			width = height * ratio;
		}
		return {'width': width, 'height': height};
	}

	function preview(callBack, afterRenderCallBack)
	{
		ColorPicker.close();
		Main.previewRenderFinished = false;
		$(".slider_bt").hide();
		previewZoom = 1;
		$('#editAreaWorkArea').css({
			'position': 'absolute',
			'opacity': 0,
			'z-index': '-1000'
		});
		$('#svg-main')[0].instance.addClass('preview');
		if( $('#svg-main .shape-path.active').length ) {
			$('#svg-main .shape-path.active')[0].instance.removeClass('active');
		}
		$('#zoom_bt').hide();
		$('#preview_box').show();
		$('#preview_box #loading-image').show();
		$('#preview_content').html('').hide();
		callBack();
		var timeProcess = 0;
		var interval = setInterval(function() {
			console.log('--Rendering-- '+ (timeProcess/1000).toFixed(2));
			timeProcess+= 200;
			if( timeProcess > 60000 ) {
				clearInterval(interval);
				return false;
			}
			if(!Main.previewRenderFinished) {
				return false;
			}
			if( typeof afterRenderCallBack == 'function' ) {
				afterRenderCallBack();
			} else {
				$('#zoom_bt2').show();
				$('#preview_content').show();
				$('#preview_box #loading-image').hide();
			}
			clearInterval(interval);
		}, 200);
	}
	return {
		previewRenderFinished: false,
		bind : function() {
			bind();
			this.findWrap();
		},
		findWrap: function() {
			var wrapName = $('#paletteContentOptions input[type=radio][name=frame_style]:checked').attr('title');
			if( typeof wrapName != undefined ) {
				this.changeWrapName(wrapName);
			}
		},
		changeWrapName: function(wrapName) {
			$('span#name_wrap').text(wrapName);
		},
		changeWrap: function(wrapKey, wrapName) {
			if( wrapName == 'Spot Colour' ) {
				$('.paletteContent').removeClass('active');
				$('#pick_color').addClass('active');
			} else {
				Design.wrap(wrapKey);
			}
			this.changeWrapName(wrapName);
		},
		filter: function(filterKey) {
			$('#paletteContentFilters input[type=radio][name=filter_type][value='+ filterKey +']').prop('checked', true);
		},
		rotate: function(rotate) {
			$('#amount').val(rotate);
			$( "#slider-vertical" ).slider('value', rotate);
		},
		zoom: function(zoom) {
			$('#zoom-slider').slider('value', zoom);
		},
		showSlider: function(show) {
			if( show == false ) {
				$('.slider_bt').hide();
			} else {
				$('.slider_bt').show();
			}
		},
		zoomAll: function(zoom) {
			if( zoom != 1 ) {
				this.showResetZoom();
				$('#reset_zoom').show();
			} else {
				this.showResetZoom(false);
			}
			if( $('#pick_color').is(':visible') ) {
				ColorPicker.pick();
			}
		},
		showResetZoom: function(show) {
			if( show == false ) {
				$('#reset_zoom').hide();
			} else {
				$('#reset_zoom').show();
			}
		},
		preview: function(show) {
			if( show === false ) {
				return this.closePreview();
			}

			preview(function(){
				if( $('#svg-main.preview.preview-bg').length ) {
					$('#svg-main')[0].instance.removeClass('preview')
												.removeClass('preview-bg');
					$('#svg-main.preview.preview-bg .shape-path').each(function(){
						this.instance.unfilter(true);
					});
				}
				$('#paletteLabelBackgrounds, #zoom_bt2').hide();
				$('#paletteLabelArrangements').trigger('click');
				$('#preview_content').mouseover(function(){
					return false;
				});
				var svgSetup = Design.svgSetup();
				var previewAttribute = {
					'id': 'svg-preview',
					'width': svgSetup.main.width,
					'height': svgSetup.main.height,
					'viewBox': '0 0 '+ svgSetup.main.width +' '+ svgSetup.main.height
				};
				var useAttribute = {
					'id': 'use-preview',
					'x': 0,
					y: 0
				};
				Design.resetZoom();
				SVG('preview_content')
					.attr(previewAttribute)
					.use( SVG.get('svg-main') )
					.attr(useAttribute);
				Main.previewRenderFinished = true;
			});
		},
		previewBG: function() {
			preview(function(){
				$('#paletteLabelBackgrounds').show().trigger('click');
								
				$('#preview_content').unbind('mouseover');
				$('#svg-main')[0].instance.addClass('preview')
											.addClass('preview-bg');
				var size = getWH();
				var svgSetup = Design.svgSetup();
				Design.resetZoom();
				var previewDraw = SVG('preview_content').attr({'id': 'svg-preview', 'width': 1000, 'height': 450});

				$('#svg-main .shape-path').each(function(){
					var defs = SVG.get('main-defs');
					var id = $(this).attr('id').replace('shape-path-', '');
					var shapePath = this;
					if( !$('#shape-clip-'+ id).length ) {
						defs.add( previewDraw.clip()
											.add(
												shapePath.instance.clone()
																	.removeClass('shape-path')
																	.attr('fill', null)
											).attr('id', 'shape-clip-'+ id)
								)
					}
					SVG.get('group-image-'+ id)
						.attr('clip-path', 'url("#shape-clip-'+ id +'")');
				});
				var image = previewDraw.image(backgroundImage)
										.attr({
											'width': size.width,
											'height': size.height,
											'x': 0,
											'y': 0,
											'id': 'preview-background-image'
										})
										.loaded(function(){
											this.draggable();
										});
				previewDraw.add(image)
				var nested = previewDraw.nested()
										.attr({
											'id': 'svg-use',
											'width': svgSetup.main.width/3,
											'height': svgSetup.main.height/3,
											'viewBox': '0 0 '+ svgSetup.main.width +' '+ svgSetup.main.height,
											'x': size.width - svgSetup.main.width/1.3,
											'y': (size.height - svgSetup.main.height/3)/2,
										});
				nested.draggable({
					minX: 0,
					maxX: size.width,
					minY: 0,
					maxY: size.height
				});
				nested.add(
						nested.use( SVG.get('svg-main') )
						.on('click', function() {
							return false;
						})
						.filter(function(add) {
						  	var blur = add.offset(10, 10).in(add.sourceAlpha).gaussianBlur(5)
  							add.blend(add.source, blur)
						})
					);
				Main.previewRenderFinished = true;
			});
			
			//$('.paletteBgThumbnail.selected').removeClass('selected');
			//$('#paletteContentBackgrounds .backgroundCategory:first img').addClass('selected');
			$('#paletteContentBackgrounds .backgroundCategory:first').click();
			
		},
		closePreview: function() {
			$('#paletteLabelBackgrounds, #zoom_bt2').hide();
			$('#paletteLabelArrangements').trigger('click');
			if( $('#svg-main.preview-bg').length ) {
				$('#svg-main')[0].instance.removeClass('preview-bg');
				$('#svg-main.preview.preview-bg .shape-path').each(function(){
					this.instance.unfilter(true);
				});
			}
			if( $('#svg-main.preview').length ) {
				$('#svg-main')[0].instance.removeClass('preview');
			}
			$('#svg-main .main-image').each(function(){
				var id = $(this).attr('id').replace('image-', '');
				SVG.get('group-image-'+ id).attr('clip-path', 'url("#clip-'+ id +'")');
			});
			$('#editAreaWorkArea').css({
				'position': 'relative',
				'opacity': 1,
				'z-index': 0
			});
			$('#preview_box').hide();
			$('#zoom_bt').show();
			
			$('#paletteLabelProducts').click();
		},
		zoomInPreview: function() {
			this.zoomPreview(previewZoom+0.2);
		},
		zoomOutPreview: function() {
			this.zoomPreview(previewZoom-0.2);
		},
		zoomPreview: function(zoom) {
			if( zoom == undefined
				|| zoom < 0.5
				|| zoom > 3.6 ) {
				return false;
			}
			previewZoom = zoom;
			if( $('#svg-preview #preview-background-image').length ) {
				SVG.get('svg-preview')
					.attr({
						'width': 1000*zoom,
						'height': 450*zoom,
						'viewBox': '0 0 1000 450'
					});
			} else {
				var svgSetup = Design.svgSetup();
				var width = svgSetup.main.width;
				var height = svgSetup.main.height;
				SVG.get('svg-preview')
					.attr({
						'width': width*zoom,
						'height': height*zoom,
						'viewBox': '0 0 '+ width +' '+ height
					});
			}
		},
		changeBackgound: function(object) {
			//$('.backgroundCategory.active').removeClass('active');
			//$(object).addClass('active');
			$('.paletteBgThumbnail.selected').removeClass('selected');
			$('img', object).addClass('selected');
			
			backgroundImage = $('img', object).attr('src');
			var size = getWH();
			SVG.get('preview-background-image').attr({href: backgroundImage, x: 0, y: 0, 'width': size.width, 'height': size.height});
			var svgUse = SVG.get('svg-use');
			svgUse.attr({
				x: size.width - svgUse.width()/0.4,
				y: (size.height - svgUse.height())/2
			}).draggable({
				minX: 0,
				maxX: size.width,
				minY: 0,
				maxY: size.height
			});
		},
		removeBackground: function(object) {

			$('#div-waiting-remove').html('<img src="{{URL}}/assets/images/others/ajax-loader-1.gif" width="100%">');  
			var p_obj = $(object).parent().parent();
			background_remove = $('img', p_obj).attr('src');
			$.ajax({
				url: '{{ URL }}/design/remove-background',
				type: 'GET',
				data: {bg: background_remove},
				success: function(result){
					
					if(p_obj.find('.paletteBgThumbnail').hasClass('selected'))
					{
						$('#paletteContentBackgrounds .backgroundCategory:first').trigger('click');
					}
					p_obj.remove();
					$('#div-waiting-remove').html('');				
				}
			})	
			
		},
		choice: function(id) {
		    $("#block_image_"+ id +" .icon_close5").show();
		    $("#block_image_"+ id +" .cover_album").addClass("choice_image")
		    										.attr("data-check",1);

		},
		removeChoice: function(id) {
		    $("#block_image_"+ id +" .icon_close5").hide();
		    $("#block_image_"+ id +" .cover_album").removeClass("choice_image")
		    										.attr("data-check",0);
		},
		chooseImages: function() {
		    var html;
		    var d = new Date();
		    var arrImgs = [];
		    $.each($("[data-check=1]"),function( key, value ) {
		        var link = $( this ).attr("data-source");
		        var data = $(this).data();
		        if( data.store == 'google-drive' ) {
		            var ext = data.ext;
		            $.ajax({
		                url: '{{URL}}/socials/get-image',
		                type: 'POST',
		                async:false,
		                data:{
		                    link:link,
		                    ext: ext,
		                    data: data
		                },
		                async:false,
		                success: function(result){
		                    if(result.error==0){
		                        link = result.data;
		                    }else{
		                        link = false;
		                    }
		                }
		            })
		        }
		        if( !link ) {
		            return;
		        }
		        html = '<div class="image_content" id="img_upload_vi'+d.getTime()+'">'+
		               		"<img class=\"photo\" src=\""+link+"\" alt=\"\" data-link=\""+link+"\" data-id=\""+data.id+"\" data-name=\""+data.name+"\" data-path_thumb=\""+data.path_thumb+"\" onclick=\"Design.changeImage(this);\">"+
		               		'<div class="name"><a href="{{URL}}/pic-'+ data.id +'/'+ data.short_name +'.html" title="Go to '+ data.name +'">'+ data.name +'</a></div>'
					   +'</div>';
		        $(html).prependTo("#slider_image");
		        //images.push(link);
				images.push(data.id);
		    });
		    //save session
		    $.ajax({
		        url:"{{ URL }}/design/put-image-store",
		        type:"POST",
		        data:{ 'images': images },
		        success: function(){
		        }
		    });
		    //$("#dialog" ).dialog({width: 1200}).dialog("close");
			$('.modal-header button[class="close"]').click();
		},
		getImages: function(page, keyword) {
			getImages(page, keyword);
		},		
		preview3D: function(afterRenderCallBack) {
			Design.resetZoom();
			this.closePreview();
			preview(function(){
				var info = {};
				var svgSetup = Design.svgSetup();
				var opacity = false;
				var draw = Design.getDraw();
				var arrColor = ['red', 'green', 'yellow', 'gray', 'organe', 'blue'];
				$('#svg-main .shape-path').each(function(){
					var minX = null;
					var minY = null;
					var id = $(this).attr('id').replace('shape-path-', '');
					var pathArray = this.instance.array.value;
					var position = 0;
					for(var i in pathArray) {
						if( info[id+'.'+position] == undefined ) {
							info[id+'.'+position] = {
								'center': {
									'points': []
								}
							};
						}
						var array = pathArray[i];
						if( array.length != 3 ) {
							position++;
							var minX = null;
							var minY = null;
							continue;
						}
						var x = Number(array[1]);
						var y = Number(array[2]);
						if( minX == null || minX > x ) {
							minX = x;
						}
						if( minY == null || minY > y) {
							minY = y;
						}
						info[id+'.'+position].center.points.push({ x: x, y: y });
						info[id+'.'+position].center.minX = minX;
						info[id+'.'+position].center.minY = minY;
					}
					var bleedArray = SVG.get('bleed-'+ id).array.value;
					var j = 0;
					var position = 0;
					var minX = null;
					var minY = null;
					for(var i in bleedArray) {
						var array = bleedArray[i];
						if( info[id+'.'+position]['bleed_'+ j] == undefined ) {
							info[id+'.'+position]['bleed_'+ j] = {
								'points' : [],
								'angle': ''
							};
						}
						if( array.length != 3 ) {
							var point = svgSetup.elements[id].allPoints.points;
							var current = j;
							var next = current + 1;
							if( next > point['path_'+ position].length - 1 ) {
								next = next - point['path_'+ position].length;
							}
							prevPoint = {x: point['path_'+ position][current].x, y: point['path_'+ position][next].y};
							info[id+'.'+position]['bleed_'+ j].angle = -Pointer.angle(prevPoint, point['path_'+ position][current], point['path_'+ position][next]);
							/*draw.path(
								'M'+prevPoint.x+' '+prevPoint.y+
								'L'+point['path_'+ position][current].x+' '+point['path_'+ position][current].y+
								'L'+point['path_'+ position][next].x+' '+point['path_'+ position][next].y
								).attr({'stroke': arrColor[j], 'fill': 'none'});*/
							j++;
							if( j == point['path_'+ position].length ) {
								var minX = null;
								var minY = null;
								j = 0;
								position++;
							}
							continue;
						}
						var x = Number(array[1]);
						var y = Number(array[2]);
						if( minX == null || minX > x ) {
							minX = x;
						}
						if( minY == null || minY > y) {
							minY = y;
						}
						info[id+'.'+position]['bleed_'+ j].points.push({ x: x, y: y });
						info[id+'.'+position]['bleed_'+ j].minX = minX;
						info[id+'.'+position]['bleed_'+ j].minY = minY;
						var bleedPath = SVG.get('bleed-'+ id);
						if( bleedPath.attr('fill-opacity') == 0.4 ) {
							opacity = true;
							bleedPath.attr('fill-opacity', 0);
						}
					}
				});
				canvg('main-canvas', Design.get(), {
					renderCallback: function(){
						var draw = Design.getDraw();
						var mainCanvas = document.getElementById('main-canvas');
						var canvasCollection = $('#canvas-collection');
						canvasCollection.html('');
						var OBJECT = {
										'width' 	 : svgSetup.main.width,
										'height' 	 : svgSetup.main.height,
										'bleed' 	 : svgSetup.main.bleed,
										'imageTotal' : 0,
										'shapes'	 : {}
									};
						var imageWrap = $.inArray(svgSetup.main.wrap, ['natural', 'm_wrap']) != -1 ? true : false;

						if( !imageWrap ) {
							var color;
							if( svgSetup.main.wrap == 'white' ) {
								color = '#ffffff';
							} else if( svgSetup.main.wrap == 'black' ) {
								color = '#000000';
							} else if(  svgSetup.main.wrap.indexOf('#') !== -1 ) {
								color = svgSetup.main.wrap;
							} else {
								color = '#ffffff';
							}
							OBJECT.color = color;
						}
						for(var shapePosition in info) {
							var shapeInfo = info[ shapePosition ];
							for(var shapeName in shapeInfo) {
								var shape = shapeInfo[ shapeName ];
								if( OBJECT.shapes[shapePosition] == undefined ) {
									OBJECT.shapes[shapePosition] = {};
								}
								if( OBJECT.shapes[shapePosition][shapeName] == undefined ) {
									OBJECT.shapes[shapePosition][shapeName] = {};
								}
								OBJECT.shapes[shapePosition][shapeName].points = shape.points;
								var points = shape.points;
								if( shapeName != 'center' ) {
									continue;
								} else {
									var d = '';
									for( var p in points ) {
										if( p == 0 ) {
											d += 'M'+ points[p].x +' '+points[p].y;
										} else {
											d += 'L'+ points[p].x +' '+points[p].y;
										}
									}
									var path = draw.path(d +'Z');
									var minX = shape.minX;
									var minY = shape.minY;
									var w = path.width();
									var h = path.height();
									path.remove();
									canvasCollection.append('<canvas id="canvas-'+ shapePosition +'-'+ shapeName +'" width="'+ w +'" height="'+ h +'"></canvas>');

									var canvas = document.getElementById('canvas-'+ shapePosition +'-'+ shapeName);
									var ctx = canvas.getContext("2d");
									ctx.globalAlpha = 1.00;
								    ctx.drawImage(mainCanvas, minX, minY, w, h, 0, 0, w, h);
								    ctx.restore();
									OBJECT.shapes[shapePosition][shapeName].image = 'canvas-'+ shapePosition +'-'+ shapeName;
								}
							}
						}
						Preview3D.draw(OBJECT);
						Main.previewRenderFinished = true;
						if( opacity ) {
							$('#svg-main .group-bleed .bleed').each(function(){
								this.instance.attr('fill-opacity', 0.4);
							});
						}
					}
				});
				return false;
			}, afterRenderCallBack);
		}
	}
}();
</script>