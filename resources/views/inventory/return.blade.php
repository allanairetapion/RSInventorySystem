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
			<div class="ibox-content">
			<div class="row">
			<div class="col-md-2"><button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
					data-target="#myModal">Return Item</button></div>
			<div class="col-md-10">
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
				</div>
			</div>
				<div id="returnAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="returnTicketSearch form-horizontal">
							{!! csrf_field() !!}


							<div class="row">
								<div class="col-md-4">
									<label class="control-label col-md-4">Item No:</label> 
									<div class="col-md-8">
									<select class="form-control itemNo chosen-select" name="itemNo">
										<option value="" selected></option> 
										@foreach($itemNumbers as $id)
										<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
										@endforeach
									</select></div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Unique ID:</label>
									<div class="col-md-8">
										<input class="form-control" name="unique_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Borrower:</label>
									<div class="col-md-8">
									 <select class="form-control chosen-select" name="borrower">
										<option value="" selected></option> 
										@foreach($clients as $client)
										<option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option> 
										@endforeach
									</select>
									</div>
								</div>
								<div class="col-md-4">
								<br>
									<label class="control-label col-md-4">Receiver:</label> 
									<div class="col-md-8">
									<select class="form-control chosen-select" name="receiver">
										<option value="" selected hidden></option> 
										@foreach($agents as $agent)
										<option value="{{$client->id}}">{{$agent->first_name.' '.$agent->last_name}}</option> 
										@endforeach
									</select>
									</div>
								</div>



								<div class="col-md-4">
								<br>
									<label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
									<div class="input-group date dateReturned">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
											type="text" class="form-control dateReturned"
											placeholder="Date Returned" name="dateReturned">

									</div>
									</div>
								</div>



								<div class=" col-md-3 text-center">
									<br>

									<button type="button"
										class="btn btn-primary btn-sm returnTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning btn-sm">
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
								
								<th>Item No.</th>
								<th>Unique Identifier</th>
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
								<td><a href="/inventory/items/{{$return->itemNo}}">{{$return->itemNo}}</a></td>
								<td>{{$return->unique_id}}</td>
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
<tfoot>
						<tr>
						<td colspan="8" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
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
					<input type="hidden" name="borrower" class="infoBorrower">
					<div class="row">
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<select id="returnItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> 
									@foreach($itemNumbers as $id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger itemNo">192.168.100.200</span>
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
					<h2 class="text-center">Item is already returned</h2>
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
								<input type="text" class="form-control infoBorrowerName"
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
			var table = $('table#return').data('footable');
			$('tbody>tr').each(function(){
				table.removeRow(this);
				});
			
			if(data.response.length >= 1){
			$.each(data.response,function(i, v) {
				var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +"'>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
				"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
				"<td>" + v.borrower + "</td><td>" + v.first_name + " " + v.last_name +"</td>"+
				"<td>" + v.created_at + "</td></tr>";
				table.appendRow(newRow);
				});
			
			
			}else{
				table.appendRow("<tr><td colspan='8' class='text-center'> No Data Found.</td></tr>");
			}
		}
		
	});
});


</script>
@endsection

