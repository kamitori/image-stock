<script type="text/javascript">
var Order = function() {

	function caculatePrice()
	{
		var sizew, sizeh = 0;
		var depth, wrap_option, edge_color, orientation, frame_colour, border, edge;	
		
		var order_type = $('#paletteContentProducts a[class="active"] input[name="order_type"]').val();

		var arr_order_type = order_type.split("_");
		var p_short_name = arr_order_type[0];
		var sku = arr_order_type[1];
	
//		var order_form = document.getElementById("order-form");		
//		var img_sizing = order_form.elements.namedItem("img_sizing");
		var sizew = $('#paletteContentSizes input[name="width"]').val();
		var sizeh = $('#paletteContentSizes input[name="height"]').val();
		
		if(sizew == '' || sizew <= 0 || sizeh == '' || sizeh <= 0 )
		{
			$('#price').val(0);
			$('#display_price').number(0, 2);				
			return;	
		}			
		
		depth = $('#paletteContent'+p_short_name+'-depth input[name="depth"]:checked').val();
		wrap_option = $('#paletteContent'+p_short_name+'-wrap_option input[name="wrap_option"]:checked').val();
		orientation = $('#paletteContent'+p_short_name+'-orientation input[name="orientation"]:checked').val();
		frame_colour = $('#paletteContent'+p_short_name+'-frame_colour input[name="frame_colour"]:checked').val();
		border = $('#paletteContent'+p_short_name+'-border input[name="border"]:checked').val();
		edge_color = $('#paletteContent'+p_short_name+'-edge_color input[name="edge_color"]:checked').val();
		edge = $('#paletteContent'+p_short_name+'-edge input[name="edge"]:checked').val();
		
		var quantity = $('#quantity').val();		
	
		$('#sell_price').val(0);
		$('#price').val(0);
		
		$('#display_price').html('<img src="{{URL}}/assets/images/others/ajax-loader.gif" width="20px">');
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
			order_qty: quantity
		},
		function(data, status){
			if(data['status'] == 'ok')
			{
				var amount = data['data']['amount'];
				var sell_price = data['data']['sell_price'];
				$('#sell_price').val(sell_price);
				$('#price').val(amount);
				$('#display_price').number(amount, 2);			
			}
		});				
	}
	
	function addToCart()
	{
		var sizew, sizeh = 0;
		var depth, wrap_option, edge_color, orientation, frame_colour, border, edge;	
		
		var order_type = $('#paletteContentProducts a[class="active"] input[name="order_type"]').val();

		var arr_order_type = order_type.split("_");
		var p_short_name = arr_order_type[0];
		var sku = arr_order_type[1];
	
//		var order_form = document.getElementById("order-form");		
//		var img_sizing = order_form.elements.namedItem("img_sizing");
		var sizew = $('#paletteContentSizes input[name="width"]').val();
		var sizeh = $('#paletteContentSizes input[name="height"]').val();
		
		if(sizew == '' || sizew <= 0 || sizeh == '' || sizeh <= 0 )
		{
			$('#price').val(0);
			$('#display_price').number(0, 2);				
			return;	
		}			
		
		depth = $('#paletteContent'+p_short_name+'-depth input[name="depth"]:checked').val();
		wrap_option = $('#paletteContent'+p_short_name+'-wrap_option input[name="wrap_option"]:checked').val();
		orientation = $('#paletteContent'+p_short_name+'-orientation input[name="orientation"]:checked').val();
		frame_colour = $('#paletteContent'+p_short_name+'-frame_colour input[name="frame_colour"]:checked').val();
		border = $('#paletteContent'+p_short_name+'-border input[name="border"]:checked').val();
		edge_color = $('#paletteContent'+p_short_name+'-edge_color input[name="edge_color"]:checked').val();
		edge = $('#paletteContent'+p_short_name+'-edge input[name="edge"]:checked').val();
		
		var quantity = $('#quantity').val();		
		
		var sell_price = $('#sell_price').val();
		
		
		$('#divAddToCart').html('<img src="{{URL}}/assets/images/others/ajax-loader.gif" width="20px">');
		$.post("/cart/add",
		{
			order_image_id: $('#order_image_id').val(),
			order_image_name: $('#order_image_name').val(),
			path_thumb: $('#path_thumb').val(),
			order_type: order_type,
			img_width: sizew,
			img_height: sizeh,
			option_depth: depth,
			option_wrap_option: wrap_option,
			option_orientation: orientation,
			option_frame_colour: frame_colour,
			option_border: border,
			option_edge_color: edge_color,
			option_edge: edge,
			order_qty: quantity,
			sell_price: sell_price
		},
		function(data, status){
			if(data['status'] == 'ok')
			{
				location.href = '{{URL}}/cart';
			}
		});			
	}
	
	function createPage(total_image,total_page){
		var html='';

		if(total_image>0 && total_page>1){
			var current_page = parseInt($("input[name=page]").val());
			var from = current_page- 2 > 0 ? current_page- 2 : 1;
			var to = current_page+ 2<= total_page ? current_page+2 : total_page;
			//console.log(current_page, from,to,total_page);
			html+='<li>';
			html+='<a href="#" aria-label="Previous" data-value="prev" data-totalPage="'+total_page+'" onclick="Order.changePage(this)">';
			html+=		'<span aria-hidden="true">&laquo;</span>';
			html+=	'</a>';
			html+=	'</li>';
			for(i=from;i<=to;i++){
				if(i==current_page){
					html+='<li class="active"><a href="#" data-value="'+i+'" onclick="Order.changePage(this)">'+i+'</a></li>';
				}else{
					html+='<li><a href="#" data-value="'+i+'" onclick="Order.changePage(this)">'+i+'</a></li>';
				}
			}
			html+='<li>';
			html+=	'<a href="#" aria-label="Next" data-value="next" data-totalPage="'+total_page+'"  onclick="Order.changePage(this)">';
			html+=		'<span aria-hidden="true">&raquo;</span>';
			html+=	'</a>';
			html+=	'</li>';
			html+='<li>';
			html+='	<span>'+current_page+'/'+total_page+'</span>';
			html+='</li>';
		}
		$(".pagination").html(html);
	}
	
	function changePage(obj){
		var page = $(obj).attr('data-value');
		if(page=='next'){
			//console.log(parseInt($("input[name=page]").val()) );
			var total_page = $(obj).attr('data-totalPage');
			if(parseInt($("input[name=page]").val()) < total_page){
				$("input[name=page]").val(parseInt($("input[name=page]").val())+1);
				Main.getImages($("input[name=page]").val(), $("#searchByTag input[name=searchlib_text]").val());
			}
		}else{
			if(page=='prev'){
				if(parseInt($("input[name=page]").val()) > 1){
					$("input[name=page]").val(parseInt($("input[name=page]").val())-1);
					Main.getImages($("input[name=page]").val(), $("#searchByTag input[name=searchlib_text]").val());
				}
			}else{
				$("input[name=page]").val(page);
				Main.getImages($("input[name=page]").val(), $("#searchByTag input[name=searchlib_text]").val());
			}
		}
	};
	
    function resolution(){
        $("#dialog_resolution").dialog({width: '70%',height: 600}).dialog("open");
        $.ajax({
            url:"{{URL}}/design/analyze-image",
            type:"POST",
            data:{img: $("#svg-main .main-image").attr("href")},
            success: function(ret){
                ret = JSON.parse(ret);
                var html = '';
                html += '<div id="content">';
                    html += '<div style="float:left; margin-right: 20px; width:50%;">';
                        html += '<img style="width: 100%;" src="'+ ret.image +'" />';
                    html += '</div>';
                    html += '<div class="info">';
                    html += ' <ul >';
                                    html += '<li><h2>About your picture: </h2></li>';
                                    html += '<li>Your file size: <b>'+ret.size+'</b> MB </li>';
                                    html += '<li>Your file resolution: <b>'+ret.width+'</b> by <b>'+ret.height+'</b> pixels </li>';
                                    html += '<li><b>'+ret.mp+'</b> Megapixels</li>';
                        html += '</ul>';
                    html += '</div>';
                    html += '<div class="clear"></div>';
                    html += '<table id="result" border="0" cellpadding="0" cellspacing="0">';
                    for(var i in ret.dimensions){
                        html += '<tr>';
                        html += '<td width="220" valign="top" class="txmedium" style="padding:10px;spacing:5px">';
                        html += '<b>'+ret.dimensions[i][0]+'x</b><b>'+ret.dimensions[i][1]+' inches</b>';
                        html += '</td>';
                        html += '<td class="tx2" style="padding:10px;spacing:5px">';
                        html += ret.dimensions[i][3];
                        html += '</td>';
                        html += '</tr>';
                    }
                    html += '</table>';
                html += '</div>'
                $("#dialog_resolution").html(html);
            }
        });
    }		
	
	return {
		caculatePrice: function() {
			caculatePrice();
		},
		addToCart: function() {
			addToCart();
		},
		createPage: function(total_image,total_page) {
			createPage(total_image,total_page);
		},
		changePage: function(obj) {
			changePage(obj);
		},
		resolution: function() {
			resolution();
		},

	};
}();
</script>