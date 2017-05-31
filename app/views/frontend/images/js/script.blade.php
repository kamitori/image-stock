<script type="text/javascript">
var factor = 4;
var border_width = 15;
var border_width_preview = 5;
var border_image = 3;
var border_image_preview = 1;
var main_image_preview_width = 20;
var main_image_preview_left = 15;
var main_image_preview_top = 40;
var padding_mat = 10;
var padding_mat_preview = 10;

function checkSize(w, h)
{
	var d = w;
	if(h > w)
	{
		d = h;	
	}
	var size = 'small';
	if(d >= 15) size = 'medium';
	if(d >= 30) size = 'large';
	if(d > 40) size = 'extra-large';
	return size;
}

function setOrderType(order_type, index, preview)
{	
	//display image
	var img_class = 'framed';
	switch(index) {
		case 1:
			img_class = 'photo';
			break;
		case 2:
			img_class = 'canvas';
			break;
		default:
			img_class = 'framed';
	}	

	if(preview == null)
	{
		preview = '';
	}
	else
	{
		$('#main-image-preview').removeAttr( 'style' );			
	}

	//$('#main-image-cover').removeClass('framed');		
	$('#main-image-cover'+preview).removeAttr( 'style' );	
	$('#main-image-mat'+preview).removeAttr( 'style' );		
	$('#main-image-display'+preview).removeAttr( 'style' );		
	$('#main-image-cover'+preview).attr("class", img_class);	
	$('#main-image-display'+preview).attr("class", img_class);
	if(img_class == 'framed')
	{
		$('#main-image-mat'+preview).attr("class", 'mat');	
	}
	
	var html, sku;
	
	var arr_order_type = order_type.split("_");
	sku = arr_order_type[1];

	html = $('#div-choose-order'+preview+'-'+sku).html();
	$('#div-choose-order'+preview).html(html);
	
	//load list of main image sizes
	appendSizes(preview);
	
	var order_form = document.getElementById("order-form"+preview);
	var img_sizing = order_form.elements.namedItem("img_sizing");	
	var option_orientation = $('#order-form'+preview+' select[name=option_orientation] option');
		
	if(img_sizing != null && img_sizing.value == '')
	{		
		//set orientation select box
		option_orientation.each(function()
		{
			if($(this).val() == 'vertical')
			{
				$(this).attr('selected', true);
			}
		});		
		changeSize(sku, preview);
	}
	else
	{		
		var dpi = $('#image_dpi').val();
		var sizes = img_sizing.value.split("|");
		sizew = sizes[0];
		sizeh = sizes[1];
		//var height = sizeh*dpi/factor;			
		if(sizeh >= sizew)
		{
			//set orientation select box
			option_orientation.each(function()
			{
				if($(this).val() == 'vertical')
				{
					$(this).attr('selected', true);
				}
			});
			//display image						
			//$('#main-image-display').css('width', '100%');
			//$('#main-image-display').css('height', height+'px');			
		}
		else
		{
			//set orientation select box
			option_orientation.each(function()
			{
				if($(this).val() == 'horizontal')
				{
					$(this).attr('selected', true);
				}
			});
			//display image			
			//$('#main-image-display').removeAttr( 'style' );			
			//$('#main-image-display').css('width', '100%');
			//$('#main-image-display').css('height', 'auto');							
		}
	}
	
	//set edge select box
	var option_edge = $('#order-form'+preview+' select[name=option_edge] option');
	option_edge.each(function()
	{
		if($(this).val() == '2.5mat')
		{
			$(this).attr('selected', true);
		}
	});		
	
	caculatePrice(preview);	
}

