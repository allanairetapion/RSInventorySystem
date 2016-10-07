@extends('inventory.inventory') @section('title', 'RS | Items Have
Issues') @section('header-page')
<div class="col-lg-10">
	<h2>Items With Issues</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Item Have Issues Form</strong></li>
	</ol>
</div>
@endsection @section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">

			<div class="ibox-content">
				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#issueReport">Report Item Issue</button>
					
						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#repairReport">Report Item
							Repair</button>
							<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">Export <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="exportExcel">excel</a></li>
                                <li><a href="#" id="exportCSV">csv</a></li>
                               
                            </ul>
                        </div>
					</div>
					<div class="col-md-8">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="filter"
								placeholder="Search...">
							<div class="input-group-btn">
								<button class="btn btn-white" id="issueAdvancedSearch"
									type="button">
									Search Options <span class="caret"></span>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div id="issueAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="issueTicketSearch form-horizontal">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-md-4">
									<label class="control-label col-md-4">Item No:</label>
									<div class="col-md-8">
										<select class="form-control itemNo chosen-select"
											name="itemNo">
											<option value="" selected></option> @foreach($itemNumbers as
											$id)
											<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
											@endforeach
										</select>
									</div>


								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Unique ID:</label>
									<div class="col-md-8">
										<input class="form-control" name="unique_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Agent:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="reported_by">
											<option value="" selected></option> @foreach($agents as
											$agent)
											<option value="{{$agent->id}}">{{$agent->first_name.'
												'.$agent->last_name}}</option> @endforeach
										</select>
									</div>
								</div>


								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateReported">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateReported"
												name="dateReported">

										</div>
									</div>
								</div>



								<div class=" col-md-3 ">
									<br>

									<button type="button" class="btn btn-primary issueTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning">
										<i class="fa fa-refresh"></i> Clear
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>
				<div class="table-responsive">
					<table id="issue"
						class="footable table table-bordered toggle-arrow-tiny"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>

								<th data-toggle="true">Item No</th>
								<th>Unique Id</th>
								<th>Item Type</th>
								<th>Brand</th>
								<th>Model</th>
								<th>Damage</th>
								<th>Item User </th>
								<th data-hide="all">Issue</th>
								<th>Reported By</th>
								<th>Date Reported</th>

							</tr>
						</thead>
						<tbody class="issueItem">
							@foreach($issueItems as $issue)
							<tr id="{{$issue->unique_id}}">
								<td><a href="/inventory/items/{{$issue->itemNo}}">{{$issue->itemNo}}</a></td>
								<td>{{$issue->unique_id}}</td>
								<td>{{$issue->itemType}}</td>
								<td>{{$issue->brand}}</td>
								<td>{{$issue->model}}</td>
								<td>{{$issue->damage}}</td>
								<td>{{$issue->itemUser}}</td>
								<td>{!!html_entity_decode($issue->issue)!!}</td>
								<td>{{$issue->first_name.' '.$issue->last_name}}</td>
								<td>{{$issue->created_at}}</td>

							</tr>
							@endforeach
						</tbody>

					</table>
				</div>

			</div>
		</div>
	</div>
</div>



<div id="issueReport" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Issue Report</h4>
			</div>

			<div class="ibox-content">
				<form class="issueItem" id="issueItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group issueItemNo">
								<label class="control-label"> Item No:</label> <select
									id="issueItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> 
									@foreach($itemNumbers as $id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger issueItemNo">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemUser">
								<label class="control-label"> Item User:</label> 
								<select class="form-control itemUser" name="item_user"> 
								<option value="" selected></option>
								@foreach($users as $user)
								<option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
								@endforeach
								</select>
									<span class="help-block text-danger itemUser">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemDamage">
								<label class="control-label"> Damage:</label> <input type="text"
									class="form-control itemDamage" name="damage"> <span
									class="help-block text-danger itemDamage">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group dateReported">
								<label class="control-label"> Date:</label>

								<div class="input-group date dateReported">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateReported"
										name="dateReported">

								</div>
								<span class="help-block text-danger dateReported">192.168.100.200</span>


							</div>
						</div>
						<div class="form-group col-lg-12 itemIssue">
							<label class="control-label "> Issue:</label> <input
								type="hidden" id="itemIssue" name="issue" rows="2">
							<div id="issueSummary"></div>
							<span class="help-block text-danger itemIssue">192.168.100.200</span>

						</div>

					</div>

				</form>
				<center>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <span
						class="sr-only">Loading...</span>

				</center>
				<div class="itemNotfound">
					<hr>
					<h2 class="text-center">Item is already reported</h2>
				</div>
				

			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary issueItem"
					type="button" data-style="zoom-in">
					<strong>Save</strong>
				</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>

<!-- Repair Report -->

<div id="repairReport" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Repair Report</h4>
			</div>

			<div class="ibox-content">
				<form class="repairItem" id="repairItem">
					{!! csrf_field() !!}
					<div class="row">
					<div class="col-lg-12">
						<div class="form-group repairItemNo">
							<label class="control-label">Item No:</label>
							
								<select id="repairItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> @foreach($itemNumbers as
									$id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger repairItemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
						<div class="form-group dateRepair">
							<label class="control-label "> Date Repaired:</label>
							
								<div class="input-group date dateRepair">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateRepair" name="dateRepair">

								</div>


								<span class="help-block text-danger dateRepair">192.168.100.200</span>

							</div>
						</div>

					</div>

				</form>
				
				

			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary repairItem"
					type="button" data-style="zoom-in">
					<strong>Save</strong>
				</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('form.itemInfo').hide();
	$('i.fa-pulse').hide();
	$('div.itemNotfound').hide();
	$('span.text-danger').hide();

	$('.input-group.date.dateReported').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});

	$('.input-group.date.dateRepair').datepicker({
    format : 'yyyy-mm-dd',
    todayBtn: "linked"
	});
	$('table#issue').footable();
	$('div#issueAdvancedSearch').hide();
	$('div#issueSummary').summernote({
		height: 150,
		minHeight: 150,             // set minimum height of editor
		maxHeight: 150,
		toolbar : [
					[
							'style',
							[
									'bold',
									'italic',
									'underline',
									'clear' ] ],
					[ 'fontname',
							[ 'fontname' ] ],
					[ 'fontsize',
							[ 'fontsize' ] ],
					[ 'color', [ 'color' ] ],
					[
							'para',
							[ 'ul', 'ol',
									'paragraph' ] ],
					 ]   });
	
	});

