@extends('inventory.inventory')

@section('title', 'RS | Return')

@section('header-page')
					<div class="col-lg-10">
						<h2>Return Form</h2>
						<ol class="breadcrumb">
							<li>
								<a href="index.html">Home</a>
							</li>

							<li class="active">
								<strong>Return Form</strong>
							</li>
						</ol>
					</div>
					
				
	@endsection	
@section('content')
				
				
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">

									<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#myModal">Report</button>
								</div>
								<div class="ibox-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover dataTables-example" >
											<thead>
												<tr>
													<th>Item Type</th>
													
													<th>Brand</th>
													<th>Model</th>
													<th>Unique Identifier</th>
													<th>Item No.</th>
													<th>Borrower</th>
													<th>Receiver</th>
													<th>Date Returned</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody class="returnItem">
												
													@foreach($returnedItems as $return)
													<tr>
													<td>{{$return->itemType}}</td>
													<td>{{$return->brand}}</td>
													<td>{{$return->model}}</td>
													<td>{{$return->unique_id}}</td>
													<td>{{$return->itemNo}}</td>
													<td>{{$return->borrower}}</td>													
													<td>{{$return->first_name.' '.$return->last_name}}</td>
													<td>{{$return->dateReturned}}</td>
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
				<form class="form-horizontal returnItem" id="returnItem">
				{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 unique_id">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control borrowUniqueId"
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
						<div class="form-group col-lg-7 dateReturned">
							<label class="control-label col-lg-4"> Date Received:</label>
							<div class="col-lg-8">
								<div class="input-group date dateReturned">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateReturned" placeholder="Name"
									name="dateReturned">

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
						<hr>hello
						
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
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control infodateBorrowed" readonly>

									</div>
									
							</div>
						</div>
							


						</div>
					</form>
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary returnItem" type="button">
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

	$('.input-group.date.dateReturned').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
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
</script>		
@endsection

