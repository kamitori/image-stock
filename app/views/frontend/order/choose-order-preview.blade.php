@foreach($arrProduct as $key => $product)

    <div id="div-choose-order-preview-{{ $product['sku'] }}" style="display:none">
    	

        <div class="col-md-3">        	
            <label for="img_sizing_{{ $product['sku'] }}" class="control-label">Size</label>    
            <select class="form-control" name="img_sizing" id="img_sizing_{{ $product['sku'] }}" onchange="changeSize('{{ $product['sku'] }}', '-preview')">                 
            </select>
            <input type="hidden" name="custom_size" id="custom_size_{{ $product['sku'] }}" value="{{ $product['custom_size'] }}" />
        </div>
        
        @foreach($product['option_groups'] as $group)
        <div class="col-md-3" style="text-align:left;">
            <label for="option_{{ $product['sku'] }}_{{ $group['key'] }}">{{ $group['name'] }}</label>
            <select name="option_{{ $group['key'] }}" id="option_{{ $product['sku'] }}_{{ $group['key'] }}" class="form-control" onchange="changeOption(this, '{{ $group['key'] }}', '{{ $product['sku'] }}', '-preview')">
                @foreach($product['options'] as $option)
                    @if( $option['option_group_id'] == $group['id'] )
                       	<option value="{{ $option['key'] }}" data-description="{{ $option['id'] }}">{{ $option['name'] }}</option>                    
                    @endif
                @endforeach
            </select>
        </div>
        @endforeach
        <div class="col-md-10" id="div-custom-sizing-preview-{{ $product['sku'] }}" style="display:none; padding-top:10px">
            <div class="col-md-4" style="padding-left:0px;">
            	<div class="col-md-4" style="padding-left:0px;">
                	<label for="img_width_{{ $product['sku'] }}" class="control-label">Width</label>
                </div>
                <div class="col-md-4">
                	<input type="number" name="img_width" id="img_width_{{ $product['sku'] }}" class="form-control" min="1" style="width:70px" onblur="customSize('w', '{{ $product['sku'] }}', '-preview')" onclick="this.focus()" />            
                </div>
            </div>
            <div class="col-md-4" style="padding-left:10px;">
            	<div class="col-md-4" style="padding-left:0px;">
                	<label for="img_height_{{ $product['sku'] }}" class="control-label">Height</label>
                </div>
                <div class="col-md-4">
                	<input type="number" name="img_height" id="img_height_{{ $product['sku'] }}" class="form-control" min="1" style="width:70px" onblur="customSize('h', '{{ $product['sku'] }}', '-preview')" onclick="this.focus()" />
                </div>
            </div>
        </div>
        
    </div>

@endforeach

