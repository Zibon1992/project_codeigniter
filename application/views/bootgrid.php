
<html>
<head>
    <title>CodeIgniter-Ajax Crude Application</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.js"></script>  
</head>
<body>
    <div class="container box">
        <h3 align="center">Employee Management System</h3><br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="panel-title">Employee List</h3>
                    </div>
                    <div class="col-md-2" align="right">
                        <button type="button" id="add_button" data-toggle="modal" data-target="#employeeModal" class="btn btn-info btn-xs">Add</button>
                    </div>
                </div>
                
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="employee_data" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th data-column-id="name">Name</th>
                                <th data-column-id="gender">Gender</th>
                                <th data-column-id="designation">Designation</th>
                                <th data-column-id="age">Age</th>
                                <th data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
                            </tr>
                        </thead>
                        <?php 
                        	if($fetch_data->num_rows()>0)
                        	{
                        		foreach ($fetch_data-> result() as $row)
                        		 {
                        		?>
                        		<tr>
                        			<td><?php echo $row->name;?></td>
                        			<td><?php echo $row->gender;?></td>
                        			<td><?php echo $row->designation;?></td>
                        			<td><?php echo $row->age;?></td>

                        			
                        		</tr>
                        		<?php 
                        		}
                        	}
                        	else
                        	{
                        		?>
                        		<tr>
                        			<td colspan="3">No Data Found</td>
                        		</tr>
								<?php
                        	}
                        ?>
                    </table>
                </div>
            </div>
       </div>
    </div>
</body>
</html>

<div id="employeeModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="employee_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Employee</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Enter Name</label>
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>
                    
                    <div class="form-group">
                        <label>Select Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Enter Designation</label>
                        <input type="text" name="designation" id="designation" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Enter Age</label>
                        <input type="text" name="age" id="age" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="employee_id" id="employee_id" />
                    <input type="hidden" name="operation" id="operation" value="Add" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    var employeeTable = $('#employee_data').bootgrid({
        ajax:true,
        rowSelect: true,
        post:function()
        {
            return{
                id:"b0df282a-0d67-40e5-8558-c9e93b7befed"
            }
        },
        url:"<?php echo base_url(); ?>bootgrid/fetch_data",
        formatters:{
            "commands":function(column, row)
            {
                return "<button type='button' class='btn btn-warning btn-xs update' data-row-id='"+row.id+"'>Edit</button>" + "&nbsp; <button type='button' class='btn btn-danger btn-xs delete' data-row-id='"+row.id+"'>Delete</button>";
            }
        }
    });

    $('#add_button').click(function(){
        $('#employee_form')[0].reset();
        $('.modal-title').text("Add Employee");
        $('#action').val("Add");
        $('#operation').val("Add");
    });

    $(document).on('submit', '#employee_form', function(event){
        event.preventDefault();
        var name = $('#name').val();
        
        var gender = $('#gender').val();
        var designation = $('#designation').val();
        var age = $('#age').val();
        var form_data = $(this).serialize();
        if(name != '' &&  gender != '' &&  designation != '' && age != '')
        {
            $.ajax({
                url:"<?php echo base_url(); ?>bootgrid/action",
                method:"POST",
                data:form_data,
                success:function(data)
                {
                    alert(data);
                    $('#employee_form')[0].reset();
                    $('#employeeModal').modal('hide');
                    $('#employee_data').bootgrid('reload');
                }
            });
        }
        else
        {
            alert("All Fields are Required");
        }
    });

    $(document).on("loaded.rs.jquery.bootgrid", function(){
        employeeTable.find('.update').on('click', function(event){
            var id = $(this).data('row-id');
            $.ajax({
                url:"<?php echo base_url(); ?>bootgrid/fetch_single_data",
                method:"POST",
                data:{id:id},
                dataType:"json",
                success:function(data)
                {
                    $('#employeeModal').modal('show');
                    $('#name').val(data.name);
                    $('#gender').val(data.gender);
                    $('#designation').val(data.designation);
                    $('#age').val(data.age);
                    $('.modal-title').text("Edit Employee Details");
                    $('#employee_id').val(id);
                    $('#action').val('Edit');
                    $('#operation').val('Edit');
                }
            });
        });

        employeeTable.find('.delete').on('click', function(event){
            if(confirm("Are you sure you want to delete this?"))
            {
                var id = $(this).data('row-id');
                $.ajax({
                    url:"<?php echo base_url(); ?>bootgrid/delete_data",
                    method:"POST",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#employee_data').bootgrid('reload');
                    }
                });
            }
            else
            {
                return false;
            }
        });
    });
    
});
</script> 