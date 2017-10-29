<div class="container" align="center">
	<h3 style="color: darkcyan;font-weight: bold">Employee List</h3>
	<div class="alert alert-success" style="display: none;">

    </div>
	<button id="btnAdd" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Add New Employee</button><br/><br/>
    <div class="input-group">
        <input type="text" class="search form-control" placeholder="Search for..." width="50px">
        <span class="input-group-btn">
            <button class="btn btn-success" type="button">Go!</button>
        </span>
    </div>

	<table class="table table-bordered table-responsive" style="margin-top: 20px;" id="userTbl">
		<thead>
			<tr>
				<!--<td>ID</td>-->
                <td>Index Number</td>
				<td>First Name</td>
				<td>Last Name</td>
				<td>Telephone</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody id="showdata">
			
		</tbody>
	</table>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        	<form id="myForm" action="" method="post" class="form-horizontal">
                <input type="hidden" name="txtId" value="0">
                <div class="form-group">
                    <label for="name" class="label-control col-md-4">Index Number</label>
                    <div class="col-md-8">
                        <input type="text" name="txtIndex" class="form-control">
                    </div>
                </div>
        		<div class="form-group">
        			<label for="name" class="label-control col-md-4">First Name</label>
        			<div class="col-md-8">
        				<input type="text" name="txtFirstName" class="form-control">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="address" class="label-control col-md-4">Last Name</label>
        			<div class="col-md-8">
        				<input type="text" class="form-control" name="txtLastName">
        			</div>
        		</div>
                <div class="form-group">
                    <label for="address" class="label-control col-md-4">Telephone</label>
                    <div class="col-md-8">
                        <input type="tel" class="form-control" name="txtTelephone">
                    </div>
                </div>
        	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="btnSave" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        	Do you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="btnDelete" class="btn btn-danger">Delete</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(function(){
		showAllEmployee();

		//Add New
		$('#btnAdd').click(function(){
			$('#myModal').modal('show');
			$('#myModal').find('.modal-title').text('Add New Employee');
			$('#myForm').attr('action', '<?php echo base_url() ?>employee/addEmployee');
		});


		$('#btnSave').click(function(){
			var url = $('#myForm').attr('action');
			var data = $('#myForm').serialize();
			//validate form
            var indexNo = $('input[name=txtIndex]');
			var fname = $('input[name=txtFirstName]');
			var lname = $('input[name=txtLastName]');
            var telephone = $('input[name=txtTelephone]');
			var result = '';
            if(indexNo.val()==''){
                indexNo.parent().parent().addClass('has-error');
            }else{
                indexNo.parent().parent().removeClass('has-error');
                result +='1';
            }
			if(fname.val()==''){
				fname.parent().parent().addClass('has-error');
			}else{
				fname.parent().parent().removeClass('has-error');
				result +='2';
			}
			if(lname.val()==''){
				lname.parent().parent().addClass('has-error');
			}else{
				lname.parent().parent().removeClass('has-error');
				result +='3';
			}
            if(telephone.val()==''){
                telephone.parent().parent().addClass('has-error');
            }else{
                telephone.parent().parent().removeClass('has-error');
                result +='4';
            }

			if(result=='1234'){
				$.ajax({
					type: 'ajax',
					method: 'post',
					url: url,
					data: data,
					async: false,
					dataType: 'json',
					success: function(response){
						if(response.success){
							$('#myModal').modal('hide');
							$('#myForm')[0].reset();
							if(response.type=='add'){
								var type = 'added'
							}else if(response.type=='update'){
								var type ="updated"
							}
							$('.alert-success').html('Employee '+type+' successfully').fadeIn().delay(4000).fadeOut('slow');
							showAllEmployee();
						}else{
							alert('Error');
						}
					},
					error: function(){
						alert('Could not add data');
					}
				});
			}
		});

		//edit
		$('#showdata').on('click', '.item-edit', function(){
			var id = $(this).attr('data');
			$('#myModal').modal('show');
			$('#myModal').find('.modal-title').text('Edit Employee');
			$('#myForm').attr('action', '<?php echo base_url() ?>employee/updateEmployee');
			$.ajax({
				type: 'ajax',
				method: 'get',
				url: '<?php echo base_url() ?>employee/editEmployee',
				data: {id: id},
				async: false,
				dataType: 'json',
				success: function(data){
                    $('input[name=txtIndex]').val(data.indexNo);
					$('input[name=txtFirstName]').val(data.fname);
					$('input[name=txtLastName]').val(data.lname);
                    $('input[name=txtTelephone]').val(data.telephone);
					$('input[name=txtId]').val(data.id);
				},
				error: function(){
					alert('Could not Edit Data');
				}
			});
		});

		//delete- 
		$('#showdata').on('click', '.item-delete', function(){
			var id = $(this).attr('data');
			$('#deleteModal').modal('show');
			//prevent previous handler - unbind()
			$('#btnDelete').unbind().click(function(){
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: false,
					url: '<?php echo base_url() ?>employee/deleteEmployee',
					data:{id:id},
					dataType: 'json',
					success: function(response){
						if(response.success){
							$('#deleteModal').modal('hide');
							$('.alert-success').html('Employee Deleted successfully').fadeIn().delay(4000).fadeOut('slow');
							showAllEmployee();
						}else{
							alert('Error');
						}
					},
					error: function(){
						alert('Error deleting');
					}
				});
			});
		});



		//function
		function showAllEmployee(){
			$.ajax({
				type: 'ajax',
				url: '<?php echo base_url() ?>employee/showAllEmployee',
				async: false,
				dataType: 'json',
				success: function(data){
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html +='<tr>'+
									//'<td>'+data[i].id+'</td>'+
                                    '<td>'+data[i].indexNo+'</td>'+
									'<td>'+data[i].fname+'</td>'+
									'<td>'+data[i].lname+'</td>'+
									'<td>'+data[i].telephone+'</td>'+
									'<td>'+
										'<a href="javascript:;" class="btn btn-info item-edit" data="'+data[i].id+'"><span class="glyphicon glyphicon-edit"></span> Edit</a>'+
										'  <a href="javascript:;" class="btn btn-danger item-delete" data="'+data[i].id+'"><span class="glyphicon glyphicon-remove-sign"></span> Delete</a>'+
									'</td>'+
							    '</tr>';
					}
					$('#showdata').html(html);
				},
				error: function(){
					alert('Could not get Data from Database');
				}
			});
		}
	});

    $(document).ready(function(){
        $('.search').on('keyup',function(){
            var searchTerm = $(this).val().toLowerCase();
            $('#userTbl tbody tr').each(function(){
                var lineStr = $(this).text().toLowerCase();
                if(lineStr.indexOf(searchTerm) === -1){
                    $(this).hide();
                }else{
                    $(this).show();
                }
            });
        });
    });
</script>