@if (isset($imageObj))
	<div id="div-main-image" class="col-md-6" style="padding-left:0">
        <input type="hidden" name="image_id" id="image_id" value="{{ $imageObj['image_id'] }}" />
        <input type="hidden" name="image_name" id="image_name" value="{{ $imageObj['name'] }}" />
        <input type="hidden" name="image_path" id="image_path" value="{{URL}}{{ $imageObj['path'] }}" />
        <input type="hidden" name="image_width" id="image_width" value="{{ $imageObj['width'] }}" />
        <input type="hidden" name="image_height" id="image_height" value="{{ $imageObj['height'] }}" />
        <input type="hidden" name="image_dpi" id="image_dpi" value="{{ $imageObj['dpi'] }}" />
    
		<div id="main-image">
        	<div id="main-image-cover" class="framed">
            	<div id="main-image-mat" class="mat">
					<img id="main-image-display" src="{{URL}}{{ $imageObj['path'] }}" class="framed"/>
                </div>
            </div>
		</div>
		<div style="display:inline-block;">
			@if (Auth::user()->check())
				<div style="display:inline; padding:10px 10px 10px 10px">
					<span class="fa fa-lightbulb-o"></span>
					<a href="javascript:void(0)" id="save-lightbox" data-toggle="popover" class="small" title="Save to a lightbox">Save to a lightbox</a>
				</div>
				<div class="small" style="display:inline; padding:10px 10px 10px 10px">
					<span class="glyphicon glyphicon-search"></span>
					<a href="{{URL}}/similar-images/{{ $imageObj['name'] }}">Find similar images</a>
				</div>
				<div class="small" style="display:inline; padding:10px 10px 10px 10px">
					<span class="glyphicon glyphicon-share"></span>
					<a href="javascript:void(0);" title="Share this page" data-toggle="modal" data-target="#shareModal">Share</a>
				</div>
			@endif
		</div>
        <div class="pull-right" style="padding-top: 15px;"><h5><a data-toggle="modal" data-target="#modal-image-on-wall" class="fa fa-picture-o" style="cursor:pointer; text-decoration:none;">&nbsp;&nbsp;View on a wall</a></h5></div>
        
        <div class="container text-left" id="div-view-keywords" style="display:inline-block; padding:0; margin-top:-15px;">{{ $htmlKeywords }}</div>
        
        <div class="container text-left" id="div-view-image-categories" style="display:inline-block; padding:0;">{{ $htmlImageCategories }}</div>

	</div>

	<div align="center" class="col-md-6 right_column" style="padding-top:5px;">
		<div  style="text-align:left;">
			<h5><b>{{ $imageObj['name'] }}</b></h5>
            <p class="more">{{ nl2br(e($imageObj['description'])) }}</p>
			<p><small>Image ID: </small>{{ $imageObj['image_id'] }}</p>
            <p><small>Artist: {{ $imageObj['artist'] }}</small></p>
			<p><small>By: </small><a href="{{URL}}/user-reference/{{$imageObj['author_id']}}/{{ Str::slug($imageObj['user']->first_name) }}.html">{{ $imageObj['user']->first_name }}</a></p>
		</div>
		@if (Auth::user()->check())
		<div  style="text-align:left;">
            <div class="panel-body" style="padding-top:0;">
                @if($action == 'download')
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills on_off_tab">                    
                        <li class=""><a href="#order-pills" data-toggle="tab" aria-expanded="false">Order</a></li>
                    </ul>                
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top:15px">
                        <div class="tab-pane fade" id="order-pills">
                            {{ $htmlOrder }}
                        </div>
                        <div class="tab-pane fade active in" id="download-pills">
                            {{ $htmlChooseDownload }}
                        </div>
                    </div>
            	@else
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills on_off_tab">                    
                        <li class="active"><a href="#order-pills" data-toggle="tab" aria-expanded="true">Order</a></li>
                        <li class=""><a href="#download-pills" data-toggle="tab" aria-expanded="false">Download</a></li>                    
                    </ul>                
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top:15px">
                        <div class="tab-pane fade active in" id="order-pills">
                            {{ $htmlOrder }}
                        </div>
                        <div class="tab-pane fade" id="download-pills">
                            {{ $htmlChooseDownload }}
                        </div>
                    </div>                
                @endif    
            </div>
        </div>
		@else
		<div style="text-align:left;">
			<div class="panel-body">
			@if($action == 'download')
                <!-- Nav tabs -->
                <ul class="nav nav-tabs on_off_tab">            	
                    <li><a href="#order" data-toggle="tab">Order</a></li>
                    <li class="active"><a href="#download" data-toggle="tab">Download</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content" style="padding-top:15px">
                    <div class="tab-pane fade" id="order">
                        {{ $htmlOrder }}
                    </div>
                    <div class="tab-pane fade in active" id="download">                    
                        {{ $htmlSignin }}
                    </div>
                </div>
            @else
                <!-- Nav tabs -->
                <ul class="nav nav-tabs on_off_tab">            	
                    <li class="active"><a href="#order" data-toggle="tab">Order</a></li>
                    <li><a href="#download" data-toggle="tab">Download</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content" style="padding-top:15px">
                    <div class="tab-pane fade in active" id="order">
                        {{ $htmlOrder }}
                    </div>
                    <div class="tab-pane fade" id="download">                    
                        {{ $htmlSignin }}
                    </div>
                </div>
			@endif            
        	</div>                	
        </div>
		@endif
	</div>
    @include('frontend.images._modals')
    @include('frontend.images.share-modals')
@endif