function changeOption(option_obj, group_key, sku, preview)
{
	if(preview == null)
	{
		preview = '';
	}
	
	var order_form = document.getElementById("order-form"+preview);	
	var img_sizing = order_form.elements.namedItem("img_sizing");		
	var sizew, sizeh;
	var option_key = option_obj.value;
	if(group_key == 'depth')
	{
		var option_name = option_obj.options[option_obj.selectedIndex].text;
		var bleed = parseFloat(option_name);
		var default_bleed = parseFloat(option_obj.options[0].text);		
		var img_border_width = border_image;
		if(preview != '')
		{
			img_border_width = border_image_preview;
		}		
		var new_img_border_width = bleed / default_bleed * img_border_width;
		$('#main-image-display'+preview).css('border-right-width', new_img_border_width+'px');
		$('#main-image-display'+preview).css('border-bottom-width', new_img_border_width+'px');
	}
	else if(group_key == 'edge')
	{
		var edge = option_obj.value;
		if(edge == '0mat')
		{			
			$('#main-image-mat'+preview).removeClass('mat');
			$('#main-image-mat'+preview).removeAttr( 'style' );
		}
		else
		{
			$('#main-image-mat'+preview).attr("class", 'mat');

			if(img_sizing != null && img_sizing.value != '')
			{
				var sizes = img_sizing.value.split("|");
				sizew = sizes[0];
				sizeh = sizes[1];
			}
			else
			{
				sizew = order_form.elements.namedItem("img_width").value;
				sizeh = order_form.elements.namedItem("img_height").value;
				if(sizew == '' || sizew <= 0 || sizeh == '' || sizeh <= 0)	
				{
					return;	
				}				
			}
						
			var default_sizing = $('#order-form'+preview+' select[name=img_sizing] option:eq(0)');
			var default_sizes = default_sizing.val().split("|");
			//console.log(default_sizes);
			var default_sizew = default_sizes[0];
			var default_sizeh = default_sizes[1];
			
			var img_padding_mat = padding_mat;
			if(preview != '')
			{
				img_padding_mat = padding_mat_preview;
			}		
			
			var new_img_padding_mat = default_sizew / sizew * img_padding_mat;
			$('#main-image-mat'+preview).css('padding', new_img_padding_mat+'%');									
		}		
	}
	else if(group_key == 'orientation')
	{
				
		if(img_sizing != null && img_sizing.value != '')
		{
			var dpi = $('#image_dpi').val();
			var sizes = img_sizing.options[0].value.split("|");
			sizew = sizes[0];
			sizeh = sizes[1];
			var height = sizeh*dpi/factor;
			if(option_key == 'horizontal')
			{				
				//$('#main-image-display').removeAttr( 'style' );
				//$('#main-image-display').css('width', '100%');
				//$('#main-image-display').css('height', 'auto');				
				//$('#main-image-display').css('height', 'auto');			
			}
			else
			{
				//$('#main-image-display').css('width', 'auto');
				//$('#main-image-display').css('height', height+'px');			
			}					
		}		
	}
	else if(group_key == 'wrap_option')
	{
		var arr_color = new Array();
		arr_color['black'] = '#000';
		//arr_color['natural'] = '#663300';
		//arr_color['m_wrap'] = '#cc9900';
		//arr_color['red'] = '#cc6600';
		arr_color['natural'] = '#E5E5E5';
		arr_color['m_wrap'] = '#E5E5E5';
		arr_color['red'] = '#E5E5E5';
		arr_color['white'] = '#fff';

		if($('#main-image-display'+preview).hasClass("framed"))
		{
			if(arr_color[option_key] != null)
			{
				$('#main-image-cover'+preview).css('border-color', arr_color[option_key]);	
			}			
		}		
	}

	caculatePrice(preview);
}

