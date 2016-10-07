@extends('inventory.inventory') @section('title', 'RS | Broken Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Broken Items</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Broken Items</strong></li>
	</ol>
</div>


@endsection @section('content')


<div class="row">
	<div class="col-lg-12">
		<div class="ibox">

			<div class="ibox-content">
				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-primary btn-sm "
							data-toggle="modal" data-target="#brokenReport">Report Broken
							Item</button>
					
						<div class="btn-group">
							<button data-toggle="dropdown"
								class="btn btn-primary btn-sm dropdown-toggle">
								Mark as <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a id="brokenMark" href="#">Repaired</a></li>
								<li><a id="brokenMark" href="#">Send to Supplier</a></li>
								<li><a id="brokenMark" href="#">For Displacement</a></li>
								<li><a id="brokenMark" href="#">No Budget to Fix</a></li>
								<li><a id="brokenMark" href="#">Can be Repaired</a></li>
								<li><a id="brokenMark" href="#">Cannot be Repaired</a></li>
							</ul>
						</div>
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
								<button class="btn btn-white" id="brokenAdvancedSearch"
									type="button">
									Search Options <span class="caret"></span>
								</button>
							</div>
						</div>
					</div>
				</div>


				<div id="brokenAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="brokenTicketSearch form-horizontal">
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
										<input class="form-control" name="unque_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Status:</label>
									<div class="col-md-8">
										<select name="status" class="form-control">
											<option value="" selected></option>
											<option value="Send to Supplier">Send to Supplier</option>
											<option value="For Displacement">For Displacement</option>
											<option value="No Budget to Fix">No Budget to Fix</option>
											<option value="Can be Repaired">Can be Repaired</option>
											<option value="Cannot be Repaired">Cannot be Repaired</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Agent:</label>
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
										<div class="input-group date dateBroken">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateBroken"
												name="dateBroken">

										</div>
									</div>
								</div>




								<div class="col-md-4">
									<br>
									<button type="button"
										class="btn btn-primary btn-sm brokenTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning btn-sm">
										<i class="fa fa-refresh"></i> Clear
									</button>
								</div>
							</div>



						</form>


					</div>
				</div>



				<table id="broken"
					class="footable table table-bordered table-hover toggle-arrow-tiny"
					data-filter="#filter" data-striping="false">
					<thead>
						<tr>
							<th data-toggle="true"><input type="checkbox" class="i-checks" />
								&nbsp; Item No.</th>
							<th>Unique ID</th>
							
							<th>Damage</th>
							<th>Item User</th>
							<th>Current Status</th>
							<th>Reported By</th>
							<th>Date Broken</th>
							<th data-hide="all">ItemType</th>
							<th data-hide="all">Brand</th>
							<th data-hide="all">Model</th>
							<th data-hide="all">Summary</th>
						</tr>
					</thead>
					<tbody id="brokenItem">
						@foreach($brokenItems as $brokenItem)

						<tr id="{{$brokenItem->itemNo}}">
							<td><input type="checkbox" class="i-checks brokenItem"
								value="{{$brokenItem->itemNo}}" /> &nbsp;<a
								href="/inventory/items/{{$brokenItem->itemNo}}">{{$brokenItem->itemNo}}</a></td>
							<td>{{$brokenItem->unique_id}}</td>

							<td>{{$brokenItem->damage}}</td>
							<td>{{$brokenItem->itemUser}}</td>
							<td>{{$brokenItem->brokenStatus}}</td>
							<td>{{$brokenItem->first_name.' '.$brokenItem->last_name}}</td>
							<td>{{$brokenItem->created_at}}</td>
							<td>{{$brokenItem->itemType}}</td>
							<td>{{$brokenItem->brand}}</td>
							<td>{{$brokenItem->model}}</td>
							<td>{!!html_entity_decode($brokenItem->brokenSummary)!!}</td>
						</tr>

						@endforeach

					</tbody>
					<tfoot>
						<tr>
						<td colspan="7" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
				</table>


			</div>
		</div>
	</div>
</div>


<div id="brokenReport" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Broken Item Report</h4>
			</div>

			<div class="modal-body">
				<form id="brokenItem" id="brokenItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="form-group brokenItemNo">
							<label class="control-label"> Item No:</label> <select
								id="brokenItemNo" class="form-control chosen-select"
								name="itemNo">
								<option value="" selected></option> @foreach($itemNumbers as
								$id)
								<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
								@endforeach
							</select> <span class="help-block text-danger brokenItemNo">192.168.100.200</span>

						</div>

						<div class="form-group brokenStatus">
							<label class="control-label"> Status:</label> <select
								name="status" class="form-control">
								<option value="" selected></option>
								<option value="Send to Supplier">Send to Supplier</option>
								<option value="For Displacement">For Displacement</option>
								<option value="No Budget to Fix">No Budget to Fix</option>
								<option value="Can be Repaired">Can be Repaired</option>
								<option value="Cannot be Repaired">Cannot be Repaired</option>
							</select> <span class="help-block text-danger brokenStatus">192.168.100.200</span>

						</div>
						<div class="form-group brokenitemUser">
							<label class="control-label">Item User:</label> <select
								type="text" name="item_user" class="form-control">
								<option value="" selected></option> @foreach($users as $user)
								<option value="{{$user->id}}">{{$user->first_name.'
									'.$user->last_name}}</option> @endforeach
							</select> <span class="help-block text-danger brokenitemUser">192.168.100.200</span>
						</div>


						<div class="form-group brokenDamage">
							<label class="control-label "> Damage:</label> <input type="text"
								name="damage" class="form-control"> <span
								class="help-block text-danger brokenDamage">192.168.100.200</span>

						</div>
						<div class="form-group  brokenSummary">
							<label class="control-label "> Summary:</label> <input
								type="hidden" name="summary" id="brokenSummary">
							<div id="brokenSummary"></div>

							<span class="help-block text-danger brokenSummary">192.168.100.200</span>


						</div>
						<div class="form-group dateBroken">
							<label class="control-label"> Date:</label>

							<div class="input-group date dateBroken">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
									type="text" class="form-control dateBroken" name="dateBroken">

							</div>


							<span class="help-block text-danger dateBroken">192.168.100.200</span>


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
				<button class="ladda-button btn btn-w-m btn-primary brokenItem"
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
	$('div#brokenAdvancedSearch').hide();
	$('table#broken').footable();
	$('div#brokenSummary').summernote({
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
					 ] 
	 });
	});

	

