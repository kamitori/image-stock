{{-- View Image On Wall --}}
<form id="order-form-preview" method="post" action="/cart/add">
    <input type="hidden" name="order_image_id" value="{{ $imageObj['image_id'] }}" />
    <input type="hidden" name="order_image_name" value="{{ $imageObj['name'] }}" />
    <input type="hidden" name="path_thumb" value="{{ $imageObj['path_thumb'] }}" />
	<input type="file" style="display:none" id="background-upload" />
    <div class="modal fade" id="modal-image-on-wall">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="font-size:30px;">
                  <span style="color:#fff; background-color:#000;">&times;</span>
                </button>
                <div id="modal-title" class="modal-title text-center"><a title="Change background" onclick="$('#background-upload').click();"><i class="fa fa-upload" style="font-size:24px;"></i></a></div>
          </div>
          <div class="modal-body">
                <div id="background-effect" class="container text-center pull-left">
                	<div id="user-background">
                    @if(!empty($system_backgrounds))
                        @foreach($system_backgrounds as $bg)
                            <div style="margin-bottom:15px;">
                                <div class="backgroundCategory" onclick="changeBackgound(this)">
                                    <img src="{{ $bg }}" class="paletteBgThumbnail" />
                                </div>
                            </div>                    
                        @endforeach                    
                    @else
                    	<div style="margin-bottom:15px;">
                            <div class="backgroundCategory active" onclick="changeBackgound(this)">
                                <img src="{{URL}}/assets/images/others/cream-living-room.jpg" class="paletteBgThumbnail" />
                            </div>
                        </div>                    
                    @endif
                    @foreach($user_backgrounds as $bg)
                    	<div style="margin-bottom:15px;">
                            <div class="backgroundCategory" onclick="changeBackgound(this)">
                                <img src="{{ $bg }}" class="paletteBgThumbnail" />
                            </div>
                            <div style="float:right;"><a class="glyphicon glyphicon-remove" title="Remove this item" style="text-decoration:none; font-size:70%;" onclick="removeBackground(this)"></a></div>
                        </div>
                    @endforeach
                    </div>
                	<div id="div-waiting-remove"></div>
                </div>
                <div class="background-button">
                		<a class="fa fa-chevron-left" style="text-decoration:none;" onclick="slideEffect()"></a>
                </div>
                <div id="draggable" class="main-preview">
                    <div id="main-image-preview">
                        <div id="main-image-cover-preview" class="framed">
                            <div id="main-image-mat-preview" class="mat">
                                <img id="main-image-display-preview" src="{{URL}}{{ $imageObj['path'] }}" class="framed"/>
                            </div>
                        </div>
                    </div>
                </div>  
          </div>
          @if(!empty($arrProduct))
              <div class="modal-footer">
                <div class="col-md-2" style="text-align:left;">                
                @foreach($arrProduct as $key => $product)
                    <div class="radio-image-preview">
                        <?php $checked = ($key == 0) ? 'checked' : ''; ?>
                        <input id="radio_order_type-{{ $product['sku'] }}-preview" type="radio" name="order_type" value="{{ $product['short_name'] }}_{{ $product['sku'] }}" {{ $checked }} onclick="setOrderType(this.value, {{ $key }}, '-preview')">
                        <label for="radio_order_type-{{ $product['sku'] }}-preview">                
                            <div class="product-title">{{ $product['name'] }}</div>
                        </label>
                    </div>
				@endforeach
                </div>
                <div id="div-choose-order-preview" class="col-md-7" style="text-align:left;"><!--choose order here--></div>

                <div class="col-md-1" style="width:10%; padding-left:-15px;">
                    <label for="order_qty" class="control-label" style="font-weight:500">Quantity</label>            
                    <input type="number" name="order_qty" id="order_qty-preview" value="1" min="1" class="form-control" onblur="changeQuantity(this.value, '-preview')" onclick="this.focus()" />
                </div>

                <input type="hidden" name="old_qty" id="old_qty-preview" value="1" />
                <input type="hidden" name="price" id="price-preview" value="0" />
                <input type="hidden" name="sell_price" id="sell_price-preview" value="0" />                				       
                <div class="col-md-2" style="width:15%">
                    <h3 style="margin-top:0px;">$<span id="display_price-preview" class="number"></span></h3>
                    <button id="add-to-card-btn-preview" type="submit" class="btn btn-success btn-block" style="width:90%; float:right;">Add to cart</button>

                </div>                
                   
              </div>          
          @endif    
        </div>
      </div>
    </div>
</form>
@include('frontend.order.choose-order-preview')