$('button#issueAdvancedSearch').click(function(){
	$('div#issueAdvancedSearch').slideToggle();
});

$('div.modal').on('hidden.bs.modal', function() {
	$('form.itemInfo').hide();
	$('i.fa-pulse').hide();
	$('div.itemNotfound').hide();
	$('span.text-danger').hide();
});
$('button.issueTicketSearch').click(function(){
	$.ajax({
		type : "get",
		url : "/inventory/issue/search",
		data : $('form.issueTicketSearch').serialize(),
		success: function(data){
			var table = $('table#issue').data('footable');
			$('tbody>tr').each(function(){
				table.removeRow(this);
				});

			if(data.response.length >= 1){
				$.each(data.response,function(i, v) {
					var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +"'>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
								"<td>" + v.itemType + "</td><td>"+ v.brand + "</td><td>" + v.model + "</td>"+
								"<td>" + v.damage + "</td><td>"+ v.itemUser +"</td><td>" + v.issue + "</td></td>" +
								"<td>" + v.first_name + " " + v.last_name + "</td>" + 
								"<td>" + v.created_at + "</td></tr>";
					table.appendRow(newRow);
					});
				}else{
				table.appendRow("<tr><td colspan='6' class='text-center'> No Data Found.</td></tr>");
				}
		}
	});
});

</script>

@endsection
