@section('pageCSS')
<style>
	a.button {
		text-decoration:none;
	}

	ul.list_image li {
		width:16%;
		margin-top:5px;
		margin-bottom:5px;
	}
	ul.list_image li a .div_image{
		/*width:115px;*/
		/*height:115px;*/
		height:95px;
		margin: 0 5px;
		overflow: hidden;
	}
	ul.list_image li a .div-image-name{
		width:110px;
		white-space: nowrap;
  		overflow: hidden;
 		text-overflow: ellipsis;
 		text-decoration: none;
 		color: #888;
	}
	.div-image-name:hover{
		text-decoration: none!important;
	}
	.tooltip.in {
		opacity: 1 !important;
	}
	.tooltip-arrow{
		border-bottom-color: #e7e7e7 !important;
	}
	.tooltip-inner{
		background: #e7e7e7 !important;
		max-width: 100% !important;
		padding: 3px !important;
	}
	table.size-list {
		width: 100%;
	}
	table.size-list td {
		width: 10%;
	}
	table.size-list td:first-child {
		width: 5%;
	}
	.close_popover{
		position: absolute;
		top:5px;
		right:5px;
		cursor: pointer;
		padding: 3px;
	}
	.close_popover:hover{
		background: #ddd;
	}
	.popover_lightbox{
		min-width: 250px;
	}

	/*For radio image*/
	div.customize-control-radio-image {
		padding:0px;
	}

	.customize-control-radio-image div.product-title {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		font-size:75%;
		padding-bottom:3px;
	}

	.customize-control-radio-image a {
		cursor:pointer;
	}

	.customize-control-radio-image label {
		box-sizing: border-box;
		max-width:  110%;
		height:     auto;
		padding:    1px;
		border:     4px solid transparent;
		cursor:pointer;
	}

    .customize-control-radio-image label:hover,
    .customize-control-radio-image label:focus {
        border-color: #ddd;
    }

	.customize-control-radio-image input{ /* HIDE RADIO */
	  visibility: hidden; /* Makes input not-clickable */
	  position: absolute; /* Remove input from document flow */
	}
	.customize-control-radio-image input[type="radio"]:checked + label {
		border:4px solid #F80;
	}

	.customize-control-radio-image label img {
		width:95px;
		height:65px;
	}

	a.morelink {
		text-decoration:none;
		outline: none;
	}
	.morecontent span {
		display: none;
	}
	@if($mod_download==0 || $mod_order==0)
	.on_off_tab{
		display: none;
	}
	.tab-content{
		margin-left: -17px;
	}
	@endif

	div.display-price {
		display:inline;
		margin-left:120px;
		margin-top:-50px;
		position:absolute;
	}

	#main-image img {
		width:100%;
		/*max-height:300px;*/
	}

	/*Image Effect*/
	/*Framed*/
	#main-image div.framed {
		border: 15px #000 solid;
		padding: 0%;
		box-shadow: 10px 10px 15px #ccc;
		background-color: #EFEFEF;
		margin: 15px 15px 15px 0px;
		text-align:center;
	}
	#main-image div.framed img.framed{
		border: 3px #DADADA solid;
		border-top-color:#fff;
		border-bottom-color:#999999;
	}
	/*Photo Print*/
	#main-image div.photo {
		padding: 5%;
	}
	#main-image img.photo{
		border: 1px #fff;
		box-shadow: 10px 10px 15px #ccc;
	}
	/*Canvas*/
	#main-image div.canvas {
		padding: 5%;
	}
	#main-image img.canvas{
		border-right: 3px #DADADA solid;
		border-bottom: 3px #999999 solid;
		border-left: 1px #999999 solid;
		border-top: 3px #fff solid;
		box-shadow: 10px 10px 15px #ccc;
	}
	/*Mat*/
	#main-image div.framed div.mat {
		padding: 10%;
	}

	/*For View Image on Wall*/
	#modal-image-on-wall div.modal-dialog {
		width:100%;
		height:100%;
		margin:0;
		position:fixed;
	}
	#modal-image-on-wall div.modal-content {
		border:none;
		border-radius:0px !important;
		width:100vw;
		height:100vh;
		background-image:url("{{URL}}/assets/images/others/cream-living-room.jpg");
		background-repeat:repeat;
		/*background-size:contain;*/
		background-position:center;
	}
	#modal-image-on-wall div.modal-header {
		border:none;
		height:5vh;
	}
	#modal-image-on-wall div.modal-header .modal-title {
		margin-left: -10px;
		margin-top: -10px;
		width: 2%;
	}
	#modal-image-on-wall div.modal-header .modal-title a {
		cursor:pointer;
		color:#E8E8E8;
	}
	#modal-image-on-wall div.modal-header .modal-title a:hover {
		color: #fff;
	}

	#modal-image-on-wall div.modal-body {
		/*min-height:74.3%;*/
		height:75vh;
		padding:0;
	}
	#modal-image-on-wall div.modal-footer {
		background-color:#fff;
		height:20vh;
		overflow-y:auto;
		padding-right:1%;
	}

	#modal-image-on-wall div.main-preview {
		margin-left:30%;
		/*height:63%;*/
		height:100%;
		width:22%;
		overflow-y:hidden;
		display:flex;
	}

	#modal-image-on-wall a {
		cursor:pointer;
	}

	#main-image-preview {
		width:20%;
		margin-left:15%;
		margin-top:30%;
	}

	#main-image-preview img {
		width:100%;
	}

	#main-image-cover-preview {
		cursor:move;
	}

	/*Framed on Wall*/
	#main-image-preview div.framed {
		border: 5px #000 solid;
		padding: 0%;
		box-shadow: 1px 1px 3px #333;
		background-color: #EEE;
		text-align:center;
	}
	#main-image-preview div.framed img.framed{
		border: 1px #DADADA solid;
		border-top-color:#fff;
		border-bottom-color:#999999;
	}
	/*Photo Print on Wall*/
	#main-image-preview div.photo {
		padding: 5%;
	}
	#main-image-preview img.photo{
		border: 1px #fff;
		box-shadow: 1px 1px 3px #333;
	}
	/*Canvas on Wall*/
	#main-image-preview div.canvas {
		padding: 5%;
	}
	#main-image-preview img.canvas{
		border-right: 1px #DADADA solid;
		border-bottom: 1px #999999 solid;
		border-left: 3px #999999 solid;
		border-top: 3px #fff solid;
		box-shadow: 1px 1px 3px #333;
	}
	/*Mat on Wall*/
	#main-image-preview div.framed div.mat {
		padding: 10%;
	}

	/*For radio image Preview*/
	div.radio-image-preview {
		padding:0px;
	}

	.radio-image-preview div.product-title {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		font-size:smaller;
		padding:3px;
		color:#fff;
		background-color:#ccc;
		opacity:0.8;
	}

	.radio-image-preview a {
		cursor:pointer;
	}

	.radio-image-preview label {
		box-sizing: border-box;
		max-width:  110%;
		height:     auto;
		padding:    1px;
		border:     2px solid transparent;
		cursor:pointer;
	}

    .radio-image-preview label:hover,
    .radio-image-preview label:focus {
        border-color: #ddd;
    }

	.radio-image-preview input{ /* HIDE RADIO */
	  visibility: hidden; /* Makes input not-clickable */
	  position: absolute; /* Remove input from document flow */
	}
	.radio-image-preview input[type="radio"]:checked + label {
		border:2px solid #F80;
	}

	div.display-price-preview {
		display:inline;
	}

	#div-choose-order-preview label {
		font-weight:500;
	}

	.backgroundCategory {
		cursor: pointer;
		border:2px solid transparent;
		margin-bottom: 15px;
		padding:1px;
		box-sizing:border-box;
		width:85%;
		float:left
	}
	.backgroundCategory img{
		width:100%;
	}
	.backgroundCategory:hover {
		border:2px solid #ddd;
	}
	.backgroundCategory.active {
		border:2px solid #F80;
	}

	.background-button {
		width:10px;
		float:left;
		margin-top:15%;
		vertical-align:middle;
		background:#ccc;
		border:1px solid transparent;
		padding:1px;
		box-sizing:border-box;
		opacity:0.7;
	}
	.background-button:hover{
		border:1px solid #ddd;
	}
	.background-button a{
		color:#fff;
		padding-top:25px;
		padding-bottom:25px;
		margin-left:-2px;
	}

	#background-effect {
		width:10%;
		height:100%;
		background:#fff;
		overflow-y:auto;
		padding:10px;
		opacity:0.9;
	}

	/*Slide products*/
	.jssorb03 {
		position: absolute;
	}
	.jssorb03 div, .jssorb03 div:hover, .jssorb03 .av {
		position: absolute;
		/* size of bullet elment */
		width: 21px;
		height: 21px;
		text-align: center;
		line-height: 21px;
		color: white;
		font-size: 12px;
		background: url("{{URL}}/assets/images/others/b03.png") no-repeat;
		overflow: hidden;
		cursor: pointer;
	}
	.jssorb03 div { background-position: -5px -4px; }
	.jssorb03 div:hover, .jssorb03 .av:hover { background-position: -35px -4px; }
	.jssorb03 .av { background-position: -65px -4px; }
	.jssorb03 .dn, .jssorb03 .dn:hover { background-position: -95px -4px; }

	.jssora03l, .jssora03r {
		display: block;
		position: absolute;
		/* size of arrow element */
		width: 55px;
		height: 55px;
		cursor: pointer;
		background: url("{{URL}}/assets/images/others/a03.png") no-repeat;
		overflow: hidden;
	}
	.jssora03l { background-position: -3px -33px; }
	.jssora03r { background-position: -63px -33px; }
	.jssora03l:hover { background-position: -123px -33px; }
	.jssora03r:hover { background-position: -183px -33px; }
	.jssora03l.jssora03ldn { background-position: -243px -33px; }
	.jssora03r.jssora03rdn { background-position: -303px -33px; }
	/*End slide products*/

