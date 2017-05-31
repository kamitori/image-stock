<?php
$list_sendmail = array();
if ( Session::has('list_sendmail') )
{
	$list_sendmail = Session::get('list_sendmail');
}		
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">List of images that have just activated.</h4>
</div>
<div class="modal-body form">
    <div style="padding:10px">
    @if(count($list_sendmail) > 0)
        <ul>
        @foreach ($list_sendmail as $key => $value)        
            <li style="padding:5px">User: <b>{{$value['first_name']}} {{$value['last_name']}}</b>, Email: <b>{{$value['email']}}</b></li>
            <ul>
            <?php $arr_images = $value['images']; ?>
            @foreach ($arr_images as $key1 => $value1)
                <li>
                    Image Id: <b>{{$value1['id']}}</b>, Image Name: <b>{{$value1['name']}}</b>, Activated.
                </li>                    
            @endforeach	
            </ul>
        
        @endforeach
        </ul>
    @else
    	There are no new images have been activated.
    @endif   
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-close">Close</button>
    @if(count($list_sendmail) > 0)
    	<button type="button" id="info-send" class="btn btn-primary"><i class="fa fa-check"></i>Send list of activated images to users</button>
    @endif
</div>

