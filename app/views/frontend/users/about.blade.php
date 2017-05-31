@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{URL}}/assets/css/style.css">
@stop


@if(is_object($user_obj))
<div>
	<div id="options_view" class="container" style="padding-top:30px; vertical-align:bottom;">
    	<ul class="pull-left" style="padding-bottom:5px; padding-left:0; color:#333; font-size:150%">
        	<li>
            @if($user_obj->image != '' && File::exists(public_path().DS.$user_obj->image))
                <img src="{{ URL::asset( $user_obj->image ) }}" alt="{{$user_obj->first_name}}" class="img-circle"  width="70px" height="70px">
            @else
                <img src="{{ URL::asset( 'assets/images/noimage/person.jpg' ) }}" alt="Image for Profile" class="img-circle" width="70px" height="70px">
            @endif
            </li>                  	
        	<li>{{$user_obj->first_name}} {{$user_obj->last_name}}</li>
        </ul>            
	</div>
	<div class="collapse navbar-collapse" id="navigator">

		<ul class="nav nav-tabs left" id="sort_method">			
            <li>
                <a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="All Images">All Images</a>
            </li>
            <li>
                <a href="/user-gallery/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="Galleries">Galleries</a>
            </li>
            <li>
                <a href="/user-collection/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="Collections">Collections</a>
            </li>			
            <li class="active">
                <a href="/user-about/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="About">About</a>
            </li>			
		</ul>
        
	</div>
</div>

<div class="container" style="position:relative; padding-bottom:20px;">
	@if($user_obj->description != '')
    <div class="row col-md-8 col-md-offset-2 text-left">    	
        	{{ nl2br(e($user_obj->description)) }}        
    </div>
    @else
        <span>
            <h4 class="container">There has not information yet.</h4>
        </span>    
    @endif
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

@section('pageJS')

@stop
    
@endif