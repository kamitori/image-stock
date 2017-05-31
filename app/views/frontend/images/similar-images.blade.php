	<div id="similar-images">
		<div class="hc2 abel bold">
        	<h4>Similar pieces
            	@if (is_object($image_obj))
	             <span>&nbsp;|&nbsp;&nbsp;&nbsp;<a href="{{URL}}/similar-images/{{$image_obj->id}}/{{$image_obj->short_name}}.html" style="font-size:16px">See All</a></span>
                @endif
             </h4>
        	<hr>
        </div>
		<div>
			@if(isset($arrSimilarImages) && count($arrSimilarImages)>0)
                <ul class="list-inline list_image">
                    @foreach($arrSimilarImages as $similarImage)
                        @if (isset($similarImage['path']))
                        <li class="text-center">
                            <a rel="same-artist-group" href="{{URL}}/pic-{{$similarImage['image_id']}}/{{$similarImage['short_name']}}.html" title="{{ $similarImage['name'] }}">
                                <div class="div_image">
                                    <img alt="" src="{{URL}}{{ $similarImage['path'] }}" title="<img src='{{ URL.'/pic/with-logo/'.$similarImage['short_name'].'-'.$similarImage['image_id'].'.jpg' }}' />" data-toggle="tooltip"  style="{{$similarImage['width']>$similarImage['height']?'height:100%':'width:100%'}}"/>
                                </div>
                                <div class="div-image-name" style="width:100%">{{ Str::words($similarImage['name'], 3, '...') }}</div>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>

                @if(isset($total_image) && is_object($image_obj) && $total_image>0 && $total_page>1)
                <hr>
                <!-- Pagination -->
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination">

                            <li>
                                @if($current > 1)
                                <a href="/similar-images/{{$image_obj->id}}/{{$image_obj->short_name}}.html?page={{ $current-1 }}" aria-label="Previous" data-value='prev'>
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                @endif
                            </li>
                            @for( $i = $from; $i<= $to; $i++)
                                @if($i == $current)
                                <li class="active"><a href="/similar-images/{{$image_obj->id}}/{{$image_obj->short_name}}.html?page={{ $i }}" data-value="{{ $i }}">{{ $i }}</a></li>
                                @else
                                <li><a href="/similar-images/{{$image_obj->id}}/{{$image_obj->short_name}}.html?page={{ $i }}" data-value="{{ $i }}">{{ $i }}</a></li>
                                @endif
                            @endfor
                            @if($current < $total_page)
                            <li>
                                <a href="/similar-images/{{$image_obj->id}}/{{$image_obj->short_name}}.html?page={{ $current+1 }}" aria-label="Next" data-value="next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <span>{{ $current }}/{{ $total_page }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
                @endif

			@else
				There are no items.
			@endif
		</div>
	</div>