function customSize(size_type, sku, preview)
{
	if(preview == null)
	{
		preview = '';
	}	
	
	var order_form = document.getElementById("order-form"+preview);	
	var default_sizing = $('#order-form'+preview+' select[name=img_sizing] option:eq(0)');
	var default_sizes = default_sizing.val().split("|");
	var default_sizew = default_sizes[0];
	var default_sizeh = default_sizes[1];
		
	if(size_type == 'w')
	{
		var w = order_form.elements.namedItem("img_width").value;
		if(w != '' && w > 0)
		{
			var h = Math.round(w / default_sizew * default_sizeh);			
			order_form.elements.namedItem("img_height").value = h;
			changeSize(sku, preview);	
		}
	}
	else
	{
		var h = order_form.elements.namedItem("img_height").value;
		if(h != '' && h > 0)
		{
			var w = Math.round(h / default_sizeh * default_sizew);			
			order_form.elements.namedItem("img_width").value = w;
			changeSize(sku, preview);	
		}		
	}
}
function changeSize(sku, preview)
{
	if(preview == null)
	{
		preview = '';
	}
	var order_form = document.getElementById("order-form"+preview);	
	var sizew, sizeh;
	var img_sizing = order_form.elements.namedItem("img_sizing").value;	
	if(img_sizing == '')
	{
//		$('input[name=img_width]').val('');
//		$('input[name=img_height]').val('');
		
		$('#div-custom-sizing'+preview+'-'+sku).show();		
		sizew = order_form.elements.namedItem("img_width").value;
		sizeh = order_form.elements.namedItem("img_height").value;
		if(sizew == '' || sizew <= 0 || sizeh == '' || sizeh <= 0)	
		{
			$('#price'+preview).val(0);
			$('#sell_price'+preview).val(0);
			$('#display_price'+preview).number(0, 2);			
			return;	
		}
	}
	else
	{		
		$('#div-custom-sizing'+preview+'-'+sku).hide();
		var sizes = img_sizing.split("|");
		sizew = sizes[0];
		sizeh = sizes[1];
	}
	
	//Display Image
	var default_sizing = $('#order-form'+preview+' select[name=img_sizing] option:eq(0)');
	var default_sizes = default_sizing.val().split("|");
	//console.log(default_sizes);
	var default_sizew = default_sizes[0];
	var default_sizeh = default_sizes[1];

	if($('#main-image-display'+preview).hasClass("framed"))
	{
		var order_form = document.getElementById("order-form"+preview);	
		var edge = order_form.elements.namedItem("option_edge");	
		if(edge != null)
		{
			if(edge.value == '2.5mat')
			{
				var img_padding_mat = padding_mat;
				if(preview != '')
				{
					img_padding_mat = padding_mat_preview;
				}		
				var new_img_padding_mat = default_sizew / sizew * img_padding_mat;
				$('#main-image-mat'+preview).css('padding', new_img_padding_mat+'%');					
			}	
		}
		
		var img_border_width = border_width;
		if(preview != '')
		{
			img_border_width = border_width_preview;
		}
		var new_img_border_width = default_sizew / sizew * img_border_width;
		$('#main-image-cover'+preview).css('border-width', new_img_border_width+'px');				
	}
	else
	{
		//for photo & canvas
		var img_padding = 5;
		var new_img_padding = default_sizew / sizew * img_padding;
		$('#main-image-cover'+preview).css('padding', new_img_padding+'%');			
	}			

	if(preview != '')
	{
//		if(sizeh > 63)
//		{
//			sizeh = 63;
//			sizew = sizeh / default_sizeh * default_sizew;		
//		}

		var new_img_width = sizew / default_sizew * main_image_preview_width;
		//if(new_img_width > 95) new_img_width = 95;
		var new_img_left = default_sizew / sizew * main_image_preview_left;
		var new_img_top = default_sizew / sizew * main_image_preview_top;
		$('#main-image-preview').css('width', new_img_width+'%');			
		$('#main-image-preview').css('margin-left', new_img_left+'%');			
		$('#main-image-preview').css('margin-top', new_img_top+'%');						
	}
		
	caculatePrice(preview);
	
}

function changeQuantity(order_qty, preview)
{
	if(preview == null)
	{
		preview = '';
	}		
	var order_form = document.getElementById("order-form"+preview);	
	var old_qty = order_form.elements.namedItem("old_qty").value;		
	if(order_qty == old_qty)
	{
		return;	
	}
	order_form.elements.namedItem("old_qty").value = order_qty;
	caculatePrice(preview);
}

