<div>
	<div id="options_view" class="container">
		<ul class="pull-right">
			<li><span class="glyphicon glyphicon-th" aria-hidden="true" onclick="loadImages('small_grid', null);"></span>
			<li><span class="glyphicon glyphicon-th-large" aria-hidden="true" onclick="loadImages('grid', null);"></span>
			<li><span class="mosaic_grid" onclick="loadImages('mosaic', null);"></span></li>
			<li><span class="glyphicon glyphicon-cog" aria-hidden="true" data-placement="left"></span>
		</ul>
	</div>
	<div class="collapse navbar-collapse" id="navigator">
    	<ul class="nav nav-tabs left" style="margin-top:-5px; color:#333;"><h4>{{ $collection_name }} Collection</h4></ul>
		<ul class="pagination">

		</ul>
	</div>
</div>
<span id="result_search">
	@if(empty($images))
		<h4 class="container">We could not find any images.</h4>
	@endif
</span>
<div class="container" id="grid-image">

</div>
<div class="text-center">
	<ul class="pagination">

	</ul>
</div>
@if(count($categories)>0)
<div class="panel panel-default">
  <div class="panel-heading hc2 abel bold">View Images by Category</div>
  <div class="panel-body category-list">
  	<?php
		$per_col= round(count($categories)/4);
	?>
   	@foreach($categories as $key => $category)
   		@if($key%$per_col==0)
   		<ul class="col-md-3  col-sm-6  col-xs-6 list-unstyled">
   		@endif
   			<li><a href="{{ URL }}/cat-{{ $category['short_name'] }}-{{ $category['id'] }}.html" title="{{ $category['name'] }}">{{ $category['name'] }}</a> </li>
   		@if($key%$per_col==$per_col -1)
   		</ul>
   		@endif
   	@endforeach
  </div>
</div>
@endif
<div id="take_page" style="display:none;">
	<select name="take-select" onchange="changeTake(this)" class="form-control">
		<option value="30" {{ (Input::has('take')&&Input::get('take')==30)?'selected':'' }}>30</option>
		<option value="50" {{ (Input::has('take')&&Input::get('take')==50)?'selected':'' }}>50</option>
		<option value="100" {{ (Input::has('take')&&Input::get('take')==100)?'selected':'' }}>100</option>
		<option value="200" {{ (Input::has('take')&&Input::get('take')==200)?'selected':'' }}>200</option>
	</select>
</div>

<div id="add_light_box" style="display:none;">
	@if(Auth::user()->check())
		<div class="popover_lightbox" id="list_lightbox">
			@foreach($lightboxes as $lightbox)
				<p data-id-lightbox="{{ $lightbox['id'] }}" class="btn btn-default " onclick="addLightBox(this)" style="margin:5px;">{{$lightbox['name']}}</p>
			@endforeach
		</div>
    
		<div class="clear-fix">
			<input type="text" id="lightbox_name">
			<button type="button" class="btn btn-default" onclick="saveLightBox(this)">Save</button>
		</div>
    @else
		<div class="clear-fix">
			<button type="button" class="btn btn-default" onclick="saveLightBox(this)" style="width:100px;">Like</button>
		</div>    
	@endif
	
</div>

@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{URL}}/assets/css/style.css">
<style type="text/css" media="screen">
	.close_popover{
		position: absolute;
		top:5px;
		right:5px;
		cursor: pointer;
		padding: 3px;
	}
	.close_popover:hover{
		background: #ddd;
	}
	.popover_lightbox{
		min-width: 250px;
	}
	@if($mod_download==0)
	.is_mod_download{
		display: none!important;
	}
	@endif
</style>
@stop
@section('pageJS')
<script src="{{URL}}/assets/global/plugins/rowgrid/row-grid.js" type="text/javascript" charset="utf-8" ></script>
<script>
	var list_image = {{ json_encode($images) }}; //console.log(list_image);
	var total_page = {{ $total_page }};
	var total_image = {{ $total_image }};
	var load_url = "/collections/{{$collection_id}}-{{$collection_short_name}}.html";

</script>
<script src="{{URL}}/assets/global/scripts/grid-images.js" type="text/javascript" charset="utf-8" ></script>
@stop