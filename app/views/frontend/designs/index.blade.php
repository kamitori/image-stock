@section('pageCSS')
<link href="{{URL}}/assets/designonline/css/font.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/designonline/css/iconfont.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/designonline/css/design.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/designonline/css/stylesheet.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/designonline/css/slide.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/designonline/css/style2.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="{{URL}}/assets/designonline/css/jquery-ui.min.css">
<style type="text/css">
#preview_content:hover {
cursor: url(/assets/designonline/images/openhand.cur), auto;
}
#preview_content:active {
cursor: url(/assets/designonline/images/closedhand.cur), auto;
}
#dlg-container{
position: absolute;
z-index: 9999;
width: 100%;
top:0%;
height: 100%;
display: none;
margin:0 auto;
}
.cursor_zoomin {
cursor : url(/assets/designonline/images/cursors/icon-tool-zoom.png) !important;
}
#svg-main .main-image {
	cursor: move;
}
#svg-main .shape-path.active{
	stroke: #CA7642;
	stroke-width: 2px;
}
#svg-main.preview .group-bleed  {
	display: block !important;
}
#svg-main.preview .group-bleed .bleed {
	fill: #fff !important;
	fill-opacity: 1 !important;
	stroke: #fff !important;
  	stroke-width: 3px !important;
}

#svg-main.preview .group-mirror-bleed{
	display: none !important;
}
#svg-main.preview-bg .group-bleed .bleed {
	display: none !important;
}
#svg-preview {
	cursor: default !important;
}

#paletteContentProducts a .bundle  {
	width:90%;
}
#paletteContentProducts a.active .bundle  {
	border: #428bca solid 1px;
}
table { font-size: 12px; background: white; margin-bottom: 1.66667em; border: solid 1px #dddddd; }
table thead, table tfoot { background: whitesmoke; font-weight: bold; }
table thead tr th, table thead tr td, table tfoot tr th, table tfoot tr td { padding: 0.66667em 0.83333em 0.83333em; font-size: 1.16667em; color: #222222; text-align: left; }
table tr th, table tr td { padding: 0.75em 0.83333em; /*font-size: 1.16667em;*/font-size: 1em; color: #222222; }
table tr.even, table tr.alt, table tr:nth-of-type(even) { background: #f9f9f9; }
table thead tr th, table tfoot tr th, table tbody tr td, table tr td, table tfoot tr td { display: table-cell; /*line-height: 1.5em;*/line-height: 1em; }
input[type="text"]{
	font-size: 1em;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	-ms-border-radius: 2px;
	-o-border-radius: 2px;
	border-radius: 2px;
	background: #ffffff;
	color: #444444;
	border: 1px solid #ebebeb;
	letter-spacing: 0.8px;
}

#list_album{
  height: 185px;
  overflow-y: scroll;
  overflow-x: hidden;
  border: 1px solid #ddd;
}
#list_album .block_album,
#list_image .block_album{
  margin-top: 1.25em;
  height: 155px;
  float: left;
}
#list_album .block_image,
#list_image .block_image{
  display: table-cell;
  line-height: 130px;
  width:130px;
  text-align: center;
  vertical-align: middle;

}
#list_album .block_image:hover > img,
#list_image .block_image:hover > img{
  border:none;
  box-shadow: 0px 0px 2px 3px #428bca;
}
#list_album .block_image img,
#list_image .block_image img,
#list_image .block_album .block_name{
  margin: auto;
  text-align: center;
  cursor: pointer;
  max-height: 130px;
  /*min-height:130px;*/
  max-width: 90%;
  min-width: 50%;
  margin-bottom: 5px;
}
#list_image .block_album:hover > .block_name{
  font-size: 115%;
  border:none;
  box-shadow: 0px 0px 2px 3px #428bca;
-webkit-transition: all 300ms cubic-bezier(0.420, 0.000, 0.580, 1.000);
   -moz-transition: all 300ms cubic-bezier(0.420, 0.000, 0.580, 1.000);
     -o-transition: all 300ms cubic-bezier(0.420, 0.000, 0.580, 1.000);
        transition: all 300ms cubic-bezier(0.420, 0.000, 0.580, 1.000); /* ease-in-out */