function caculatePrice(preview)
{
	if(preview == null)
	{
		preview = '';
	}			
	var sum = 0;
	var sizew, sizeh = 0;
	var depth, wrap_option, edge_color, orientation, frame_colour, border, edge;

	var order_type = $('#order-form'+preview+' input[name="order_type"]:checked').val();
	
	var arr_order_type = order_type.split("_");
	var sku = arr_order_type[1];

	var order_form = document.getElementById("order-form"+preview);
	
	var img_sizing = order_form.elements.namedItem("img_sizing");
	if(img_sizing != null && img_sizing.value != '')
	{
		var size = img_sizing.value;
		var sizes = size.split("|");
		sizew = sizes[0];
		sizeh = sizes[1];
	}
	else
	{
		if(order_form.elements.namedItem("img_width") != null && order_form.elements.namedItem("img_height") != null)
		{
			sizew = order_form.elements.namedItem("img_width").value;
			sizeh = order_form.elements.namedItem("img_height").value;			
			
			if(sizew == '' || sizew <= 0 || sizeh == '' || sizeh <= 0 )
			{
				$('#price'+preview).val(0);
				$('#display_price'+preview).number(0, 2);				
				return;	
			}			
		}
	}
	
	if(order_form.elements.namedItem("option_depth") != null)
	{
		depth = order_form.elements.namedItem("option_depth").value;
	}
	if(order_form.elements.namedItem("option_wrap_option") != null)
	{
		wrap_option = order_form.elements.namedItem("option_wrap_option").value;
	}
	if(order_form.elements.namedItem("option_orientation") != null)
	{
		orientation = order_form.elements.namedItem("option_orientation").value;
	}
	if(order_form.elements.namedItem("option_frame_colour") != null)
	{
		frame_colour = order_form.elements.namedItem("option_frame_colour").value;
	}
	if(order_form.elements.namedItem("option_border") != null)
	{
		border = order_form.elements.namedItem("option_border").value;
	}
	if(order_form.elements.namedItem("option_edge_color") != null)
	{
		edge_color = order_form.elements.namedItem("option_edge_color").value;
	}
	if(order_form.elements.namedItem("option_edge") != null)
	{
		edge = order_form.elements.namedItem("option_edge").value;
	}
	
	var order_qty = order_form.elements.namedItem("order_qty").value;			

	$('#sell_price'+preview).val(0);
	$('#price'+preview).val(0);
	
	$('#display_price'+preview).html('<img src="{{URL}}/assets/images/others/ajax-loader.gif" width="20px">');
	$.post("/order/calculate-price",
	{
		sku: sku,
		sizew: sizew,
		sizeh: sizeh,
		depth: depth,
		wrap_option: wrap_option,
		orientation: orientation,
		frame_colour: frame_colour,
		border: border,
		edge_color: edge_color,
		edge: edge,
		order_qty: order_qty
	},
	function(data, status){
		if(data['status'] == 'ok')
		{
			sum = data['data']['amount'];
			var sell_price = data['data']['sell_price'];
			$('#sell_price'+preview).val(sell_price);
			$('#price'+preview).val(sum);
			$('#display_price'+preview).number(sum, 2);			
		}
	});				
}

function appendSizes(preview)
{
	if(preview == null)
	{
		preview = '';
	}				
	var width = $('#image_width').val();
	var height = $('#image_height').val();
	var dpi = $('#image_dpi').val();

	var img_sizing = $('#order-form'+preview+' select[name=img_sizing]');
	img_sizing.empty();

	if(dpi > 0)
	{
		var arrSizes = arrangeSize(width, height, dpi);
		if(arrSizes.length > 0)
		{
			for(var i=0; i<arrSizes.length; i++)
			{
				img_sizing.append('<option value="'+arrSizes[i].value+'" >'+arrSizes[i].text+'</option>');
			}		
		}		
	}
	
	var custom_size = $('#order-form'+preview+' input[name=custom_size]').val();
	if(custom_size)
	{
		img_sizing.append('<option value="">Custom...</option>');
	}
}

function arrangeSize(width, height, dpi)
{
	var w_in = Math.round(width/dpi*factor);
	var h_in = Math.round(height/dpi*factor);
	var w, h, r, d, old_w, old_h;
	var s = 'small';
	var arrSize = new Array();
	var j=0;
	var obj, obj1;
	//console.log('w_in: '+w_in+', h_in: '+h_in);
	for(var i=2; i<=w_in; i+=1)
	{
		obj = {};
		obj1 = {};
		w = i;
		r = w/w_in;			
		h = Math.round(h_in*r);		
		size = checkSize(w, h);
		//console.log('w: '+w+', h: '+h+', size: '+size);			
		
		if(size != s)
		{
			old_w = i-1;
			r = old_w/w_in;			
			old_h = Math.round(h_in*r);		
			obj.value = old_w+'|'+old_h;
			obj.text = old_w+' x '+old_h+' - '+checkSize(old_w, old_h);			
			arrSize[j] = obj;
			j++;
			s = size;			
		}
		
		obj1.value = w+'|'+h;
		obj1.text = w+' x '+h+' - '+size;
		//console.log('obj1'+obj1);											
	}

	if(arrSize.length == 0)
	{
		if(i > 1)
		{
			arrSize[0] = obj1;	
		}		
	}
	else
	{
		arrSize[j] = obj1;
	}
	//console.log(arrSize);
	return arrSize;
}

