<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Your images have been activated.</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	Hello {{$userdata['first_name']}},<br/><br/>
    We have approved some images of you from {{URL}}, please see the list below<br/><br/>
    <ul>
    <?php $arr_images = $userdata['images']; ?>
    @foreach ($arr_images as $key => $value)
        <li>
            Image Id: <b>{{$value['id']}}</b>, Image Name: <b>{{$value['name']}}</b>, Go to <a href="{{URL}}/pic-{{$value['id']}}/{{$value['short_name']}}.html" title="{{ $value['name'] }}">Web Link</a>
        </li>                    
    @endforeach	
    </ul>
    <br/><br/>
	Thank you<br/>
    <b>The Image Stock.</b>
</body>

</html> 
