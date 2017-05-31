@foreach($images as $elem)
<div class="item inline">
    <a href="{{URL}}/pic-{{$elem['id']}}/{{$elem['short_name']}}.html">
        <img src="{{ URL}}/pic/with-logo/{{$elem['short_name']}}-{{$elem['id']}}.jpg" height="250" width="{{ $elem['width']/$elem['height']*250 }}" />
    </a>
    <span class="image_action">
        <span class="glyphicon glyphicon-heart" title="{{ $image_action_title }}" data-id-image="{{$elem['id']}}" data-toggle="popover"></span><small>(<span id="count_favorite_{{$elem['id']}}">{{$elem["count_favorite"]}}</span>)</small>
        <span class="glyphicon glyphicon-arrow-down is_mod_download" title="Download" onclick="downloadImage('{{$elem['id']}}', '{{$elem['short_name']}}')"></span>
    </span>
</div>
@endforeach