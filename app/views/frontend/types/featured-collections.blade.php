
<div id="featured-collections" class="container">
	<div class="col-md-10 col-md-offset-1">
        <h2 class="hc1 abel text-left" style="margin-bottom:20px;">Featured Photo Collections</h2>
        @for($i=0; $i<9; $i++)
            @if (isset($arrFeaturedCollection[$i]['path']))
                <div class="col-md-4" style="display:inline-block; padding:7px; height:165px; overflow:hidden; margin-bottom:5px; margin-top:5px;">
                    <div>
                        <a href="{{ URL.'/collections/'.$arrFeaturedCollection[$i]['collection_id'].'-'.$arrFeaturedCollection[$i]['collection_short_name'].'.html' }}" title="{{ $arrFeaturedCollection[$i]['collection_name'] }}">
                            <img src="{{URL}}/{{ $arrFeaturedCollection[$i]['path'] }}" style="width: 100%" />
                        </a>
                    </div>
                    <div class="collection-link">
                        <a href="{{ URL.'/collections/'.$arrFeaturedCollection[$i]['collection_id'].'-'.$arrFeaturedCollection[$i]['collection_short_name'].'.html' }}" title="{{ $arrFeaturedCollection[$i]['collection_name'] }}">{{ $arrFeaturedCollection[$i]['collection_name'] }} </a>
                    </div>
                </div>
            @else
                <div class="col-md-4" style="display:inline-block; padding:7px; height:165px; overflow:hidden; margin-bottom:5px; margin-top:5px;">
                    <div>
                        <a href="#">
                        <img src="{{URL}}/assets/images/noimage/315x165.gif" style="width: 100%;">
                        </a>
                    </div>
                    <div class="collection-link">No collections</div>
                </div>
            @endif
        @endfor
	</div>
</div>
