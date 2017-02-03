@extends('layouts.inventory_basic') @section('title', 'RS | Returned Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Return Form</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index/">Home</a></li>

		<li class="active"><a href="/inventory/return"><strong>Return Item</strong></a></li>
	</ol>
</div>


@endsection @section('content')


<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-2">
						<button type="button" class="btn btn-primary btn-sm btn-block"
							data-toggle="modal" data-target="#myModal">Return Item</button>
					</div>
					<div class="col-md-offset-6 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="returnSearch"
								name="returnSearch" placeholder="Search...">
							<div class="input-group-btn">
								<button id="returnSearch" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success"
									type="button" data-toggle="collapse" data-target="#advancedSearch">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>

				</div>
				<div id="advancedSearch" class="panel panel-default  collapse">
					<div class="panel-body">
						<form class="returnTicketSearch form-horizontal">
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
									<label class="control-label col-md-4">Borrower:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="borrower">
											<option value="" selected></option> @foreach($clients as
											$client)
											<option value="{{$client->id}}">{{$client->first_name.'
												'.$client->last_name}}</option> @endforeach
										</select>
									</div>
								</div>
								</div>
								<div class="row">
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Receiver:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="receiver">
											<option value="" selected hidden></option> @foreach($agents
											as $agent)
											<option value="{{$agent->id}}">{{$agent->first_name.'
												'.$agent->last_name}}</option> @endforeach
										</select>
									</div>
								</div>



								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateReturned">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateReturned"
												placeholder="Date Returned" name="dateReturned">

										</div>
									</div>
								</div>



								<div class=" col-md-4 text-center">
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
				<div class="spiner-example hide" id="spinner">
					<div class="sk-spinner sk-spinner-three-bounce">
						<div class="sk-bounce1"></div>
						<div class="sk-bounce2"></div>
						<div class="sk-bounce3"></div>
					</div>
				</div>
				<div class="table-responsive" id="returnResult">
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
						<tbody class="return">
							@foreach($returnedItems as $return) @if($return->dateReturned)
							<tr>
								<td><a href="/inventory/items/{{$return->itemNo}}">{{$return->itemNo}}</a></td>
								<td>{{$return->unique_id}}</td>
								<td>{{$return->itemType}}</td>
								<td>{{$return->brand}}</td>
								<td>{{$return->model}}</td>
								<td>{{$return->first_name.' '.$return->last_name}}</td>
								<td>{{$return->agent_FN.' '.$return->agent_LN}}</td>
								<td>{{$return->dateReturned}}</td>

							</tr>
							@endif @endforeach

						</tbody>
						
					</table>
				</div>
				<table id="returnSearchResult"
					class="table table-bordered table-hover hide" data-striping="false">
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
					<tbody id="returnSearchResult">


					</tbody>
					
				</table>
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
					{!! csrf_field() !!} <input type="hidden" name="borrower"
						class="infoBorrower">
					<div class="row">
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<select id="returnItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> @foreach($itemNumbers as
									$id)
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
@endsection @section('scripts')
<script type="text/javascript" src="/js/inventory/inventoryReturn.js">
</script>
@endsection