$('#background-upload').change(function(){
	
	if(!$("#modal-image-on-wall #background-effect").is(":visible"))
	{
		slideEffect(); 
	}
	
	$('#modal-title').html('<img src="{{URL}}/assets/images/others/ajax-loader.gif" width="20px">');     
	
	var data = new FormData();
	data.append('background', $(this)[0].files[0]);
	$.ajax({
		url: '{{ URL }}/design/put-background',
		type: 'POST',
		data: data,
		processData: false,
		contentType: false,
		success: function(result){
			//console.log('result: '+result);
			if( result.url ) {
				$('#user-background .backgroundCategory.active').removeClass('active');
				var html = '<div style="margin-bottom:15px;">';
				html += '<div class="backgroundCategory" onclick="changeBackgound(this)">' + '<img src="'+ result.url +'" class="paletteBgThumbnail" />' + '</div>';
				html += '<div style="float:right;"><a onclick="removeBackground(this)" class="glyphicon glyphicon-remove" title="Remove this item" style="text-decoration:none; font-size:70%;"></a></div>';
				html += '</div>';
				
				$('#user-background').append(html);
				//$('#modal-image-on-wall div.modal-content').css('background-image', 'url("'+result.url+'")');	
				$('#user-background .backgroundCategory:last img').load(function(){
					$(this).parent().trigger('click');
				});
				
			}
			var upload_btn = '<a title="Change background" onclick="$(\'#background-upload\').click();"><i class="fa fa-upload" style="font-size:24px;"></i></a>';
			$('#modal-title').html(upload_btn);
		}
	})
});

function changeBackgound(object)
{
	$('#user-background .backgroundCategory.active').removeClass('active');
	$(object).addClass('active');
	backgroundImage = $('img', object).attr('src');
	$('#modal-image-on-wall div.modal-content').css('background-image', 'url("'+backgroundImage+'")');
	//$('#modal-image-on-wall div.modal-content').css('background-repeat', 'repeat');
}

function removeBackground(object)
{	
	$('#div-waiting-remove').html('<img src="{{URL}}/assets/images/others/ajax-loader-1.gif" width="100%">');  
	var p_obj = $(object).parent().parent();
	background_remove = $('img', p_obj).attr('src');
	$.ajax({
		url: '{{ URL }}/design/remove-background',
		type: 'GET',
		data: {bg: background_remove},
		success: function(result){
			
			if(p_obj.find('.backgroundCategory').hasClass('active'))
			{
				$('#user-background .backgroundCategory:first').trigger('click');
			}
			p_obj.remove();
			$('#div-waiting-remove').html('');				
		}
	})	
}

function slideEffect()
{
	$( "#modal-image-on-wall #background-effect" ).toggle( 'slide', {}, 200 );
}

