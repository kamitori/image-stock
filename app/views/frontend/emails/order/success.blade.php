<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h4>Hello {{$userdata->first_name}},</h4>

		<div>
			You have ordered successfully!<br/>
            Your order number is <b>#{{$order_id}}</b>. We will contact to you as soon as possible.<br/><br/>
            We have sent you an order's information file in the attachment. For more details, please get to this link {{ URL::to('order') }}.
		</div>
        <br/>
        <div>
            Thank you.<br/>
            <b>The Image Stock</b>
        </div>
	</body>
</html>
