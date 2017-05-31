<div id="view-image-categories" style="text-align:left">
    @if(is_array($arrImageCategories) && count($arrImageCategories)>0)
    
        <h5><span class="fa fa-folder"></span>&nbsp;&nbsp;Image's Categories</h5>
        <hr style="margin-top:10px;"/>
    	<div style="display:inline-block; padding:0 5px 30px 5px;">
            @foreach($arrImageCategories as $category)            
                
                    <div style="display:inline; padding:7px 0 7px 0;">
                        <a href="{{ URL.'/cat-'.$category['short_name'].'-'.$category['id'].'.html' }}" title="{{ $category['name'] }}">{{ $category['name'] }}</a>,
                    </div>
                            
            @endforeach
        </div>
	@endif
</div>
