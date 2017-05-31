<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa icon-images"></i>{{ 'Inactive Images' }}
                </div>
                <div class="actions">
                    <a href="#" id="list-sendmail" class="btn default yellow-stripe">
                        <i class="fa fa-mail-reply-all"></i>
                        <span class="hidden-480">{{ 'Send Email' }}</span>
                    </a>
                </div>                
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-hover table-bordered" id="list-images">
                        <thead>
                            <tr role="row" class="heading">
                                <td>No.</td>
                                <td>Image Id</td>
                                <th>
                                   User
                                </th>
                                <th>
                                    Image name
                                </th>
                                <th>
                                    Photos
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center" width="18%">
                                     {{'Tools'}}
                                </th>
                            </tr>
                            <tr role="row" class="filter">
                            	<td></td>
                                <td><input type="text" class="form-control form-filter input-sm" name="search[id]"></td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="search[full_name]">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="search[name]">
                                </td>
                                
                                <td>
                                </td>
                                <td>
                                </td>

                                <td class="text-center">
                                    <button id="search-button" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i>{{ 'Search' }}</button>
                                    <button id="cancel-button" class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i>{{ 'Reset' }}</button>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--List sendmail modal-->
<div id="list-sendmail-modal" class="modal" style="z-index: 1000000;">
	<div id="div-list-sendmail" class="modal-content" style="top: 12%; width: 70%; left: 15%; position: absolute; z-index: 1000000;">
	</div>
</div>

        
@section('pageCSS')
<link href="{{ URL::asset( 'assets/global/css/plugins.css' ) }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset( 'assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css' ) }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset( 'assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css' ) }}" rel="stylesheet" type="text/css" >
@stop
@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/datatables/media/js/jquery.dataTables.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js' ) }}"></script>
<script type="text/javascript">

var columnDefs = [
        {
            "targets": 1,
            "name"  : "id",
            "data" : function(row, type, val, meta) {
                return row[1];
            }
			
        },        
		{
            "targets": 2,
            "name"  : "full_name",
            "data" : function(row, type, val, meta) {
                return '<span>'+row[2]+'</span>';
            }
			
        },
        {
            "targets": 3,
            "name"  : "name",
            "data" : function(row, type, val, meta) {
				//console.log('row: '+row);
                var html = '';
                html='<span>'+row[3]+'</span>'
                return html;
            }
        },
        {
            "targets": 4,
            "name"  : "name",
            "data" : function(row, type, val, meta) {
				//console.log('row: '+row);
                var html = '';
                html='<div>'+row[4]+'</div>'
                return html;
            }
        },
        {
            "targets": 5,
            "className" : "text-center",
            "name"  : "active",
            "data" : function(row, type, val, meta) {
                var html = '';
                var label = {
                        'In-active' : 'label-default',
                        'Active' : 'label-success'
                };
				html = '<span>In-active</span>';	
				if(row[5] == 1)
				{
					html = '<span>Active</span>';	
				}				
                
                return '<a href="javascript:void(0)" class="xeditable-select" data-escape="true" data-name="active" data-type="select" data-value="'+row[5]+'" data-pk="'+row[1]+'" data-url="{{ URL.'/admin/images/update-status'}}" data-title="Active this image">'+html+'</a>';
            }
        },

    ];
listRecord({
    url: "{{ URL.'/admin/images/list-inactive-images' }}",
	delete_url: "{{ URL.'/admin/images/delete-image' }}",
    table_id: "#list-images",
    columnDefs: columnDefs,
    pageLength: 20,
    fnDrawCallback: function(){
        $("tbody td.limit-text","#list-images").tooltip({
                placement:"top",
                html:true,
                title: function(){
                    return $(this).find('span').attr("data-title");
                },
                container: 'body'
        });
        $(".xeditable-select","#list-images").editable({
            source: [{value: "0", text: "In-active"},{value: "1", text: "Active"}],
            success: function(response, newValue){
                if( response.status == "ok" ) {
                    toastr.success(response.message, 'Message');
                } else {
                    return response.message;
                }
            }
        });
        $(".xeditable-text","#list-images").editable({
            source: [],
            success: function(response, newValue){
                if( response.status == "ok" ) {
                    toastr.success(response.message, 'Message');
                } else {
                    return response.message;
                }
            }
        });
		
    },
});

$("#list-sendmail").click(function(){
	
	$.ajax({
		url: '{{ URL.'/admin/images/list-sendmail/' }}',
		type: 'GET',
		data: {},
		success: function(data) {

			if(data['status'] == 'ok')
			{
				$('#div-list-sendmail').html(data['html']);
				$("#list-sendmail-modal").modal('show');
				
				$('.btn-close').click(function(){
					$('#list-sendmail-modal').modal('hide');
				});
				
				$('#info-send').click(function(){
				
					$.ajax({
						url: '{{ URL.'/admin/images/sendmail-activated/' }}',
						type: 'GET',
						data: {},
						success: function(data) {
				
							if(data['status'] == 'ok')
							{
								$('#div-list-sendmail').html('');
								$("#list-sendmail-modal").modal('hide');
								toastr.success('Email sent.', 'Message');
							}
						}
					});				
				
				});
				
			}
		}
	});				
});

</script>
@stop