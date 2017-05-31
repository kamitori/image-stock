@foreach($arrProduct as $key => $product)

    <div id="div-choose-order-{{ $product['sku'] }}" style="display:none">
    	

        <div class="form-group">        	
            <label for="img_sizing_{{ $product['sku'] }}" class="control-label">Size</label>    
            <select class="form-control" name="img_sizing" id="img_sizing_{{ $product['sku'] }}" onchange="changeSize('{{ $product['sku'] }}')">                 
            </select>
            <input type="hidden" name="custom_size" id="custom_size_{{ $product['sku'] }}" value="{{ $product['custom_size'] }}" />
            <input type="hidden" id="default-sizew-{{ $product['sku'] }}" value="{{ isset($product['size_lists'][0]) ? $product['size_lists'][0]['sizew'] : 0 }}" />
            <input type="hidden" id="default-sizeh-{{ $product['sku'] }}" value="{{ isset($product['size_lists'][0]) ? $product['size_lists'][0]['sizeh'] : 0 }}" />
            <div id="div-custom-sizing-{{ $product['sku'] }}" style="display:none;">
                <div style="display:inline-block">
                    <label for="img_width_{{ $product['sku'] }}" class="control-label">Width</label>
                    <input type="number" name="img_width" id="img_width_{{ $product['sku'] }}" class="form-control" min="1" style="width:70px" onblur="customSize('w', '{{ $product['sku'] }}')" onclick="this.focus()" />            
                </div>
                <div style="display:inline-block">
                    <label for="img_height_{{ $product['sku'] }}" class="control-label">Height</label>
                    <input type="number" name="img_height" id="img_height_{{ $product['sku'] }}" class="form-control" min="1" style="width:70px" onblur="customSize('h', '{{ $product['sku'] }}')" onclick="this.focus()" />            
                </div>
            </div>
        </div>

        
        @foreach($product['option_groups'] as $group)
        <div class="form-group" style="text-align:left;">
            <label for="option_{{ $product['sku'] }}_{{ $group['key'] }}">{{ $group['name'] }}</label>
            <select name="option_{{ $group['key'] }}" id="option_{{ $product['sku'] }}_{{ $group['key'] }}" class="form-control" onchange="changeOption(this, '{{ $group['key'] }}', '{{ $product['sku'] }}')">
                @foreach($product['options'] as $option)
                    @if( $option['option_group_id'] == $group['id'] )
                       	<option value="{{ $option['key'] }}" data-description="{{ $option['id'] }}">{{ $option['name'] }}</option>                    
                    @endif
                @endforeach
            </select>
        </div>
        @endforeach
        
    </div>

@endforeach

