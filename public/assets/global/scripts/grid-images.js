// JavaScript Document
var options = {minMargin: 10, maxMargin: 15, itemSelector: ".item"};

$(document).ready(function() {
	
	loadImages('mosaic', 'paging');
	
	createPage(total_image,total_page);
			
	$("#options_view .glyphicon-cog").popover({
		content: $("#take_page").html(),
		html:true,
		width:50,
	})
	
	//set Take select box
	$("#options_view .glyphicon-cog").click(function( event ) {
	  event.preventDefault();			
		$('.popover-content select[name=take-select] option').each(function()
		{
			var take = $("input[name=take]").val();
			if($(this).val() == take)
			{
				$(this).attr('selected', true);
			}
		});		  
	});
	
	
	$('body').on('click', function (e) {
		//did not click a popover toggle or popover
		if ($(e.target).data('toggle') !== 'popover'
		&& $(e.target).parents('.popover.in').length === 0
		) {
			$('.image_action .glyphicon-heart').popover('hide');
		}

		if ($(e.target).data('toggle') === 'popover'
			&& $(e.target).attr('aria-describedby')!==$(".popover.in").get(0).id
			){
			$('.image_action .glyphicon-heart').not($(e.target)).popover('hide');
		}
	});
	
});

function loadImages(sort_style, action, callback)
{
	$("input[name=sort_style]").val(sort_style);
	
	var page = $("input[name=page]").val();
	var take = $("input[name=take]").val();
	//alert(page);
	$.get(load_url, 
	{
		sort_style: sort_style,
		page: page,
		take: take,
		action: action
	}, 
	function(data, status){
		
		$('#grid-image').html(data['html']);			
		showAddLightBox();
		
		if(sort_style == 'mosaic')
		{
			$("#grid-image").rowGrid(options);	
		}
		else
		{
			$("#grid-image a").tooltip({
				html: true,
				container:'body',
			});								
		}
		if(callback != null)
		{
			callback(data);	
		}	
	});			
}

function changePage(obj){
	
	var sort_style = $("input[name=sort_style]").val();
	
	var page = $(obj).attr('data-value');
	if(page=='next'){
		//console.log(parseInt($("input[name=page]").val()) );
		if(parseInt($("input[name=page]").val()) < total_page){
			$("input[name=page]").val(parseInt($("input[name=page]").val())+1);
			loadImages(sort_style, 'paging', loadImageCallback);
		}
	}else{
		if(page=='prev'){
			if(parseInt($("input[name=page]").val()) > 1){
				$("input[name=page]").val(parseInt($("input[name=page]").val())-1);
				loadImages(sort_style, 'paging', loadImageCallback);
			}
		}else{
			$("input[name=page]").val(page);
			loadImages(sort_style, 'paging', loadImageCallback);
		}
	}
};

function loadImageCallback(data){
	
	total_page = data['total_page'];
	total_image = data['total_image'];
	sort_style = data['sort_style'];
	
	//console.log(data);
	createPage(total_image,total_page);
	
	$(".popover").remove();			
	$(".tooltip").remove();
	
	var html='';
	if(total_image=0){
		html='<h4 class="container">We could not find any images.</h4>';
	}	
	$("#result_search").html(html);
}

function createPage(total_image,total_page){
	var html='';

	if(total_image>0 && total_page>1){
		var current_page = parseInt($("input[name=page]").val());
		var from = current_page- 2 > 0 ? current_page- 2 : 1;
		var to = current_page+ 2<= total_page ? current_page+2 : total_page;
		//console.log(current_page, from,to,total_page);
		html+='<li>';
		html+='<a href="#" aria-label="Previous" data-value="prev" onclick="changePage(this)">';
		html+=		'<span aria-hidden="true">&laquo;</span>';
		html+=	'</a>';
		html+=	'</li>';
		for(i=from;i<=to;i++){
			if(i==current_page){
				html+='<li class="active"><a href="#" data-value="'+i+'" onclick="changePage(this)">'+i+'</a></li>';
			}else{
				html+='<li><a href="#" data-value="'+i+'" onclick="changePage(this)">'+i+'</a></li>';
			}
		}
		html+='<li>';
		html+=	'<a href="#" aria-label="Next" data-value="next" onclick="changePage(this)">';
		html+=		'<span aria-hidden="true">&raquo;</span>';
		html+=	'</a>';
		html+=	'</li>';
		html+='<li>';
		html+='	<span>'+current_page+'/'+total_page+'</span>';
		html+='</li>';
	}
	$(".pagination").html(html);
}