</style>
@stop

@if (Session::has('message'))
	<div class="alert alert-warning">{{ Session::get('message') }}</div>
@endif
@if (isset($imageObj))
<div>
	<div class='col-md-10 center-content'>
		<input type="hidden" id="img-id" value="{{ $imageObj['image_id'] }}">
		<input type="hidden" id="img-name" value="{{ $imageObj['short_name'] }}">
		<div class="" id="div-main-image">{{ $htmlMainImage }}</div>
		<div class="col-md-12 " style="padding-left:0">
			<div class="row">
				<div class="col-md-6">
					<div class="fb-comments" data-href="{{ $currentURL }}" data-numposts="5"></div>
				</div>
				<div class="col-md-6">
					<div class="col-md-12" id="div-same-artist">{{ $htmlSameArtist }}</div>
					<div class="col-md-12" id="div-similar-images" style="padding-bottom:10px">{{ $htmlSimilarImages }}</div>
				</div>
			</div>
			<div class="row">
            	<!--<div class="container text-left" id="div-view-keywords">{{ $htmlKeywords }}</div>-->
	            <div class="panel panel-default" id="div-view-categories">
	            	<div class="panel-heading hc2 abel bold">View Image by Category</div>
	  				<div class="panel-body category-list">
	                {{ $htmlCategories }}
	                </div>
	            </div>
			</div>
        </div>
	</div>
