<div class="row">
	<div class="col-md-12">
		<!-- Begin: life time stats -->
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-shopping-cart"></i>Order #{{$order['id']}} <span class="hidden-480">
					| {{$order['created_at']}} </span>
				</div>
				<div class="actions">
					<a href="javascript:history.back();" class="btn default yellow-stripe">
					<i class="fa fa-angle-left"></i>
					<span class="hidden-480">
					Back </span>
					</a>
					<div class="btn-group">
						<a class="btn default yellow-stripe dropdown-toggle" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-cog"></i>
						<span class="hidden-480">
						Tools </span>
						<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a href="/admin/orders/export-to-pdf/{{$order['id']}}" target="_blank">
								Export to PDF </a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="javascript:;">
								Print Invoice </a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="portlet-body">
				<div class="tabbable">
					<ul class="nav nav-tabs nav-tabs-lg">
						<li class="active">
							<a href="#info" data-toggle="tab">
							Info </a>
						</li>
						<li>
							<a href="#details" data-toggle="tab">
							Details <span class="badge badge-success">
							{{ count($order['order_details']) }} </span>
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="info">
							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="portlet yellow-crusta box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Order Info
											</div>
											<div class="actions">
<!--												<a href="javascript:;" class="btn btn-default btn-sm">
												<i class="fa fa-pencil"></i> Edit </a>
-->											</div>
										</div>
										<div class="portlet-body">
											<div class="row static-info">
												<div class="col-md-5 name">
													Order #:
												</div>
												<div class="col-md-7 value">
													{{$order['id']}} 
                                                    <input type="hidden" name="order_id" id="order_id" value="{{$order['id']}}" />
                                                    <!--<span class="label label-info label-sm">Email confirmation was sent </span>-->
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Order Date &amp; Time:
												</div>
												<div class="col-md-7 value">
													{{$order['created_at'] }}
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Order Status:
												</div>
												<div class="col-md-7 value">
													<span class="label label-success">
													{{$order['status']}} </span>
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Grand Total:
												</div>
												<div class="col-md-7 value">
													${{number_format($order['sum_amount'],2)}}
												</div>
											</div>
											{{-- <div class="row static-info">
												<div class="col-md-5 name">
													Payment Information:
												</div>
												<div class="col-md-7 value">
													Credit Card
												</div>
											</div> --}}
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="portlet blue-hoki box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Customer Information
											</div>
											<div class="actions">
<!--												<a href="javascript:;" class="btn btn-default btn-sm">
												<i class="fa fa-pencil"></i> Edit </a>