-webkit-transition-timing-function: cubic-bezier(0.420, 0.000, 0.580, 1.000);
   -moz-transition-timing-function: cubic-bezier(0.420, 0.000, 0.580, 1.000);
     -o-transition-timing-function: cubic-bezier(0.420, 0.000, 0.580, 1.000);
        transition-timing-function: cubic-bezier(0.420, 0.000, 0.580, 1.000); /* ease-in-out */
}

#list_album .block_name{
  height:20px;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  text-align: center;

}

#slider_image div.clearall {
	float: left;
    height: 20px;
    margin-left: 20px;
    margin-top: 25px;
    width: 100px;
	/*position:absolute;*/
}
#slider_image .image_content:hover > img{
  border:none;
  box-shadow: 0px 0px 2px 3px #428bca;
}


#paletteLabels .paletteLabel1 {
    background-color: #f0f0f0;
    border-radius: 4px 0 0 4px;
    color: #666;
    cursor: pointer;
    display: inline-block;
    font-family: "Avenir LT W01 55 Roman",Verdana,Arial,sans-serif;
    font-size: 14px;
    margin: 0;
    overflow: hidden;
    padding: 0;
    position: relative;
    width: 35px;
}
#paletteLabels .paletteLabel1 div.noIe8 {
    transform: rotate(270deg);
    transform-origin: center center 0;
}
#paletteLabels .paletteLabel1 div {
    display: inline-block;
    margin: 0;
    padding: 0;
    position: absolute;
}

.name {
    overflow: hidden;
    padding-left: 10px;
    padding-right: 10px;
    position: relative;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
	text-align:center;
}

/*ui-dialog*/
.ui-dialog > .ui-widget-header {
	/*background: #DFDFDF;	*/
	border: 1px solid #EBEBEB;
	color: #ccc;
	background: url("{{URL}}/assets/designonline/images/dtool_header2.png") repeat-x;
	background-color: transparent;	
}

#user-background div.background {
	margin-top:15px; 
	width:70%; 
	display:inline-block;
}
#user-background div.background .backgroundCategory{
	width:90%; 
	margin-top:0; 
	float:left;	
}

</style>
@stop
<div id="loading_wait" style="width:160px; margin:68px 0 0 1000px; position:absolute;display:none;float:right;">
	<img src="{{URL}}/assets/designonline/images/ajax-loader.gif" alt="title" />
	<span> Loading ...</span>
</div>
<div id="docBare" class="null cf">
	<article id="bundleBody" class="cp designawall">
		<section id="navBarContainer">
        	<div style="padding:15px">
            	<a class="fa fa-reply" href="{{URL}}/pic-{{$image_obj['id']}}/{{$image_obj['short_name']}}.html" >&nbsp;Back to Image Page</a>
            </div>
			<div id="actionContainer" style="top: 6px; padding:10px">

<!--				<span>Wrap type: </span>
				<span id="name_wrap" style="font-weight:bold;color:red;">
				</span>
