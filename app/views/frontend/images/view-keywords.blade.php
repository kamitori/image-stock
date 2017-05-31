<div id="view-keywords" style="text-align:left">
    @if(is_array($arrKeywords))
    
        <h5><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Keywords</h5>
        <hr style="margin-top:10px;"/>
    	<div style="display:inline-block; padding:0 5px 30px 5px;">
            @foreach($arrKeywords as $keyword)            
                
                    <div style="display:inline; padding:7px 0 7px 0;">
                        <a href="{{ URL.'/search?keyword='.$keyword }}" title="{{ $keyword }}">{{ $keyword }}</a>,
                    </div>
                            
            @endforeach
        </div>
	@endif
</div>
