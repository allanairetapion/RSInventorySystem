@extends('inventory.inventory') @section('title', 'RS | Borrow')

@section('header-page')

<div class="col-lg-10">
	<h2>Borrow Form</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><strong>Borrow Form</strong></li>
	</ol>
</div>

@endsection @section('content')



	<div class="row">

		<div class="col-lg-12">

			<div class="ibox float-e-margins">

				<div class="ibox-title">

					<!-- Trigger the modal with a button -->
					<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#myModal">Report</button>

				</div>

				<div class="ibox-content">
<div id="advancedSearch" class=" gray-bg" style="padding: 5px;">
				<br>
				<form class="advancedTicket" method="GET" action="/inventory/borrow/search">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-3">
							<label class="control-label">Unique Id:</label> 
							<input type="text" class="form-control" name="unique_id">

						</div>
						<div class="col-md-3">
							<label class="control-label">Brand:</label> <select
								name="brand" class="form-control brand">
								<option value="" selected ></option>
								<option value="Hua" selected >Hua</option>
								
							</select>

						</div>
						<div class="col-md-3">
							<label class="control-label">Borrowee:</label> 
							<select class="form-control chosen-select" name="borrowee">
							<option value="" selected ></option>
								@foreach($clients as $client)
									<option value="{{$client->id}}"> {{$client->first_name.' '.$client->last_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Borrower:</label> 
							<select class="form-control chosen-select" name="borrower">
								<option value="" selected ></option>
								@foreach($clients as $client)
									<option value="{{$client->id}}"> {{$client->first_name.' '.$client->last_name}}</option>
								@endforeach
							</select>
						</div>
						
						</div>
						<div class="row">
						<div class="col-md-6">
						<label class="control-label">Date Range:</label>
						<div class="input-daterange input-group" id="datepicker">
									<span class="input-group-addon">From</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateStart" value=""/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateEnd" value="" />
                                </div>
						</div>
					
						
						
						<div class="col-md-offset-3 col-md-3 text-center">
							<br>

							<button type="submit"
								class="btn btn-primary">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="reset" class="btn btn-warning">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
					</div>

				</form>
				<br>
			</div>

					<div class="table-responsive">
					
						<table
							class="table table-bordered table-hover borrow">
							<thead>
								<tr>
									<th>Item Type</th>
									
									<th>Brand</th>
									<th>Model</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									
									<th>Borrowee</th>
									<th>Borrower</th>
									<th>Station No </th>
									<th>Date Borrowed</th>
									
								</tr>
							</thead>
							<tbody class="borrowItem">
							@foreach($borrowedItems as $borrow)
							<tr>
									<td>{{$borrow->itemType}}</td>
									<td>{{$borrow->brand}}</td>
									<td>{{$borrow->model}}</td>
									<td>{{$borrow->unique_id}}</td>
									<td>{{$borrow->itemNo}}</td>
									<td>{{$borrow->first_name.' '.$borrow->last_name}} </td>
									<td>{{$borrow->borrower}}</td>
									<td>{{$borrow->borrowerStationNo}}</td>
									<td>{{$borrow->dateBorrowed}}</td>
									
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
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Borrow Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal borrowItem" id="borrowItem">
				{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 unique_id">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control uniqueId"
									placeholder="Unique Identifier" name="unique_id">
									<span class="help-block text-danger unique_id">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoItemNo" placeholder="Item No."
									name="itemNo" readonly>
									<span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-7 borrower">
							<label class="control-label col-lg-4"> Borrower:</label>
							<div class="col-lg-8">
							<select class="form-control chosen-select" name="borrower">
								@foreach($clients as $client)
									<option value="{{$client->id}}"> {{$client->first_name.' '.$client->last_name}}</option>
								@endforeach
							</select>
								
									<span class="help-block text-danger borrower">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 stationNo">
							<label class="control-label col-lg-4"> Station No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control"
									placeholder="Station No." name="stationNo">
									<span class="help-block text-danger stationNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-7 dateBorrowed">
							<label class="control-label col-lg-4"> Date Borrowed:</label>
							<div class="col-lg-8">
							<div class="input-group date dateBorrowed">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateBorrowed" placeholder="Name"
									name="dateBorrowed">

									</div>
							
                   
									<span class="help-block text-danger dateBorrowed">192.168.100.200</span>
									
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
					<form class="form-horizontal itemInfo">
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
								<label class="control-label col-lg-4"> Company :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoCompany"
										value="Company" readonly>
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
							


						</div>
					</form>
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary borrowItem" type="button">
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
	$('form.itemInfo').hide();
	$('i.fa-pulse').hide();
	$('div.itemNotfound').hide();
	$('span.text-danger').hide();

	$('.input-group.date.dateBorrowed').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});

	$('table.borrow').dataTable({
		"bSort" : false,
		dom : '<"html5buttons"B>lTfgtip',
		buttons : [{
			text : 'Advanced Search',
			action : function() {
				$(
						'div#advancedSearch')
						.slideToggle();
			}
		},
			],
	});

	
});

$('div#advancedSearch').slideToggle();

$('#myModal').on('shown.bs.modal', function () {
	  $('.chosen-select', this).chosen();
	});

$(function() {
	$("input.uniqueId").keyup(function() {
		$("input.uniqueId").autocomplete({
			source : "{{URL('/uniqueId')}}",
			minLength : 1,
			appendTo: "#borrowItem"
		});
		
	});
}); 
</script>
@endsection
