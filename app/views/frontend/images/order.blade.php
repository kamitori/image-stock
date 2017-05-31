@if(!empty($arrProduct))
    <form id="order-form" method="post" action="/cart/add" class="form-horizontal">
    <input type="hidden" name="order_image_id" value="{{ $arrImage['image_id'] }}" />
    <input type="hidden" name="order_image_name" value="{{ $arrImage['name'] }}" />
    <input type="hidden" name="path_thumb" value="{{ $arrImage['path_thumb'] }}" />
    <div id="div-products" style="text-align:left; width:320px; height:95px; position:relative; overflow:hidden;">
        <!-- Slides Container -->
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 100%; height: 150px; overflow: hidden;">

        @foreach($arrProduct as $key => $product)

            <div class="col-md-4 customize-control-radio-image">
                <div class="product-title">
                    <a data-toggle="modal" data-target="#modal-{{ $product['sku'] }}">{{ $product['name'] }}</a>
                </div>
                <?php $checked = ($key == 0) ? 'checked' : ''; ?>
                <input id="radio_order_type-{{ $product['sku'] }}" type="radio" name="order_type" value="{{ $product['short_name'] }}_{{ $product['sku'] }}" {{ $checked }} onclick="setOrderType(this.value, {{ $key }})">
                <label for="radio_order_type-{{ $product['sku'] }}">
                    <!--<img src="{{URL}}/assets/images/others/product-FP.jpg">-->
                    <!--<img src="{{URL}}/assets/images/others/product-PO.jpg"> -->
                    <!--<img src="{{URL}}/assets/images/others/product-S.jpg">-->
                    <?php
                        $product_image_path = isset($product['main_image'][0]['path']) ? $product['main_image'][0]['path'] : 'noimage';
                        $product_image_path = str_replace('assets/images/products/', 'assets/images/products/thumbs/', $product_image_path);
                        $product_image_file = $product_image_path;
                        $product_image_file = str_replace('/', DS, $product_image_file);
                    ?>
                    @if(File::exists(public_path().DS.$product_image_file))
                    <img data-origin-src="{{ URL::asset( $product_image_path ) }}" src="{{ URL::asset( $product_image_path ) }}">
                    @else
                    <img data-origin-src="{{ URL::asset( 'assets/images/noimage/247x185.gif' ) }}" src="{{ URL::asset( 'assets/images/noimage/247x185.gif' ) }}" alt=""/>
                    @endif
                </label>
            </div>
        @endforeach
        </div>
        <!-- End slides Container -->
        <!-- bullet navigator container -->

<!--        <div u="navigator" class="jssorb03" style="bottom: 4px; right: 6px;">
            <div u="prototype"><div u="numbertemplate"></div></div>
        </div>
-->
        <!-- Arrow Left -->
        <span u="arrowleft" class="jssora03l" style="top: 123px; left: 8px;">
        </span>
        <!-- Arrow Right -->
        <span u="arrowright" class="jssora03r" style="top: 123px; right: 8px;">
        </span>
    </div>

    <div class="row" style="padding-left:30px; padding-right:35px; text-align:left">
        <div id="div-choose-order">
            <!--load choose order here-->
        </div>

        <div class="form-group" style="width:100px;">
        	<div style="display:inline;">
                <label for="order_qty" class="control-label">Quantity</label>
                <input type="number" name="order_qty" id="order_qty" value="1" min="1" class="form-control" onblur="changeQuantity(this.value)" onclick="this.focus()" />
			</div>
            <div class="display-price">
            	<h3>$<span id="display_price" class="number"></span></h3>
            </div>
        </div>
        <input type="hidden" name="old_qty" id="old_qty" value="1" />
        <input type="hidden" name="price" id="price" value="0" />
        <input type="hidden" name="sell_price" id="sell_price" value="0" />

        <div class="form-group">
            <div style="width:170px; float:left">
                <a href="/cart" style="text-decoration:none">
                    <button id="add-to-card-btn" type="button" class="btn btn-success btn-block">Add to cart</button>
                </a>
                <p><small><a data-toggle="modal" data-target="#guarantee-modal" style="cursor:pointer">100% money back guarantee</a></small></p>
            </div>
            <div style="float:left; margin-left:35px">
                <a href="/design-{{ $arrImage['image_id'] }}/{{ $arrImage['short_name'] }}.html" style="text-decoration:none">
                    <button type="button" class="btn btn-default btn-block">Quick Design</button>
                </a>
                <p>&nbsp;</p>
            </div>
            
        </div>
    </div>
    </form>
    @include('frontend.order._modals')
    @include('frontend.order.choose-order')
@endif
