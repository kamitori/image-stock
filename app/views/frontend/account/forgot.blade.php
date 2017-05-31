<div class="main-content ">
    <div class="box-width">
        <h1 class="text-center page-title light" style="font-size: 25px;">
			Forgot Your Password?
		</h1>
    </div>
    <div class="box-width">
        <div class="border-container">
            @if( Session::get('emailNotFound') )
            <div id="messages">
                <ul class="alert alert-error">
                    <li>
                        We could not find the username you entered in our system.
                    </li>
                </ul>
            </div>
            @elseif( Session::get('resetPassword') )
            <div id="messages">
                <ul class="alert alert-success">
                    <li>
                        Please check your email shortly for a link to reset your password.
                    </li>
                </ul>
            </div>
            @endif
            {{ Form::open(['id' => 'forgot_form', 'method' => 'post']) }}
            <p class="text-center">Enter your email address below and we'll send you a link to reset your password.</p>
            <div class="text-center">
                <div>
                    <input class="form-control" type="text" name="email" id="email" autocomplete="off" spellcheck="false" placeholder="Email Address">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@section('pageCSS')
<style type="text/css">
#messages ul.alert {
    margin-bottom: 20px;
}

#messages ul,
ul.alert {
    list-style-type: none;
    margin: 0;
}

.alert {
    padding: 11px 35px 10px 20px;
    margin-bottom: 20px;
    font-size: 12.6px!important;
    line-height: 1.3;
    color: #606060;
    border: 1px solid #edbbbc;
    background-color: #faeeee;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    box-shadow: 0 1px rgba(0, 0, 0, 0.03), inset 0 0 1px 0 rgba(255, 255, 255, 0.3);
}

.box-width {
    width: 340px;
    max-width: 100%;
    box-sizing: border-box;
    margin: 0 auto 0 auto;
}

.border-container {
    border: 1px solid #c4c4c4;
    border-radius: 4px;
    padding: 32px;
    position: static !important;
    margin-bottom: 32px;
}

#forgot_form .btn {
    margin-top: 16px;
    margin-bottom: 16px;
}

#forgot_form .btn-primary,
#forgot_form .btn-success {
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    background-color: #d2453f;
    background-image: -moz-linear-gradient(top, #e85b54, #b22520);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#e85b54), to(#b22520));
    background-image: -webkit-linear-gradient(top, #e85b54, #b22520);
    background-image: -o-linear-gradient(top, #e85b54, #b22520);
    background-image: linear-gradient(to bottom, #e85b54, #b22520);
    background-repeat: repeat-x;
    border: 1px solid #b22520;
    filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffe85b54', endColorstr='#ffb22520', GradientType=0);
    filter: progid: DXImageTransform.Microsoft.gradient(enabled=false);
    box-shadow: inset 0 1px 0 rgba(242, 164, 162, 0.6), 0 1px 2px rgba(0, 0, 0, 0.05);
}
</style>
@stop
