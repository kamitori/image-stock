@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{URL}}/assets/css/style.css">
<style>
div.img-collection {
	/*width:250px;*/ 
	height:225px; 
	border:1px solid; 
	border-color:#D5D5D5; 
	padding:4px; 
	border-radius:3px; 
	position:relative;
}
div.img-collection .cover {
	width:100%; 
	height:100%; 
	position:relative;
}
div.img-collection:hover {
	-moz-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	-webkit-box-shadow: 0 0 5px rgba(0,0,0,0.5);
	box-shadow: 0 0 5px rgba(0,0,0,0.5);	
}
.col-centered{
    float: none;
    margin: 0 auto;
}
.lightbox-name {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	color:#999;
	margin-bottom:5px;	
}
.img-collection img {
	margin-bottom:4px;
}
</style>
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
            <li class="active">
                <a href="/user-collection/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="Collections">Collections</a>
            </li>			
            <li>
                <a href="/user-about/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html" title="About">About</a>
            </li>			
		</ul>
        
	</div>
</div>

    @if(count($arrLightboxes)>0)
        <?php
            $per_row= 4;
        ?>
        <div class="container" style="position:relative; text-align:center">
        @foreach($arrLightboxes as $key => $arrLightbox)
            
            @if($key%$per_row==0)
            <div class="row col-lg-10 col-lg-offset-1 text-center">        	
            @endif
                <div class="col-md-3" style="padding:10px">

                    <div class="img-collection">
                    	<div class="cover">
                        	<a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html?lid={{$arrLightbox['id']}}">
                        	@if(count($arrLightbox['images'])<=3)
                            	<?php $image = $arrLightbox['images'][rand(0, count($arrLightbox['images'])-1)];?>
                            	<img src="{{URL}}/pic/with-logo/{{$image['short_name']}}-{{$image['id']}}.jpg" style="width: 100%; height: 100%;">
                            @elseif(count($arrLightbox['images'])==4 || count($arrLightbox['images'])==5)    
                            	<?php $arrRange = $currentObj->arrangeImages(4);?>
                                @for($i=0; $i<4; $i++)
                                <?php $image = $arrLightbox['images'][$i];?>
                                    <img src="{{URL}}/pic/thumb/{{$image['short_name']}}-{{$image['id']}}.jpg" style="width: {{$arrRange[$i]['width']}}; height: {{$arrRange[$i]['height']}}; top: {{$arrRange[$i]['top']}}; left: {{$arrRange[$i]['left']}}; position:absolute;">                       
                                @endfor                                                            
                            @else
                            	<?php $arrRange = $currentObj->arrangeImages(rand(0, 3));?>
                                @for($i=0; $i<6; $i++)
                                <?php $image = $arrLightbox['images'][$i];?>
                                    <img src="{{URL}}/pic/thumb/{{$image['short_name']}}-{{$image['id']}}.jpg" style="width: {{$arrRange[$i]['width']}}; height: {{$arrRange[$i]['height']}}; top: {{$arrRange[$i]['top']}}; left: {{$arrRange[$i]['left']}}; position:absolute;">                       
                                @endfor                            
                            @endif
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-left">
                        <h4 class="lightbox-name" title="{{$arrLightbox['name']}}">
                            <span><a href="/user-reference/{{$user_obj->id}}/{{Str::slug($user_obj->first_name)}}.html?lid={{$arrLightbox['id']}}" style="color:#999">{{$arrLightbox['name']}}</a></span>
                        </h4>
                        <span style="color:#ccc">{{count($arrLightbox['images'])}} images</span>  
                    </div> 
                </div>
            @if($key%$per_row==$per_row -1)
            </div>
            @endif
        @endforeach
        </div>
    @else
        <span>
            <h4 class="container">We could not find any images.</h4>
        </span>    
    @endif

    @section('pageJS')

    @stop
    
@endif