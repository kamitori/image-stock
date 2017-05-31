	<div id="same-artist">
		<div style="padding-top:10px" class="hc2 abel bold">
        	<h4>Also from {{ $user_obj->first_name.' '.$user_obj->last_name }}
            	@if (isset($user_id))
	             <span>&nbsp;|&nbsp;&nbsp;&nbsp;<a href="{{URL}}/user-reference/{{$user_id}}/{{Str::slug($user_obj->first_name)}}.html" style="font-size:16px">See All</a></span>
                @endif
             </h4>
			<hr>
		</div>
		<div class="container">
			@if(isset($arrSameArtist) && count($arrSameArtist)>0)
			<ul class="list-inline list_image">
				@foreach($arrSameArtist as $sameArtist)
					@if (isset($sameArtist['path']))
					<li class="text-center">
						<a rel="same-artist-group" href="{{URL}}/pic-{{$sameArtist['image_id']}}/{{$sameArtist['short_name']}}.html" >
							<div class="div_image" style="">
								<img alt="" src="{{URL}}{{ $sameArtist['path'] }}" title="<img src='{{ URL.'/pic/with-logo/'.$sameArtist['short_name'].'-'.$sameArtist['image_id'].'.jpg' }}' />" data-toggle="tooltip" style="{{ $sameArtist['width']>$sameArtist['height']?'height:100%':'width:100%' }}"/>
							</div>
							<div class="div-image-name" style="width:100%">{{ Str::words($sameArtist['name'], 3, '...') }}</div>
						</a>
					</li>
					@endif
				@endforeach
			</ul>

                @if(isset($total_image) && isset($user_id) && $total_image>0 && $total_page>1)
                <hr>
                <!-- Pagination -->
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination">

                            <li>
                                @if($current > 1)
                                <a href="/user-reference/{{$user_id}}/{{Str::slug($user_obj->first_name)}}.html?page={{ $current-1 }}" aria-label="Previous" data-value='prev'>
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                @endif
                            </li>
                            @for( $i = $from; $i<= $to; $i++)
                                @if($i == $current)
                                <li class="active"><a href="/user-reference/{{$user_id}}/{{Str::slug($user_obj->first_name)}}.html?page={{ $i }}" data-value="{{ $i }}">{{ $i }}</a></li>
                                @else
                                <li><a href="/user-reference/{{$user_id}}/{{Str::slug($user_obj->first_name)}}.html?page={{ $i }}" data-value="{{ $i }}">{{ $i }}</a></li>
                                @endif
                            @endfor
                            @if($current < $total_page)
                            <li>
                                <a href="/user-reference/{{$user_id}}/{{Str::slug($user_obj->first_name)}}.html?page={{ $current+1 }}" aria-label="Next" data-value="next">
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