function changeTake(obj){
	$("input[name=page]").val(1);
	var take = $(obj).val();
	$("input[name=take]").val(take);
	
	var sort_style = $("input[name=sort_style]").val();
	loadImages(sort_style, 'paging', loadImageCallback);
}

function showAddLightBox(){
	$('#grid-image [data-toggle=popover]').popover({
		content: function(){
			return $("#add_light_box").html();
		},
		html:true,
		container:'body',
		width:150,
		placement:'bottom',
		template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="close_popover" onclick="closePopover()">X</div><div class="popover-content"></div></div>'
	});
	$('#grid-image [data-toggle=popover]').on('shown.bs.popover',function(){
		html = '<input type="hidden" name="" id="id_image" value="'+$(this).data('id-image')+'">';
		$("#"+$(this).attr('aria-describedby')+' .popover-content').append(html);
	})
}


function addLightBox(obj){
	var id_lightbox = $(obj).data('id-lightbox');
	var id_image = $(obj).parent().parent().parent().find($("#id_image")).val();
	$.ajax({
		url:'/lightbox/add/'+id_image+'/'+id_lightbox,
		type:'GET',
		success:function(data){
			if(data.result = 'success'){
				
				//Increase the number of like of an image to 1				
				if(data.count == 0)
				{
					var count_favorite = 1;
					count_favorite += parseInt($('#count_favorite_'+id_image).html());
					$('#count_favorite_'+id_image).html(count_favorite);							
				}
				
				$(obj).parent().parent().html('<div class="popover_lightbox">'+'Saved to '+$(obj).text()+'</div>');
			}else{
				$(obj).parent().parent().html('<div class="popover_lightbox">Save error</div>');
			}
		}
	})
}

function saveLightBox(obj){
	var name_lightbox = $(obj).prev().val();
	var id_image = $(obj).parent().parent().parent().find($("#id_image")).val();
	$.ajax({
		url:'/lightbox/add-by-name/'+id_image+'/'+name_lightbox,
		type:'GET',
		success:function(data){
			if(data.result == 'success'){

				//Increase the number of like of an image to 1				
				if(data.count == 0)
				{
					var count_favorite = 1;
					count_favorite += parseInt($('#count_favorite_'+id_image).html());
					$('#count_favorite_'+id_image).html(count_favorite);							
				}
				
				if(data.case == 'favorites')
				{
					name_lightbox = data.case;
				}

				$(obj).parent().parent().html('<div class="popover_lightbox">'+'Saved to '+name_lightbox+'</div>');
				$.ajax({
					url:'/lightbox/get-lightbox-user',
					type:'GET',
					success:function(data){
						if(data.result=='success'){
							html='';
							$.each(data.lightboxes,function(key,value){
								html+='<p data-id-lightbox="'+value['id']+'" class="btn btn-default " onclick="addLightBox(this)" style="margin:5px;">'+value['name']+'</p>'
							})
							$("#list_lightbox").html(html);
						}else{
							//alert(data.message);
						}
					}
				})
			}else{
				$(obj).parent().parent().html('Save error');
			}
		}
	})
}


function closePopover(){
	$('.image_action .glyphicon-heart').popover('hide');
}

function downloadImage(image_id, short_name)
{
	var url = '/pic-'+image_id+'/'+short_name+'.html?a=download';
	window.location = url;
}
