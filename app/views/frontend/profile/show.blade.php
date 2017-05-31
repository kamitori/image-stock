@section('pageCSS')
<style>
* {
  box-sizing: border-box;
}

/* Make sure draggable area is whole page */
html, body {
  /*height: 100%;*/
}

body.droppable .profile-avatar-wrap {
  border: 5px dashed lightblue;
  z-index: 9999;
}

.panel.account-info {
	border-color:#D1D1D1;
}
.panel.account-info .account-heading {
	background-color:#f5f5f5;
	border-color:#D1D1D1;
	color:#333;
	height:50px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    padding: 10px 15px;	
}

.page-wrap {
  width: 100%;
}

h1 {
  margin: 0 0 30px 0;
  border-bottom: 5px solid #ccc;
}
h3 {
  clear: both;
  margin: 100px 0 0 0;
}

.profile {
  width: 100%;
}
.profile-avatar-wrap {
  width: 13.33%;
  float: left;
  margin: 0 20px 5px 0;
  position: relative;
  pointer-events: none;
  border: 5px solid transparent;
}
.profile-avatar-wrap:after {
  /* Drag Prevention */
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.profile-avatar-wrap img {
  width: 100%;
  display: block;
}
.location {
  text-transform: uppercase;
  color: #999;
  letter-spacing: 1px;
  margin: 0 0 10px 0;
  font-size: 90%;
}

.progressBar {
    width: 110px;
    height: 22px;
    border: 1px solid #ddd;
    border-radius: 5px; 
    overflow: hidden;
    display:inline-block;
    margin:0;
    vertical-align:top;
}
 
.progressBar div {
    height: 100%;
    color: #fff;
    text-align: right;
    line-height: 22px; /* same as #progressBar height if we want text middle aligned */
    width: 0;
    background-color: #0ba1b5; border-radius: 3px; 
}
.statusbar
{
    min-height:25px;
    width:100%;
    padding:0 5px;
    vertical-align:top;
}
.statusbar:nth-child(odd){
    background:#EBEFF0;
}
.filename
{
	display:inline-block;
	vertical-align:top;
	width:250px;
}
.filesize
{
	display:inline-block;
	vertical-align:top;
	color:#30693D;
	width:100px;
	margin-left:10px;
	margin-right:5px;
}
.abort{
    background-color:#A8352F;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    border-radius:4px;display:inline-block;
    color:#fff;
    font-family:arial;font-size:13px;font-weight:normal;
    padding:0px 15px;
    cursor:pointer;
    vertical-align:top
}
.upload_status {
	display: inline;
    padding: 5px 20px;
    position: absolute;
	color:#0ba1b5;
}
</style>
@stop
<div class="container">
      <div class="row">
	@if (Auth::user()->check())
      <div class="col-md-5  toppad  pull-right col-md-offset-3 ">

        <!--<a href="/account/sign-out" >Logout</a>-->

      </div>
        <div class="col-lg-6 col-lg-offset-3 toppad" >

          <div class="panel account-info">
            <div class="account-heading">
              <h3 class="panel-title">Account Details</h3>
            </div>
            <div class="panel-body">
            	<div class="row" style="padding:20px;">
                
                    <div class="page-wrap">                
                        <div id="profile" class="row profile">        
                            <div class="col-md-3 profile-avatar-wrap">
                            @if($user->image != '' && File::exists(public_path().DS.$user->image))
                                <img src="{{ URL::asset( $user->image ) }}" id="profile-avatar" alt="Image for Profile">
                            @else
                         		<img src="{{ URL::asset( 'assets/images/noimage/person.jpg' ) }}" id="profile-avatar" alt="Image for Profile">   
                            @endif                            	
                            </div>
                            <div class="col-md-8">       
                                <h3 id="full_name"><a href="/user-reference/{{$user->id}}/{{Str::slug($user->first_name)}}.html" title="Go to {{ $user->first_name }}'s images">{{ $user->first_name }} {{ $user->last_name }}</a></h3>
                                <div class="location">{{ $user->city }} {{ $user->state_name }} {{ $user->country_name }}</div>
                                <p>Drag & Drop Image to Change Avatar.</p>
                            </div>        
                        </div>

                        <div class="row" style="padding: 15px 15px 0 15px">
                            <p>You could do this with a file input too...</p>
                            <input name="myfile" type="file" id="uploader">
                        </div>        
                    </div>
                                    
                </div>
              <div class="row">

                <div class=" col-md-12 col-lg-12 ">
                  <table class="table table-user-information" style="margin-bottom:0;">
                    <tbody>
                      <tr>
                        <td style="padding-top:20px; border-top:none">Email:</td>
                        <td style="padding-top:20px; border-top:none">{{ $user->email }}</td>
                      </tr>
                      <tr>
                        <td style="border-top:none">Password:</td>
                        <td style="border-top:none"><a id="change-password" href="javascript:void(0)" class="btn btn-outline btn-default">Change Password</a></td>
                      </tr>
                      <tr style="border-top:none">
                      	<td style="border-top:none"></td>
                      	<td style="border-top:none">
                        	<div id="div-change-password"></div>
                        </td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
                <div class="container" style="padding:20px">
                    <div class="row" style="padding-bottom:5px; border-bottom:1px #999 solid">
                    	<span><b>Contact Information</b></span>
                        <button name="btn-edit" id="btn-edit" class="btn btn-outline btn-default btn-xs pull-right">Edit</button>
                    </div>
                    <div id="div-edit">
                    	@include('frontend.profile.inform')
                    </div>
                </div>              
            </div>
                 <div class="panel-footer" style="height:50px">

                        <span class="pull-right">
                            <a href="/account/sign-out" title="Logout" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-log-out"></i></a>

<!--                            <a href="#" data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
-->
                        </span>
                    </div>

          </div>
        </div>
        @else
            <p>No profile yet.</p>
            <li><a href="{{ URL::route('account-sign-in') }}" class="btn btn-outline btn-primary">Sign in</a></li>
            <li><a href="#" class="btn btn-outline btn-primary">Create an account</a></li>
        @endif
  </div>
</div>

@section('pageJS')
<script src="{{URL}}/assets/global/scripts/resample.js" type="text/javascript"></script>
<script src="{{URL}}/assets/global/scripts/avatar.js" type="text/javascript"></script>
<script>

function cancelChangePassword()
{
	$('#div-change-password').html('');
}
function cancelEditConfirm()
{
	$.get("/account/load-inform",{},
	function(data, status){
		$('#div-edit').html(data['html']);
	});
}

function passwordChange()
{
	$.post("/account/change-password",{
		old_password: $("#old_password").val(),
		password: $("#password").val(),
		password_confirm: $("#password_confirm").val()
	},
	function(data, status){

		alert(data['message']);

		if(data['result'] == "success")
		{
			$('#div-change-password').html('');
		}

	});
}

function editConfirm()
{
	$.post("/account/update",{
		first_name: $("#first_name").val(),
		last_name: $("#last_name").val(),
		description: $("#description").val()
	},
	function(data, status){
		
		if(data['result'] == "success")
		{
			$('#div-edit').html(data['html']);
			var user_link = '<a href="/user-reference/'+data['user'].id+'/'+convertToSlug(data['user'].first_name)+'.html" title="Go to '+data['user'].first_name+'\'s images">'+data['user'].first_name+' '+data['user'].last_name+'</a>';
			$('#full_name').html(user_link);
		}
		else
		{
			alert(data['message']);	
		}

	});
}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}

$(document).ready(function() {

	$("#change-password").on("click",function(){

		$.get("/account/change-password",{},
		function(data, status){
			$('#div-change-password').html(data['html']);
		});

	});

	$("#btn-edit").on("click",function(){

		$.get("/account/edit",{},
		function(data, status){
			$('#div-edit').html(data['html']);
		});

	});

});
</script>
@stop