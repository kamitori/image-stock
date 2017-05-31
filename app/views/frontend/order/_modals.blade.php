@if(!empty($arrProduct))
@foreach($arrProduct as $key => $product)
    @if($key == 0)
    <!--Framed Print Modal-->
    <div class="modal fade" id="modal-{{ $product['sku'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-label-{{ $product['sku'] }}" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-label-{{ $product['sku'] }}">{{ $product['name'] }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <!--<img src="{{URL}}/assets/images/others/product-FP-wall.jpg" width="150px">-->
                            <?php echo getProductImageSrc($product, 0);?>
                            <p>We use premium 345gsm semi-gloss baryta fine art paper paired with ultra-low glare plexiglass to ensure your art gets all the attention.</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 1);?>
                            <p>Professionally framed in clean and modern solid wood frames in black, white or espresso finish with optional 2.5" mat</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 2);?>
                            <p>Includes dust-cover, high-quality hanging wire, wall protectors, and hook and nail.</p>
                        </div>
                    </div>                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @elseif($key == 1)
    <!--Art Print Modal-->
    <div class="modal fade" id="modal-{{ $product['sku'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-label-{{ $product['sku'] }}" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-label-{{ $product['sku'] }}">{{ $product['name'] }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 0);?>
                            <p>Premium 345gsm semi-gloss baryta art paper.</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 1);?>
                            <p>Ready for any frame or wall you choose.</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 2);?>
                            <p>Shipped securely packaged and rolled in a tube.</p>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @elseif($key == 2)
    <!--Canvas Modal-->
    <div class="modal fade" id="modal-{{ $product['sku'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-label-{{ $product['sku'] }}" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-label-{{ $product['sku'] }}">{{ $product['name'] }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 0);?>
                            <p>We use the best quality canvas, bar none. It is a matte textured, 20.5 mil bright white, consistent poly-cotton blend. Designed to last 100 years.</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 1);?>
                            <p>Hand-stretched over solid wood frames, available in 0.75" and 1.5" frame depths.</p>
                        </div>
                        <div class="col-sm-4">
                            <?php echo getProductImageSrc($product, 2);?>
                            <p>Open backed with heavy-duty hanging wire and wall protectors. All prints even include a hook and nail.</p>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>    
    @endif
    
@endforeach

<!--Guarantee Modal-->
<div class="modal fade" id="guarantee-modal" tabindex="-1" role="dialog" aria-labelledby="guarantee-modal-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="guarantee-modal-label">Satisfaction Guarantee</h4>
            </div>
            <div class="modal-body">
                <p>We use the best paper-stock, canvas, inks, and frames available. Our archival art prints and canvases are stretched and framed by hand then inspected carefully in our North American factories to ensure the highest standards and attention to detail.<br /><br />

We stand behind everything we produce. If you have an any questions about a print you received or notice any defects please contact us and our support team will help you out.</p>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif
<?php
function getProductImageSrc($product, $index)
{
	$product_image_path = isset($product['images'][$index]['path']) ? $product['images'][$index]['path'] : 'noimage';
	$product_image_path = str_replace('assets/images/products/', 'assets/images/products/thumbs/', $product_image_path);
	$product_image_file = $product_image_path;
	$product_image_file = str_replace('/', DS, $product_image_file);
	if(file_exists(public_path().DS.$product_image_file))
	{
		return "<img src='".URL::asset( $product_image_path )."' width='150px' />";
	}
	return "<img src='".URL::asset( 'assets/images/noimage/247x185.gif' )."' width='150px' />";
}

?>
