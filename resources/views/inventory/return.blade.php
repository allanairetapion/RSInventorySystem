@extends('inventory.inventory') 

@section('title', 'RS | Return')

@section('header-page')
<div class="col-lg-10">
	<h2>Return Form</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Return Item</strong></li>
	</ol>
</div>


@endsection @section('content')


<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">

				<button type="button" class="btn btn-primary" data-toggle="modal"
					data-target="#myModal">Report</button>
			</div>
			<div class="ibox-content">
				<div class="input-group m-b">
					<input type="text" class="form-control" id="filter"
						placeholder="Search...">
					<div class="input-group-btn">
						<button class="btn btn-white" id="returnAdvancedSearch"
							type="button">
							Search Options <span class="caret"></span>
						</button>
					</div>
				</div>
				<div id="returnAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="returnTicketSearch">
							{!! csrf_field() !!}


							<div class="row">
								<div class="col-md-3">
									<label class="control-label">Unique Id:</label> <select
										class="form-control uniqueId chosen-select" name="unique_id">
										<option value="" selected></option> 
										@foreach($unique_ids as $id)
										<option value="{{$id->unique_id}}">{{$id->unique_id}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-3">
									<label class="control-label">Borrower:</label> <select
										class="form-control chosen-select" name="borrower">
										<option value="" selected></option> 
										@foreach($clients as $client)
										<option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option> 
										@endforeach
									</select>
								</div>
								<div class="col-md-3">
									<label class="control-label">Receiver:</label> <select
										class="form-control chosen-select" name="receiver">
										<option value="" selected hidden></option> 
										@foreach($clients as $client)
										<option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option> 
										@endforeach
									</select>
								</div>



								<div class="col-md-3">
									<label class="control-label">Date Returned:</label>
									<div class="input-group date dateReturned">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
											type="text" class="form-control dateReturned"
											placeholder="Name" name="dateReturned">

									</div>
								</div>



								<div class=" col-md-3 text-center">
									<br>

									<button type="button"
										class="btn btn-primary returnTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning">
										<i class="fa fa-refresh"></i> Reset
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>

				<div class="table-responsive">
					<table id="return" class="table table-bordered table-hover"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>
								<th>Unique Identifier</th>
								<th>Item No.</th>
								<th>Item Type</th>

								<th>Brand</th>
								<th>Model</th>

								<th>Borrower</th>
								<th>Receiver</th>
								<th>Date Returned</th>

							</tr>
						</thead>
						<tbody class="returnItem">

							@foreach($returnedItems as $return) 
							@if($return->dateReturned)
							<tr>
								<td>{{$return->unique_id}}</td>
								<td>{{$return->itemNo}}</td>
								<td>{{$return->itemType}}</td>
								<td>{{$return->brand}}</td>
								<td>{{$return->model}}</td>

								<td>{{$return->borrower}}</td>
								<td>{{$return->first_name.' '.$return->last_name}}</td>
								<td>{{$return->dateReturned}}</td>

							</tr>
							@endif
							@endforeach

						</tbody>

					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Return Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal returnItem" id="returnItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 unique_id">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<select id="returnUniqueId" class="form-control chosen-select"
									name="unique_id">
									<option value="" selected></option> 
									@foreach($unique_ids as $id)
									<option value="{{$id->unique_id}}">{{$id->unique_id}}</option>
									@endforeach
								</select> <span class="help-block text-danger unique_id">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoItemNo"
									placeholder="Item No." name="itemNo" readonly> <span
									class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-7 dateReturned">
							<label class="control-label col-lg-4"> Date Received:</label>
							<div class="col-lg-8">
								<div class="input-group date dateReturned">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateReturned"
										placeholder="Name" name="dateReturned">

								</div>
								<span class="help-block text-danger dateReturned">192.168.100.200</span>
							</div>
						</div>

					</div>

				</form>
				<center>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <span
						class="sr-only">Loading...</span>

				</center>
				<div class="itemNotfound">
					<hr>
					<h2 class="text-center">Item Not Found</h2>
				</div>
				<form class="form-horizontal borrowInfo">
					<hr>
					<div class="row">
						<div class="form-group col-lg-7">
							<label class="control-label col-lg-4"> Unique Identifier :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoId"
									value="Unique Identifier" readonly>
							</div>
						</div>
						<div class="form-group col-lg-5">
							<label class="control-label col-lg-4"> Item No :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoItemNo"
									value="Item No." readonly>
							</div>
						</div>
						<div class="form-group col-lg-7">
							<label class="control-label col-lg-4"> Borrower :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoBorrower"
									value="Borrower" readonly>
							</div>
						</div>
						<div class="form-group col-lg-5">
							<label class="control-label col-lg-4"> Station No :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoStationNo"
									value="Station No." readonly>
							</div>
						</div>
						<div class="form-group col-lg-7">
							<label class="control-label col-lg-4"> Brand :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoBrand" value="Brand"
									readonly>
							</div>
						</div>
						<div class="form-group col-lg-5">
							<label class="control-label col-lg-4"> Model :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoModel" value="Model"
									readonly>
							</div>
						</div>
						<div class="form-group col-lg-7">
							<label class="control-label col-lg-4"> Item Type :</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoItemType"
									value="Item Type" readonly>
							</div>
						</div>
						<div class="form-group col-lg-7">
							<label class="control-label col-lg-4"> Date Borrowed:</label>
							<div class="col-lg-8">
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control infodateBorrowed" readonly>

								</div>

							</div>
						</div>



					</div>
				</form>

			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary returnItem"
					type="button">
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

<script>
$(document).ready(function() {
	$('form.borrowInfo').hide();
	$('i.fa-pulse').hide();
	$('div.itemNotfound').hide();
	$('span.text-danger').hide();
	$('div#advancedSearch').slideToggle();
	$('.input-group.date.dateReturned').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	$("div#returnAdvancedSearch").hide();
	$('table#return').footable();
	
});

$("button#returnAdvancedSearch").click(function(){
	$("div#returnAdvancedSearch").slideToggle();
	});
	
$(function() {
	$("input.uniqueId").keyup(function() {
		$("input.uniqueId").autocomplete({
			source : "{{URL('/uniqueId')}}",
			minLength : 1,
			appendTo: "#returnItem"
		});
		
	});
}); 

$('button.returnTicketSearch').click(function(){
	$.ajax({
		type : "get",
		url : "/inventory/return/search",
		data : $('form.returnTicketSearch').serialize(),
		success: function(data){
			var table = $('table#borrow').data('footable');
			$('tbody>tr').each(function(){
				table.removeRow(this);
				});
			
			if(data.response.length >= 1){
			$.each(data.response,function(i, v) {
				var newRow = "<tr><td>" + v.unique_id + "</td><td>" + v.itemNo + " </td>"+
				"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
				"<td>" + v.borrower + "</td><td>" + v.first_name + " " + v.last_name +"</td>"+
				"<td>" + v.dateReturned + "</td></tr>";
				
				});
			}else{
				table.appendRow("<tr><td colspan='8' class='text-center'> No Data Found.</td></tr>");
			}
		}
		
	});
});


</script>
@endsection

