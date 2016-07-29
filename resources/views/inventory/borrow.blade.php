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

					<div class="table-responsive">
						<table
							class="table table-striped table-bordered table-hover dataTables-example">
							<thead>
								<tr>
									<th>Item Type</th>
									
									<th>Brand</th>
									<th>Model</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									
									<th>Borrowee</th>
									<th>Borrower</th>
									<th>Date Borrowed</th>
									<th>Action</th>
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
									<td>{{$borrow->dateBorrowed}}</td>
									<td></td>
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
								<input type="text" class="form-control" placeholder="Name"
									name="borrower">
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
								<label class="control-label col-lg-4"> Item Type :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoItemType"
										value="Item Type" readonly>
								</div>
							</div>
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Brand :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoBrand" value="Brand"
										readonly>
								</div>
							</div>
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Model :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoModel" value="Model"
										readonly>
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
