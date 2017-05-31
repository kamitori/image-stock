<div class="row" style="padding-top:15px;">
    <div class="col-md-4">First name:</div>
    <div class="col-md-8">{{ $user->first_name }}</div>
</div>
<div class="row" style="padding-top:15px">
    <div class="col-md-4">Last name:</div>
    <div class="col-md-8">{{ $user->last_name }}</div>
</div>
<div class="row" style="padding-top:15px">
    <div class="col-md-4">About:</div>
    <div class="col-md-8">{{ nl2br(e($user->description)) }}</div>
</div>
