@extends('tickets.ticketadminlayout') @section('body')
<div class="row">
	<div class="col-md-12">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
					<div class="pull-right">
						<button data-toggle="modal" data-target="#myModal"
							class="btn btn-sm btn-primary">
							<i class="fa fa-user-plus"></i> Add Department
						</button>
					</div>
					<h2 class="font-bold ">Departments</h2>
				</div>
				<div class="ibox-content">

					<table class="table table-bordered">
						<thead>
							<tr>
								
								<th>Department</th>
								<th>Head</th>
								<th>Description</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="department">
							@foreach($departments as $department)
							<tr>
								<td class="department{{$department->id}}">{{$department->department}}</td>
								<td class="departmentHead{{$department->id}}">{{$department->head}}</td>
								<td class="departmentDescription{{$department->id}}">{{$department->department_description}}</td>
								
									<td class="text-center">
										<div class="btn-group">
											<button type="button"
												class="btn btn-sm btn-warning btn-xs editDepartment"
												value="{{$department->id}}" data-toggle="modal" data-target="#editDepartment">Edit</button>

											
										</div>
									</td> 
								
							</tr>
							@endforeach
						</tbody>
					</table>


				</div>

			</div>
		</div>
	</div>


</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog ">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Department</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal addDepartment">

					{!! csrf_field() !!}
					<div class="form-group addDepartment">
						<label class="col-md-3 control-label">Department:</label>
						<div class="col-md-9">
							<input type="text" class="form-control input-sm addDepartment"
								name="department"> <label
								class="text-danger help-block addDepartment"></label>
						</div>

					</div>
					<div class="form-group addDepartmentHead">
						<label class="col-md-3 control-label">Dept. Head:</label>
						<div class="col-md-9">
							<input type="text" class="form-control input-sm addDepartmentHead"
								name="head"> <label
								class="text-danger help-block addDepartmentHead"></label>
						</div>
					</div>
					
					<div class="form-group addDepartmentDescription">
						<label class="col-md-3 control-label">Description:</label>
						<div class="col-md-9">
							<textarea class="form-control" rows="4" name="description">
							</textarea>
							<label class="text-danger help-block addDepartmentDescription"></label>
						</div>
					</div>

				</form>



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-w-m addDeparment">Submit</button>
				<button type="button" class="btn btn-w-m btn-danger btn-outline"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>

<div id="editDepartment" class="modal fade" role="dialog">
	<div class="modal-dialog ">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit Department</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal editDepartment">

					{!! csrf_field() !!}
					<input type="hidden" name="id" class="editDepartmentId">
					<div class="form-group editDepartment">
						<label class="col-md-3 control-label">Department:</label>
						<div class="col-md-9">
							<input type="text" class="form-control input-sm editDepartment"
								name="department"> <label
								class="text-danger help-block editDepartment"></label>
						</div>

					</div>
					<div class="form-group editDepartmentHead">
						<label class="col-md-3 control-label">Head:</label>
						<div class="col-md-9">
							<input type="text" class="form-control input-sm editDepartmentHead"
								name="head"> <label
								class="text-danger help-block editDepartmentHead"></label>
						</div>
					</div>
					
					<div class="form-group editDepartmentDescription">
						<label class="col-md-3 control-label">Description:</label>
						<div class="col-md-9">
							<textarea class="form-control editDepartmentDescription" rows="4" name="description">
							</textarea>
							<label class="text-danger help-block editDepartmentDescription"></label>
						</div>
					</div>

				</form>



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-w-m saveEditDeparment">Save</button>
				<button type="button" class="btn btn-w-m btn-danger btn-outline"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$('label.text-danger').hide();
	$('textarea').val("");
});
</script>
@endsection
