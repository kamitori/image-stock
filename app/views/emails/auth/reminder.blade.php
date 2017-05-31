<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			Hello {{ $user->first_name.'  '.$user->last_name }},<br />
			You recently asked ImageStock to reset your login password.<br />
			You can reset your own password by visiting the following page: {{ URL.'/account/reset?token='.$token }}<br />
			This process is designed to ensure the privacy and security of your account information.<br />
			Thank you for using ImageStock.<br />
			Best Regards,<br />
			ImageStock
		</div>
	</body>
</html>