function addLightBox(obj){
	var id_lightbox = $(obj).data('id-lightbox');
	var id_image = $("#image_id").val();
	$.ajax({
		url:'{{URL}}/lightbox/add/'+id_image+'/'+id_lightbox,
		type:'GET',
		success:function(data){
			if(data.result = 'success'){
				$(obj).parent().parent().html('<div class="popover_lightbox">'+'Saved to '+$(obj).text()+'</div>');
			}else{
				$(obj).parent().parent().html('<div class="popover_lightbox">Save error</div>');
			}
		}
	})
}
function saveLightBox(obj){
	var name_lightbox = $(obj).prev().val();
	var id_image = $("#image_id").val();
	$.ajax({
		url:'{{URL}}/lightbox/add-by-name/'+id_image+'/'+name_lightbox,
		type:'GET',
		success:function(data){
			if(data.result == 'success'){
				$(obj).parent().parent().html('<div class="popover_lightbox">'+'Saved to '+name_lightbox+'</div>');
				$.ajax({
					url:'{{URL}}/lightbox/get-lightbox-user',
					type:'GET',
					success:function(data){
						if(data.result=='success'){
							html='';
							$.each(data.lightboxes,function(key,value){
								html+='<p data-id-lightbox="'+value['id']+'" class="btn btn-default " onclick="addLightBox(this)" style="margin:5px;">'+value['name']+'</p>'
							})
							$("#list_lightbox").html(html);
						}else{
							alert(data.message);
						}
					}
				})
			}else{
				$(obj).parent().parent().html('Save error');
			}
		}
	})
}

function truncateDescription()
{
   	var showChar = 200;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ ' </span><span class="morecontent"><span>' + h + '</span>  <a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
}

$('#add-to-card-btn').click(function( event ) {
	event.preventDefault();
	var order_form = document.getElementById("order-form");
	order_form.submit();
});

$('#save-lightbox').on('click',function(){
	$(this).popover({
		content: function(){
			return $("#add_light_box").html();
		},
		html:true,
		container:'body',
		width:250,
		placement:'bottom',
		template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
	})
});

$('table.size-list > tbody > tr').click(function() {
	$('table.size-list > tbody > tr').removeClass('active');
	$('input:radio', this).prop('checked', true);
	$(this).addClass('active');
});
$('#download-btn').click(function(){
	var id = $('#img-id').val();
	var name = $('#img-name').val();
	var img = $('input[name=size_type]:checked').val();
	var url = '{{ URL }}';
	var open = false;
	$.ajax({
		url: '{{ URL }}/d/' + id + '/' + name,
		type: 'POST',
		data: {
			img: img
		},
		async: false,
		success: function(result){
			if( result.status == 'ok' ) {
				url = result.url;
				open = true;
			}
		}
	});
	if( open ) {
		window.open(url,'_blank');
	}
});
//Slide products
jQuery(document).ready(function ($) {
	var options = {
		$AutoPlay: false,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
		$AutoPlaySteps: 3,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
		$AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
		$PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

		$ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
		$SlideDuration: 160,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
		$MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
		$SlideWidth: 105,                                   //[Optional] Width of every slide in pixels, default value is width of 'slides' container
		//$SlideHeight: 150,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
		$SlideSpacing: 3, 					                //[Optional] Space between each slide in pixels, default value is 0
		$DisplayPieces: 3,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
		$ParkingPosition: 0,                              //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
		$UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
		$PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
		$DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

		$BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
			$Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
			$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
			$AutoCenter: 0,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
			$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
			$Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
			$SpacingX: 0,                                   //[Optional] Horizontal space between each item in pixel, default value is 0

			$SpacingY: 0,                                   //[Optional] Vertical space between each item in pixel, default value is 0
			$Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
		},

		$ArrowNavigatorOptions: {
			$Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
			$ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
			$AutoCenter: 2,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
			$Steps: 2                                       //[Optional] Steps to go for each navigation request, default value is 1
		}
	};

	var jssor_slider1 = new $JssorSlider$("div-products", options);
	//responsive code begin
	//you can remove responsive code if you don't want the slider scales while window resizes
	function ScaleSlider() {
		var bodyWidth = document.body.clientWidth;
		if (bodyWidth)
			jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 360));
		else
			window.setTimeout(ScaleSlider, 30);
	}
	ScaleSlider();

	$(window).bind("load", ScaleSlider);
	$(window).bind("resize", ScaleSlider);
	$(window).bind("orientationchange", ScaleSlider);
	//responsive code end
});
//End slide products

//Share

/*window.fbAsyncInit = function() {
FB.init({
  appId      : '464365180407589',//live: 464361690407938, test: 464365180407589
  xfbml      : true,
  version    : 'v2.4'
});
};

(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "//connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function shareFB(url)
{
	FB.ui({
	  method: 'share',
	  href: url,
	}, function(response){});	
}*/

//End share

</script>