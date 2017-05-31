@foreach($images as $elem)
	<?php $img_title = "<img src='".URL."/pic/with-logo/".$elem['short_name']."-".$elem['id'].".jpg' >"; ?>
    
    <div class="item inline large_grid">
        <a href="{{URL}}/pic-{{$elem['id']}}/{{$elem['short_name']}}.html"  data-toggle="tooltip" data-placement="right auto" title="{{$img_title}}">
            <div class="img_mark"><img src="{{URL}}/pic/thumb/{{$elem['short_name']}}-{{$elem['id']}}.jpg" /></div>
        </a>
        <span class="image_action">
            <span class="glyphicon glyphicon-heart" title="{{ $image_action_title }}" data-id-image="{{$elem['id']}}"  data-toggle="popover"></span><small>(<span id="count_favorite_{{$elem['id']}}">{{$elem["count_favorite"]}}</span>)</small>
            <span class="glyphicon glyphicon-arrow-down is_mod_download" title="Download" onclick="downloadImage('{{$elem['id']}}', '{{$elem['short_name']}}')"></span>
        </span>
    </div>    
@endforeach