$('button#brokenAdvancedSearch').click(function(){
	$('div#brokenAdvancedSearch').slideToggle();
});
	
$('li a#brokenMark').click(function(){
	var items = [];
	var mark = $(this).text();

	$('input:checkbox.brokenItem:checked').each(function () {
	       if(this.checked){
		       items.push($(this).val());
		   		$('tr#'+$(this).val()+' td').eq(3).text(mark);
	       }
	  });
	console.log(items);
	  if(items.length == 0){
		  toastr.warning('No Input');
			return false;
		  }
	
	swal({
		title : 'Are you sure?',
		text : "This Action can't be undone",
		type : 'warning',
		showCancelButton : true,
		showCancelButton : true,
		closeOnConfirm : false,
		showLoaderOnConfirm : true,
		disableButtonsOnConfirm : true,
	}, function() {
		
		$.ajax({
			headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
			type : "PUT",
			url : "/inventory/brokenMark",
			data : {items : items, mark : mark},
			success: function(data){
				var table = $('table#broken').data('footable');
				swal({
					title:"Success",
					text: "Successfully Marked as "+ mark,
					type: "success",
					},function(){
						$('input:checkbox.brokenItem:checked').each(function () {
							if(mark != "Repaired"){
								
							   		$('tr#'+$(this).val()+' td').eq(3).text(mark);	   		
						       		
							}else{								 
									table.removeRow($('tr#'+$(this).val()));
								}
							table.redraw();
						});
						});
				
					}
			});
		});
	
	  
});

$('button.brokenTicketSearch').click(function(){
	$.ajax({
		type : "get",
		url : "/inventory/broken/search",
		data : $('form.brokenTicketSearch').serialize(),
		success: function(data){
			var table = $('table#broken').data('footable');
			$('tbody>tr').each(function(){
				table.removeRow(this);
				});

			if(data.response.length >= 1){
				$.each(data.response,function(i, v) {
					var newRow = "<tr id='" + v.itemNo + "'><td><input type='checkbox' class='i-checks brokenItem' value='" + v.itemNo +"' />" + 
					"<a href='/inventory/items/"+ v.itemNo +"'> " +v.itemNo + "</td><td>" + v.unique_id + " </td>"+
					"<td>" + v.damage + "</td><td>" + v.itemUser + "</td><td>" + v.brokenStatus + "</td>" +
					"<td>" + v.first_name + " " + v.last_name + "</td>" + 
					"<td>" + v.created_at + "</td><td>" + v.itemType + "</td>"+
					"<td>" + v.brand + "</td><td>" + v.model + "</td>" + 
					"<td>" + decodeURI(v.brokenSummary) + "</td></tr>";
					table.appendRow(newRow);
					
					
					});
				}else{
				table.appendRow("<tr><td colspan='6' class='text-center'> No Data Found.</td></tr>");
				}
			$('.i-checks').iCheck({
				checkboxClass : 'icheckbox_square-green',
				radioClass : 'iradio_square-green',
			});
		}
	
	});
});

</script>
@endsection