-->
				<span>Total Price: $</span><span id="display_price" style="font-weight:bold;color:red;"> 0.00 </span>
				<span id="divAddToCart">                	
                    <button id="addToCartLink" class="btn btn-success btn-xs" type="button">{{ isset($product['cart_id']) ? 'Update' : 'Add to'}} Cart</button>
                </span>
                
                
                <a id="returnToCartLink" class="primaryButton hidden">Return to Cart</a>

                <input type="hidden" name="old_qty" id="old_qty" value="1" />
                <input type="hidden" name="price" id="price" value="0" />
                <input type="hidden" name="sell_price" id="sell_price" value="0" />
                <input type="hidden" name="order_image_id" id="order_image_id" value="{{$image_obj['id']}}" />
                <input type="hidden" name="order_image_name" id="order_image_name" value="{{$image_obj['name']}}" />
                <input type="hidden" name="path_thumb" id="path_thumb" value="{{$image_obj['path_thumb']}}" />
                
			</div>
		</section>
		<section id="contentContainer" style="min-height:750px;">
			<div id="paletteContainer">
				<div id="paletteContent" style="padding-left:0;">
					<div class="headerLine"></div>
					<div id="paletteContentImages"  class="paletteContent viSTPWide">
						<div class="large-3 columns" >
							<img id="import_vi" src="/assets/designonline/images/social_icon/button-vi.png" style="width:100%;" alt="import_vi" title="From VI library" />
						</div>
						<div id="loading_none" style="display:none;">
							<img src="{{URL}}/assets/designonline/images/loading.gif" alt="title" />Loading ...
						</div>
						<div id="loading_import" style="display:none;margin-top:20px;">
							<img src="{{URL}}/assets/designonline/images/loading.gif" alt="title" />Loading ...
						</div>
					</div>
					<div id="paletteContentProducts" class="paletteContent active">
						@foreach($products as $p)
						<a href="javascript:void(0)" data-id="{{ $p['short_name'] }}" >
                        	<input type="hidden" name="order_type" value="{{ $p['short_name'].'_'.$p['sku'] }}" />
							<div class="bundle" style="margin-top:0px;">
								<div>
                                	<img src="{{URL}}/{{$p['main_image']}}" />
								</div>
								<label class="bundlename">{{ $p['name'] }}</label>
							</div>
						</a>
						@endforeach
					</div>
					<div id="paletteContentSizes" class="paletteContent">
						<table class="full_width">
                            <thead>
                                <tr>
                                    <td align="left" colspan="2">Size (inch)</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">
                                        Width
                                    </td>
                                    <td align="left">
                                    	 <input type="text" class="sizes form-control text-right" name="width" value="12" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                    	Height
                                    </td>
                                    <td align="left">
                                        <input type="text" class="sizes form-control text-right" name="height" value="12" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                    	Quantity
                                    </td>
                                    <td align="left">
                                        <input type="text" class="form-control text-right" name="quantity" id="quantity" value="1" style="width:100%" >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
					</div>
					@foreach($products as $p)
						@foreach($p['option_groups'] as $group)
							<div id="paletteContent{{ $p['short_name'].'-'.$group['key'] }}"  class="paletteContent" style="">
								@foreach($group['options'] as $option)
								<div class="step3-options " style="display: block;">
									<label for="{{ $p['short_name'].'-'.$group['key'].'-'.$option['key'] }}">
										<ul>
											<li class="col1">
												<input type="radio" id="{{ $p['short_name'].'-'.$group['key'].'-'.$option['key'] }}" name="{{ $group['key'] }}" value="{{ $option['key'] }}">
												<span><b>{{ $option['name'] }}</b></span>
											</li>
											<li class="col2">
												<div class="float-L thumb-img item_{{ $option['key'] }}"></div>
											</li>
										</ul>
									</label>
								</div>
								@endforeach
							</div>
						@endforeach
					@endforeach
					<div id="dialog_resolution" title="Image's Resolution" style="display:none;width:70%;"></div>
					<div id="paletteContentFilters"  class="paletteContent">
						@foreach(['original' => 'Original', 'sepia' => 'Sepia', 'grayscale' => 'Grayscale'] as $key => $filter)
						<div class="step3-options " style="display: block;">
							<label for="opfilter_{{ $key }}">
								<ul>
									<li class="col1">
										<input type="radio" id="opfilter_{{ $key }}" name="filter_type" value="{{ $key }}" onclick="Design.filter('{{ $key }}');">
										<span><b>{{ $filter }}</b></span>
									</li>
									<li class="col2">
										<div class="float-L thumb-img item_{{ $key }}"></div>
									</li>
								</ul>
							</label>
						</div>
						@endforeach
					</div>
					<div id="paletteContentBackgrounds" class="paletteContent text-center" style="padding-top: 15px;">
                        <div>
                            <button class="btn btn-outline btn-info btn-xs" type="button" onclick="$('#background-upload').click();" style="font-size:85%">Add backgrounds</button>
                            <input type="file" style="display:none" id="background-upload" />
                            <button class="btn btn-outline btn-default btn-xs" type="button" onclick="Main.preview(false);" style="font-size:85%">Close</button>
                        </div>
                    
						@foreach($systemBackgrounds as $bg)
						<div class="backgroundCategory" id="background-gray" style="width:100%; height: auto !important;" onclick="Main.changeBackgound(this)">
							<div class="assetCategoryLabel"></div>
							<img src="{{ $bg }}" class="paletteBgThumbnail" style="width:70%; height: auto;" />
						</div>
						@endforeach

						<div id="user-background" class="text-center" style="position:relative; width:100%">
							@foreach($userBackgrounds as $bg)
                            <div class="background">
                                <div class="backgroundCategory" onclick="Main.changeBackgound(this)">
                                    <div class="assetCategoryLabel"></div>
                                    <img src="{{ $bg }}" class="paletteBgThumbnail" style="width:100%; height: auto;" />
                                </div>
                                <div style="float:right;"><a class="glyphicon glyphicon-remove" title="Remove this item" style="text-decoration:none; font-size:70%; cursor:pointer;" onclick="Main.removeBackground(this)"></a></div>
							</div>
							@endforeach
						</div>
                        <div id="div-waiting-remove" style="margin-top:30px"></div>
					</div>
					<!-- Pick color -->
					<div id="pick_color" class="paletteContent" style="padding-top: 15px;">
						<button type="button" class="close_picker" onclick="ColorPicker.close()">×</button>
						<div class="ChooseColor">
							<div>Create your own colour</div>
							<div id="pickcolorbox">
								<div class="picker">
								    <div class="picker-colors">
								        <div class="picker-colorPicker"></div>
								    </div>
								    <div class="picker-hues">
								        <div class="picker-huePicker"></div>
								    </div>

								    <div class="picker_color_rgb">
								        <div align="center" class="picker_t_rgb">
								            <span>R</span><br>
								            <input type="text" value="" id="colorR" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_rgb">
								            <span>G</span><br>
								            <input type="text" value="" id="colorG" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_rgb">
								            <span>B</span><br>
								            <input type="text" value="" id="colorB" maxlength="3">
								        </div>
								    </div>

								    <div class="picker_color_hsv">
								        <div align="center" class="picker_t_hsv">
								            <span>H</span><br>
								            <input type="text" value="" id="colorH" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_hsv">
								            <span>S</span><br>
								            <input type="text" value="" id="colorS" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_hsv">
								            <span>V</span><br>
								            <input type="text" value="" id="colorV" maxlength="3">
								        </div>
								    </div>

								    <div class="picker_color_cmyk">
								        <div align="center" class="picker_t_1">
								            <span>C</span><br>
								            <input type="text" value="" id="colorC" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>M</span><br>
								            <input type="text" value="" id="colorM" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>Y</span><br>
								            <input type="text" value="" id="colorY" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>K</span><br>
								            <input type="text" value="" id="colorK" maxlength="3">
								        </div>
								    </div>

								</div>
							</div>
							<div id="divPMS">
								<div id="Matchingto">
									<div class="choiced_color_box">
										<div class="choiced_color_img picker_h" style="background-color:#ffffff;" id="colorbg"></div>
										<div class="choiced_color_text" style="width: 150px;">HEX: <input type="text" value="121212" id="colorhex" maxlength="6" style="width: 100px;" /> </div>
									</div>
								</div>
							</div>
							<div class="choiced_color_box" style="border:none;">
								<button id="btnChooseColorFromImg" type="button" class="btn btn-default cf-btn-colorPicker">
								<span class="cf-btn-colorPicker-icon"></span>
								<span class="cf-btn-colorPicker-text LocalizedStrings" data-localizedstringname="WrapColorPickerButton">Choose a colour<br>from your photo...</span>
								</button>
							</div>
							<div class="choiced_color_box" style="border:none;">
								<button id="btnChooseColor" type="button" class="btn btn-default cf-btn-colorPicker" onclick="ColorPicker.close()">
								<span>Choose Color</span>
								</button>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div id="paletteLabels">
				<div id="paletteLabelImages" data-target="#myImagesModal" data-toggle="modal" data-label-for="paletteContentImages" class="paletteLabel1" style="height: 110px;">
					<div style="width: 100px; left:-34px; top: 29px;" class="noIe8 lblUploads">Images</div>
				</div>
				<div id="paletteLabelProducts" data-label-for="paletteContentProducts" class="paletteLabel active" style="height: 110px;">
					<div style="width: 100px; left:-34px; top: 29px;" class="noIe8 lblUploads">Products</div>
				</div>
				<div id="paletteLabelSizes" data-label-for="paletteContentSizes" class="paletteLabel" style="height: 80px;">
					<div style="width: 100px; left:-34px; top: 2px;" class="noIe8 lblUploads">Sizes</div>
				</div>
				@foreach( $products as $p )
				<div id="tab-{{ $p['short_name'] }}" class="option-tabs" style="display: none;">
					@foreach($p['option_groups'] as $group)
					<div id="paletteLabel{{ $p['short_name'].'-'.$group['key'] }}" data-label-for="paletteContent{{ $p['short_name'].'-'.$group['key'] }}" class="paletteLabel" style="height: 110px;">
						<div style="width: 100px; left:-34px; top: 29px;" class="noIe8 lblUploads">{{ $group['name'] }}</div>
					</div>
					@endforeach
				</div>
				@endforeach
				<div id="paletteLabelFilters" data-label-for="paletteContentFilters" class="paletteLabel" style="height: 85px;">
					<div style="width: 100px; left:-34px; top: 6px;" class="noIe8 lblFilters">Filters</div>
				</div>
				<div id="paletteLabelLayouts" data-label-for="paletteContentLayouts" class="paletteLabel " style="height: 84px;display:none">
					<div style="width: 54px; left: -14px; top: 34px;" class="noIe8 lblLayouts">Layouts</div>
				</div>
				<div id="paletteLabelBackgrounds" data-label-for="paletteContentBackgrounds" class="paletteLabel " style="height: 120px;display:none">
					<div style="width: 90px; left: -32px; top: 52px;" class="noIe8 lblBackgrounds">Backgrounds</div>
				</div>
			</div>
			<div id="editPageContainer" class="svg edit" style="width: 75%;min-height:100px;">
				<div id="editAreaToolBar" style="width: 100%;">
					<div class=" slider_bt" style="position: absolute; margin: 100px 1px 1px 0px; display: block; z-index:5;background:transparent;">
						<p style="width: 37px;font-family:verdana;">Rotate</p>
						<input type="text" id="amount" style="width: 36px;color:#f6931f;font-weight:bold;">
						<div id="slider-vertical" style="  margin: -1px 4px 4px 13px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
							<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div>
							<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a>
						</div>
					</div>
					<div class=" slider_bt" style="position: absolute;   margin: 300px 1px 1px 0px; display: block;">
						<p style="width:45px;font-family:verdana;background:transparent;">Zoom</p>
						<div id="zoom-slider" style="margin: 0px 4px 4px 16px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
							<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div>
							<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a>
						</div>
					</div>
					<div id="image_bt" class="ds_tool_group ds_border_right" style="">
						<div id="dsbt_filter" class="ds_button dsbt">
							<div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-filter"></i></div>
							<div class="ds_button_name">Filter</div>
						</div>
						<div class="ds_button" onclick="Design.rotate()">
							<div class="ds_button_icon"><i class="fa fa-fw fa-repeat"></i></div>
							<div class="ds_button_name">Rotate Image</div>
						</div>
						<div class="ds_button" onclick="Design.flipX()">
							<div class="ds_button_icon"><i class="fa fa-fw fa-sort2"></i></div>
							<div class="ds_button_name">Flip X</div>
						</div>
						<div class="ds_button" onclick="Design.flipY()">
							<div class="ds_button_icon"><i class="fa fa-fw fa-sort"></i></div>
							<div class="ds_button_name">Flip Y</div>
						</div>
						<div class="ds_button" onclick="Order.resolution()">
							<div class="ds_button_icon"><i class="fa fa-fw fa-flag"></i></div>
							<div class="ds_button_name">Resolution</div>
						</div>
					</div>
					<div id="preview_bt" class="ds_tool_group ds_border_left right" style="display:block">
						<div class="ds_button" onclick="Main.previewBG()">
							<div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
							<div class="ds_button_name">Preview All</div>
						</div>
						<div class="ds_button" onclick="Main.preview3D()">
							<div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-cube"></i></div>
							<div class="ds_button_name">Preview 3D</div>
						</div>
						<div class="ds_button" onclick="Main.preview()">
							<div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
							<div class="ds_button_name" title="Preview with background">Preview</div>
						</div>
						<input type="text" id="img-link" style="display:none" value="">
					</div>
					<div id="zoom_bt" class="ds_tool_group ds_border_left right" style="display:block">
						<div class="ds_button" id="reset_zoom" onclick="Design.resetZoom()" style="display:none">
							<div class="ds_button_icon"><img src="{{URL}}/assets/designonline/images/zoom-reset.png" style="max-width:16px;" /></div>
							<div class="ds_button_name">Reset Zoom</div>
						</div>
						<div class="ds_button" onclick="Design.zoomInAll()">
							<div class="ds_button_icon"><span class="glyph zoom-in"></span></div>
							<div class="ds_button_name">Zoom In all</div>
						</div>
						<div class="ds_button" onclick="Design.zoomOutAll()">
							<div class="ds_button_icon"><span class="glyph zoom-out"></span></div>
							<div class="ds_button_name">Zoom Out all</div>
						</div>
					</div>
					<div id="zoom_bt2" class="ds_tool_group ds_border_left right" style="display:none">
						<div class="ds_button" onclick="Main.zoomInPreview()">
							<div class="ds_button_icon"><span class="glyph zoom-in"></span></div>
							<div class="ds_button_name">Zoom in</div>
						</div>
						<div class="ds_button" onclick="Main.zoomOutPreview()">
							<div class="ds_button_icon"><span class="glyph zoom-out"></span></div>
							<div class="ds_button_name">Zoom out</div>
						</div>
					</div>
				</div>
				<div id="editAreaWorkArea" class="content" style="min-height:400px;max-height:500px;overflow:auto;">
					<div class="canvas_img_thum" style="display:none;height: 100%; width: 100%; padding:0 2% 0 2%;">
						<canvas id="canvas_imgs"></canvas>
					</div>
					<div id="svg_div" style="height: 100%; width: 100%; padding:0 2% 0 2%"></div>
				</div>
				<div id="preview_box" style="display:none; padding:0; overflow: auto">
					<img id="loading-image" src="{{URL}}/assets/designonline/images/loading.gif" alt="title" style="max-height:500px;margin-top: 50px;" />
					<div id="preview_content" style="margin:0;padding:10px;border:0px; cursor: pointer;">
					</div>
				</div>
				<div id="tmp_svg" style="display:none; padding:0;/*width:800px;height:400px;position: absolute;background: white;top: 0;left: 0;z-index: 500;*/">
				</div>
			</div>
		</section>
		<section id="picturestripContainer">
			<div id="picturestrip">
				<div class="picturestripBackground">
					<div id="slider_image">
						@foreach($userImages as $image)
						<div class="image_content">
							<img class="photo" src="{{URL}}/{{ $image['path'] }}" alt="" data-link="{{URL}}/{{ $image['path'] }}" data-id="{{ $image['id'] }}" data-name="{{ $image['name'] }}" data-path_thumb="{{ $image['path_thumb'] }}" onclick="Design.changeImage(this);">
                            <div class="name"><a href="{{URL}}/pic-{{ $image['id'] }}/{{ $image['short_name'] }}.html" title="Go to {{ $image['name'] }}">{{ $image['name'] }}</a></div>
                            
						</div>
						@endforeach
                        <div class="clearall"><a href="/design/clear-image-store/{{ $image_obj['id'] }}/{{ $image_obj['short_name'] }}" title="Clear All" style="cursor:pointer;"><i class="glyphicon glyphicon-remove"></i>&nbsp;Clear All</a></div>
					</div>
                    
				</div>
			</div>
		</section>
		<div id="dynamicColorOptionsWrapper" class="dynamicColorPopup invisible"></div>
		<div id="dynamicColorTooltip" class="dynamicColorPopup invisible">
			<div class="arrow left"></div>
			<span id="message"><span class="title">Custom Color Palette</span><br>Click a color swatch to choose your own color.</span><span class="close"></span>
		</div>
	</article>