</div>
<div id="add_light_box" style="display:none;">
	@if(Auth::user()->check())
		<div class="popover_lightbox" id="list_lightbox">
			@foreach($lightboxes as $lightbox)
				<p data-id-lightbox="{{ $lightbox['id'] }}" class="btn btn-default " onclick="addLightBox(this)" style="margin:5px;">{{$lightbox['name']}}</p>
			@endforeach
		</div>
		<div class="clear-fix">
			<input type="text" id="lightbox_name">
			<button type="button" class="btn btn-default" onclick="saveLightBox(this)">Save</button>
		</div>
	@else
		<div class="popover_lightbox">
			<p>Please sign-in</p>
			<p class="small">
				To organize photos in lightboxes you must first register or login. Registration is Free! Lightboxes allow you to categorize groups of photos and send them to your friends or colleagues.
			</p>
			<span class="text-center">
				<a href="{{URL}}/account/sign-in">Sign in</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="{{URL}}/account/create">Create an account</a>
			</span>
		</div>
	@endif
</div>
@else
No Image.
@endif

<div id="fb-root"></div>
@section('pageJS')

<script src="{{URL}}/assets/global/plugins/jssor/jssor.js" type="text/javascript"></script>
<script src="{{URL}}/assets/global/plugins/jssor/jssor.slider.js" type="text/javascript"></script>
@include('frontend.images.js.script')
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=1601264390104375";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
$(document).ready(function() {

	//load Order Type
	setOrderType("{{ $arrProduct[0]['short_name'] }}_{{ $arrProduct[0]['sku'] }}");
	//view on wall
	setOrderType("{{ $arrProduct[0]['short_name'] }}_{{ $arrProduct[0]['sku'] }}", 0, '-preview');

	truncateDescription();


	$('img[data-toggle=tooltip]').tooltip({
		html: true,
		placement: 'auto right',
		container: 'body',
	});

	$( "#modal-image-on-wall #draggable" ).draggable();

	$('#modal-image-on-wall').on('shown.bs.modal', function() {
		slideEffect();
		$('#user-background .backgroundCategory:first').trigger('click');
	})

});
</script>
@stop
