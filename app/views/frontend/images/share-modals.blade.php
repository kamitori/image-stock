<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="shareModalLabel"><i class="fa fa-share-alt"></i> Share</h4>
      </div>
      <div class="modal-body">
        <p>
            <a title="Facebook" href="javascript:void(0);" onClick="shareFB('{{URL}}/pic-{{$imageObj['image_id']}}/{{ $imageObj['short_name'] }}.html')"><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x"></i></span></a> 
            <a title="Twitter" href="javascript:void(0);"><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x"></i></span></a> 
            <a title="Google+" href="javascript:void(0);"><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-google-plus fa-stack-1x"></i></span></a> 
        </p>
        
        <h2><i class="fa fa-envelope"></i> Email</h2>
        <p>Share this page to your friends via email.</p>
                
        <form action="#" method="post">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control" placeholder="yourfriend@email.com">
            </div>
            <br />
            <button type="button" value="sub" name="sub" class="btn btn-primary"><i class="fa fa-share"></i> Share Now!</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