-->											</div>
										</div>
										<div class="portlet-body">
											<div class="row static-info">
												<div class="col-md-5 name">
													Customer Name:
												</div>
												<div class="col-md-7 value">
                                                @if(isset($order['user']['first_name']))
													{{$order['user']['first_name']}} {{$order['user']['last_name']}}
                                                @else
                                                	{{$order['billing_address']['first_name']}} {{$order['billing_address']['last_name']}}
                                                @endif
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Email:
												</div>
												<div class="col-md-7 value">
                                                @if(isset($order['user']['email']))
													{{$order['user']['email']}}
                                                @else
                                                	Not register.
                                                @endif
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Country:
												</div>
												<div class="col-md-7 value">
													{{$order['billing_address']['country_name']}}
												</div>
											</div>
											<div class="row static-info">
												<div class="col-md-5 name">
													Phone Number:
												</div>
												<div class="col-md-7 value">
													{{$order['billing_address']['phone']}}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="portlet green-meadow box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Billing Address
											</div>
											<div class="actions">
												<a href="javascript:void(0);" onclick="editAddress('billing', 'div-address-{{$order['billing_address']['is_billing']}}')" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Edit </a>
											</div>
										</div>
										<div class="portlet-body">
											<div class="row static-info">
												<div id="div-address-{{$order['billing_address']['is_billing']}}" class="col-md-12 value">
                                                	{{ $order['billing_address_html'] }}
												</div>
                                                <div id="div-address-{{$order['billing_address']['is_billing']}}-view" style="display:none">
                                                    {{ $order['billing_address_html'] }}
                                                </div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="portlet red-sunglo box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Shipping Address
											</div>
											<div class="actions">
												<a href="javascript:void(0);" onclick="editAddress('shipping', 'div-address-{{$order['shipping_address']['is_billing']}}')" class="btn btn-default btn-sm">
												<i class="fa fa-pencil"></i> Edit </a>
											</div>
										</div>
										<div class="portlet-body">
											<div class="row static-info">
												<div id="div-address-{{$order['shipping_address']['is_billing']}}" class="col-md-12 value">
                                                	{{ $order['shipping_address_html'] }}
												</div>
                                                <div id="div-address-{{$order['shipping_address']['is_billing']}}-view" style="display:none">
                                                    {{ $order['shipping_address_html'] }}
                                                </div>                                                
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									<div class="well">
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Sub Total:
											</div>
											<div class="col-md-3 value">
												 ${{number_format($order['sum_amount'],2)}}
											</div>
										</div>
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Tax:
											</div>
											<div class="col-md-3 value">
												  ${{number_format($order['sum_tax'],2)}}
											</div>
										</div>
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Grand Total:
											</div>
											<div class="col-md-3 value">
												 ${{number_format($order['sum_amount'],2)}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="details">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="portlet grey-cascade box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Order Details
											</div>
										</div>
										<div class="portlet-body">
											<div class="table-responsive">
												<table class="table table-hover table-bordered table-striped">
												<thead>
												<tr>
													<th>
														 #
													</th>
													<th>
														 Image
													</th>
													<th>
														 Detail
													</th>
													<th class="text-right">
														 Price
													</th>
													<th class="text-right">
														 Quantity
													</th>
													<th class="text-right">
														 Tax Percent
													</th>
													<th class="text-right">
														 Tax Amount
													</th>
													<th class="text-right">
														 Discount Amount
													</th>
													<th class="text-right">
														 Total
													</th>
												</tr>
												</thead>
												<tbody>
												@foreach($order['order_details'] as $key => $detail)
												<tr>
													<td class="text-center">														
															{{ $key+1 }}
													</td>
													<td class="text-center">
                                                    	<a href="{{ URL.'/pic-'.$detail['image_id'].'/'.$detail['short_name'].'index.html' }}" target="_blank">
                                                        	<img src="{{ URL.$detail['path_thumb'] }}" style="width:120px;"/>
                                                        </a>
													</td>
													<td class="text-left" style="max-width: 10%;">
                                                        <h4>{{ $detail['name'] }}</h4>
                                                        @if($detail['type'] != '')
                                                            <div><small><b>Type: {{ $detail['type'] }}</b></small></div>
                                                        @endif
                                                        @if($detail['size'] != '')
                                                            <div><small>Size: {{ $detail['size'] }}</small></div>
                                                        @endif
                                                        @foreach($detail['options'] as $option)
                                                        <div><small>{{ $option['type'] }}: {{ $option['value'] }}</small></div>
                                                        @endforeach														
                                        				
													</td>
													<td class="text-right">
														 {{number_format($detail['sell_price'],2)}}
													</td>
													<td class="text-right">
														 {{$detail['quantity']}}
													</td>
													<td class="text-right">
														 {{number_format($detail['tax'],2)}}%
													</td>
													<td class="text-right">
														 {{number_format($detail['sum_tax'],2)}}
													</td>
													<td class="text-right">
														 {{$detail['discount']}}%
													</td>
													<td class="text-right">
														 {{number_format($detail['sum_amount'],2)}}
													</td>
												</tr>
												@endforeach
												</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
								</div>
								<div class="col-md-6">
									<div class="well">
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Sub Total:
											</div>
											<div class="col-md-3 value">
												 ${{number_format($order['sum_amount'],2)}}
											</div>
										</div>
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Tax:
											</div>
											<div class="col-md-3 value">
												  ${{number_format($order['sum_tax'],2)}}
											</div>
										</div>
										<div class="row static-info align-reverse">
											<div class="col-md-8 name">
												 Grand Total:
											</div>
											<div class="col-md-3 value">
												 ${{number_format($order['sum_amount'],2)}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@section('pageCSS')
<link href="{{ URL::asset( 'assets/global/css/plugins.css' ) }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset( 'assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css' ) }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset( 'assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css' ) }}" rel="stylesheet" type="text/css" >
@stop

@section('pageJS')
<script type="text/javascript">
function editAddress(type, div)
{
	var order_id = $('#order_id').val();
	$.ajax({
		url: '{{ URL.'/admin/orders/edit-address/' }}'+type+'/'+order_id,
		type: 'GET',
		data: {},
		success: function(data) {

			if(data['status'] == 'ok')
			{
				$('#'+div).html(data['html']);
			}
		}
	});			
}
function cancelEdit(div)
{
	var html = $('#'+div+'-view').html();
	$('#'+div).html(html);
}

function updateAddress(form_id)
{
	var order_id = $('#order_id').val();
	var address_form = document.getElementById(form_id);
	var is_billing = address_form.elements.namedItem("is_billing").value;
	var user_id = address_form.elements.namedItem("user_id").value;
	var first_name = address_form.elements.namedItem("first_name").value;	
	var last_name = address_form.elements.namedItem("last_name").value;
	var organization = address_form.elements.namedItem("organization").value;
	var phone = address_form.elements.namedItem("phone").value;
	var street = address_form.elements.namedItem("street").value;
	var street_extra = address_form.elements.namedItem("street_extra").value;
	var city = address_form.elements.namedItem("city").value;
	var post_code = address_form.elements.namedItem("post_code").value;
	
	var country = address_form.elements.namedItem("country_a2");
	var country_a2 = country.value;	
	var country_name = country.options[country.selectedIndex].text;
	
	var state = address_form.elements.namedItem("state_a2");
	var state_a2 = state.value;
	var state_name = state.options[state.selectedIndex].text;
	if(state_a2 == '')
	{
		state_name = null;
	}
	
	$.ajax({
		url: '{{ URL.'/admin/orders/create-address/' }}'+order_id,
		type: 'POST',
		data: {
			is_billing: is_billing,
			user_id: user_id,
			first_name: first_name,
			last_name: last_name,
			organization: organization,
			phone: phone,
			street: street,
			street_extra: street_extra,
			city: city,
			post_code: post_code,
			country_a2: country_a2,
			country_name: country_name,
			state_a2: state_a2,
			state_name: state_name
		},
		success: function(data) {
			if(data['status'] == 'ok')
			{
				$.ajax({
					url: '{{ URL.'/admin/orders/get-address/' }}'+data['result'].id,
					type: 'GET',
					data: {},
					success: function(data1) {

						if(data1['status'] == 'ok')
						{
							$('#div-address-'+is_billing).html(data1['html']);
							$('#div-address-'+is_billing+'-view').html(data1['html']);
						}
					}
				});		
			}
		}
	});
}

function getStates(country_a2, div_load)
{
	$.get("/payment/get-states/"+country_a2,
	{},
	function(data, status){
		if(data['result'] == 'ok')
		{
			$('#'+div_load).html(data['html']);
		}
	});			
}
</script>
@stop