</div>
<div style="display:none">
	<canvas id="main-canvas"></canvas>
	<div id="canvas-collection"></div>
</div>

<div aria-hidden="true" aria-labelledby="myImagesModalLabel" role="dialog" tabindex="-1" id="myImagesModal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width:75%;">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myImagesModalLabel" class="modal-title">Select images</h4>
            </div>
            <div class="modal-body" style="overflow-y:auto">
                
                <div id="modal-search" class="container" style="display:none">
                	<div class="row">
                        <div class="lib_box_search col-md-6">
                            <form id="searchByTag" method="post" action="javascript:void(0)">
                            	<div class="row">
                                    <div class="col-md-10">
                                        <input name="searchlib_text" id="searchlib_text" type="text" class="form-control" placeholder="Input keyword" autocomplete="off" />
                                    </div>
                                    <div class="col-md-2">
                                        <button id="searchlib_bt" type="submit" class="btn btn-outline btn-default">Search</button>
                                    </div>                                
                                </div>
                            </form>
                            
                        </div>
                        <div class="col-md-6" style="margin-top:15px">
                            <button class="btn btn-outline btn-primary" onclick="Main.chooseImages()" style="float:right">Choice Images</button>
                        </div>
					</div>                    
                </div>
                <div id="list_album" class="of_album">
                </div>
                <div class="container">
                    <div id="list_image" class="col-lg-12 col-lg-offset-0">
                    </div>                
                </div>
                
            </div>
            <div class="modal-footer">
                <ul class="pagination" style="margin-top:0; float:left;"></ul>
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>   

