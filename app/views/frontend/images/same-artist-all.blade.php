@if(is_object($user_obj))
<div>
	<div id="options_view" class="container" style="padding-top:30px; vertical-align:bottom;">
		<ul class="pull-right" style="margin-top:40px;">
			<li><span class="glyphicon glyphicon-th" aria-hidden="true" onclick="loadImages('small_grid', null);"></span>
			<li><span class="glyphicon glyphicon-th-large" aria-hidden="true" onclick="loadImages('grid', null);"></span>
			<li><span class="mosaic_grid" onclick="loadImages('mosaic', null);"></span></li>
			<li><span class="glyphicon glyphicon-cog" aria-hidden="true" data-placement="left"></span>
		</ul>
    	<ul class="pull-left" style="padding-bottom:5px; padding-left:0; color:#333; font-size:150%">
        	<li>
            @if($user_obj->image != '' && File::exists(public_path().DS.$user_obj->image))
                <img src="{{ URL::asset( $user_obj->image ) }}" alt="{{$user_obj->first_name}}" class="img-circle" width="70px" height="70px">
            @else
                <img src="{{ URL::asset( 'assets/images/noimage/person.jpg' ) }}" alt="Image for Profile" class="img-circle" height="70px">
            @endif
            </li>                  	
        	<li>{{$user_obj->first_name}} {{$user_obj->last_name}}</li>
        </ul>    
        
	</div>
	<div class="collapse navbar-collapse" id="navigator">

		<ul class="nav nav-tabs left" id="sort_method">
			
            <li class="active">
            	@if(is_object($category_obj))                
                	<a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html?cat={{$category_obj->id}}" title="{{$category_obj->name}}">{{$category_obj->name}}</a>
            	@elseif(is_object($lightbox_obj))                
                	<a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html?lid={{$lightbox_obj->id}}" title="{{$lightbox_obj->name}}">{{$lightbox_obj->name}}</a>
                @else
                	<a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="All Images">All Images</a>
                @endif
            </li>
            <li>
                <a href="/user-gallery/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="Galleries">Galleries</a>
            </li>
            <li>
                <a href="/user-collection/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="Collections">Collections</a>
            </li>			
            <li>
                <a href="/user-about/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="About">About</a>
            </li>			
		</ul>
        
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
<?php 
	if(is_object($category_obj))
	{
		$load_images_url = "/user-reference/".$user_obj->id."/".Str::slug($user_obj->first_name).".html?catid=".$category_obj->id;
	}
	elseif(is_object($lightbox_obj))
	{
		$load_images_url = "/user-reference/".$user_obj->id."/".Str::slug($user_obj->first_name).".html?lid=".$lightbox_obj->id;
	}
	else
	{
		$load_images_url = "/user-reference/".$user_obj->id."/".Str::slug($user_obj->first_name).".html";
	}
?>
@section('pageJS')
<script src="{{URL}}/assets/global/plugins/rowgrid/row-grid.js" type="text/javascript" charset="utf-8" ></script>
<script>
	var list_image = {{ json_encode($images) }}; //console.log(list_image);
	var total_page = {{ $total_page }};
	var total_image = {{ $total_image }};
	var load_url = '{{ $load_images_url }}';

</script>
<script src="{{URL}}/assets/global/scripts/grid-images.js" type="text/javascript" charset="utf-8" ></script>
@stop

@endif