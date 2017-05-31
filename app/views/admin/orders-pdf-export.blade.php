<!DOCTYPE html>
<head>
	<meta charset="utf-8"/>
	<title>{{ $title or '' }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<link href="{{ URL::asset( 'assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet') }}" type="text/css"/>
    <link href="{{ URL::asset( 'assets/admin/layout/css/themes/'.( isset($currentTheme['color']) ? $currentTheme['color'] : 'default').'.css') }}" rel="stylesheet" type="text/css" id="style-color"/>
    <link href="{{ URL::asset( 'assets/global/css/'. ( isset($currentTheme['style']) ? $currentTheme['style'] : 'components') .'.css') }}" id="style-components" rel="stylesheet" type="text/css"/>
<style>
</style>
</head>
<body style="background:#fff;">
<div class="row" >
    <div class="col-md-6 col-sm-12" >
        <div class="portlet yellow-crusta box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Order Info
                </div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Order #:
                    </div>
                    <div class="col-md-7 value">
                        {{$order['id']}} 
                        <input type="hidden" name="order_id" id="order_id" value="{{$order['id']}}" />
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
                        <span>
                        	{{$order['status']}}
                        </span>
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
</div>
<div class="row">
    <div class="col-md-6 col-sm-12" >
        <div class="portlet blue-hoki box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Customer Information
                </div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Customer Name:
                    </div>
                    <div class="col-md-7 value">
                        {{$order['user']['first_name']}} {{$order['user']['last_name']}}
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Email:
                    </div>
                    <div class="col-md-7 value">
                        {{$order['user']['email']}}
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
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-12 value">
                        {{ $order['billing_address_html'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="portlet red-sunglo box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Shipping Address
                </div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-12 value">
                        {{ $order['shipping_address_html'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="height:205px;"></div>
<div class="row">
    <div class="col-md-8 col-sm-8">
        <div class="portlet grey-cascade box">
            <div class="portlet-title">
                <div class="caption">
                    <h2>Order #{{$order['id']}} details</h2>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" width="100%">
                        <tr>
                        	<td></td>                        	
                            <td>
                                 #
                            </td>
                            <td>
                                 Image
                            </td>
                            <td>
                                 Detail
                            </td>
                            <td class="text-right">
                                 Price
                            </td>
                            <td class="text-right">
                                 Quantity
                            </td>
                            <td class="text-right">
                                 Tax Percent
                            </td>
                            <td class="text-right">
                                 Tax Amount
                            </td>
                            <td class="text-right">
                                 Discount Amount
                            </td>
                            <td class="text-right">
                                 Total
                            </td>
                        </tr>
                        @foreach($order['order_details'] as $key => $detail)
                        <tr>
                            <td>														
                                    {{ $key+1 }}
                            </td>
                            <td>
                                <a href="{{ URL.'/pic-'.$detail['image_id'].'/'.$detail['short_name'].'index.html' }}" target="_blank">
                                    <img src="{{ URL.$detail['path_thumb'] }}" style="width:120px;"/>
                                </a>
                            </td>
                            <td class="text-left">
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" style="text-align:right">
	<div class="col-md-8 col-sm-8">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <div class="well">
                <div class="row static-info align-reverse">
                    <div class="col-md-8 name" style="display:inline">
                         <b>Sub Total:</b>
                    </div>
                    <div class="col-md-3 value" style="display:inline">
                         ${{number_format($order['sum_amount'],2)}}
                    </div>
                </div>
                <div class="row static-info align-reverse">
                    <div class="col-md-8 name" style="display:inline">
                         <b>Tax:</b>
                    </div>
                    <div class="col-md-3 value" style="display:inline">
                          ${{number_format($order['sum_tax'],2)}}
                    </div>
                </div>
                <div class="row static-info align-reverse">
                    <div class="col-md-8 name" style="display:inline">
                         <b>Grand Total:</b>
                    </div>
                    <div class="col-md-3 value" style="display:inline">
                         ${{number_format($order['sum_amount'],2)}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>