@section('pageJS')
<script src="{{ URL }}/assets/designonline/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{URL}}/assets/designonline/js/jquery.filer.min.js"></script>
<script src="{{URL}}/assets/designonline/js/svgjs/svg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/svgjs/svg.draggable/svg.draggable.js" type="text/javascript"></script>
<script src="{{URL}}/assets/designonline/js/svgjs/svg.filter/svg.filter.min.js" type="text/javascript"></script>
<script src="{{URL}}/assets/designonline/js/pms.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/rgbcolor.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/StackBlur.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/canvg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/canvas3d/three.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/canvas3d/requestAnimFrame.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/canvas3d/OrbitControls.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/designonline/js/canvas3d/Detector.js" type="text/javascript" charset="utf-8"></script>
@include('frontend.designs.js.main')
@include('frontend.designs.js.design')
@include('frontend.designs.js.pointer')
@include('frontend.designs.js.color_picker')
@include('frontend.designs.js.preview_3d')
@include('frontend.designs.js.script')
<script type="text/javascript">
	Design.setDefaultImage('{{ $image_path }}');
	Main.bind();
	ColorPicker.bind();
	//Order.caculatePrice();
	
	$('#myImagesModal').on('shown.bs.modal', function() {
		//console.log(availableKeywords);
//		$( "#searchlib_text" ).autocomplete({
//			source: availableKeywords,			
//		});
		
	});
	
</script>
@stop