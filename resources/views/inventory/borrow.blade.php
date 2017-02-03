@extends('layouts.inventory_basic') @section('title', 'RS | Borrowed Item')
@section('header-page')

<div class="col-lg-10">
	<h2>Borrow Item</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><a href="/inventory/borrow"><strong>Borrow Item</strong></a></li>
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
							data-toggle="modal" data-target="#myModal">Report Item</button>
					</div>
					<div class="col-md-offset-6 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="borrowSearch"
								name="borrowSearch" placeholder="Search...">
							<div class="input-group-btn">
								<button id="borrowSearch" class="btn btn-primary" type="button">
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
				<div id="advancedSearch" class="panel panel-default collapse">
					<div class="panel-body">
						<form class="borrowTicketSearch form-horizontal">
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
									<label class="control-label col-md-4">Borrowee:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="borrowee">
											<option value="" selected></option> @foreach($agents as
											$agent)
											<option value="{{$agent->id}}">{{$agent->first_name.'
												'.$agent->last_name}}</option> @endforeach
										</select>
									</div>
								</div>
								</div>
								<div class="row">
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Borrower:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="borrower">
											<option value="" selected></option> @foreach($names as $name)
											<option value="{{$name->id}}">{{$name->first_name.'
												'.$name->last_name}}</option> @endforeach
										</select>
									</div>
								</div>


								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateBorrowed">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateBorrowed"
												name="dateBorrowed">

										</div>
									</div>
								</div>
								<div class=" col-md-4 ">
									<br>

									<button type="button"
										class="btn btn-primary borrowTicketSearch">
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
				<div class="spiner-example hide" id="spinner">
					<div class="sk-spinner sk-spinner-three-bounce">
						<div class="sk-bounce1"></div>
						<div class="sk-bounce2"></div>
						<div class="sk-bounce3"></div>
					</div>
				</div>

				<div class="table-responsive" id="borrowResult">

					<table id="borrow" class="table table-bordered table-hover"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>

								<th>Item No.</th>
								<th>Unique Identifier</th>
								<th>Item Type</th>
								<th>Brand</th>
								<th>Model</th>
								<th>Borrowee</th>
								<th>Borrower</th>
								<th>Station No</th>
								<th>Date Borrowed</th>

							</tr>
						</thead>
						<tbody id="borrow">
							
							@foreach($borrowedItems as $borrow) @if($borrow->dateBorrowed)
							<tr>
								<td><a href="/inventory/items/{{$borrow->itemNo}}">{{$borrow->itemNo}}</a></td>
								<td>{{$borrow->unique_id}}</td>
								<td>{{$borrow->itemType}}</td>
								<td>{{$borrow->brand}}</td>
								<td>{{$borrow->model}}</td>

								<td>{{$borrow->first_name.' '.$borrow->last_name}}</td>
								<td>{{$borrow->borrower}}</td>
								<td>{{$borrow->borrowerStationNo}}</td>
								<td>{{$borrow->dateBorrowed}}</td>

							</tr>
							@endif @endforeach


						</tbody>
						
					</table>
				</div>

				<!-- search result -->

				<table id="borrowSearchResult"
					class="table table-bordered table-hover hide" data-striping="false">
					<thead>
						<tr>

							<th>Item No</th>
							<th>Unique Identifier</th>
							<th>Item Type</th>
							<th>Brand</th>
							<th>Model</th>
							<th>Borrowee</th>
							<th>Borrower</th>
							<th>Station No</th>
							<th>Date Borrowed</th>

						</tr>
					</thead>
					<tbody id="borrowSearchResult">



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
				<h4 class="modal-title">Borrow Report</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-lg-8 b-r">
						<form class="borrowItem" id="borrowItem">
							{!! csrf_field() !!}

							<div class="form-group itemNo">
								<label class="control-label"> Item No:</label> <select
									id="borrowItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> @foreach($itemNumbers as
									$id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>

							<div class="form-group borrower">
								<label class="control-label"> Borrower:</label> <select
									class="form-control chosen-select" name="borrower">
									<option value="" selected></option> @foreach($names as $client)
									<option value="{{$client->id}}">{{$client->first_name.'
										'.$client->last_name}}</option> @endforeach
								</select> <span class="help-block text-danger borrower">192.168.100.200</span>

							</div>
							<div class="form-group stationNo">
								<label class="control-label"> Station No:</label> <input
									type="number" class="form-control" placeholder="Station No."
									name="stationNo"> <span
									class="help-block text-danger stationNo">192.168.100.200</span>

							</div>
							<div class="form-group dateBorrowed">
								<label class="control-label"> Date Borrowed:</label>

								<div class="input-group date dateBorrowed">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateBorrowed"
										placeholder="Date" name="dateBorrowed">

								</div>


								<span class="help-block text-danger dateBorrowed">192.168.100.200</span>


							</div>
					
					</div>

					</form>

					<div class="col-lg-4">
						<center>
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <span
								class="sr-only">Loading...</span>
						</center>

						<div class="itemNotfound">
							<h2 class="text-center">Item Not Found</h2>
						</div>
						
							<form class="itemInfo">


								<div class="form-group">
									<label class="control-label"> Unique Identifier :</label> <input
										type="text" class="form-control infoId"
										value="Unique Identifier" readonly>

								</div>
								<div class="form-group">
									<label class="control-label"> Item No :</label> <input
										type="text" class="form-control infoItemNo" value="Item No."
										readonly>

								</div>
								
								<div class="form-group">
									<label class="control-label"> Brand/Model :</label> <input
										type="text" class="form-control infoBrand" value="Brand"
										readonly>

								</div>
								<div class="form-group">
									<label class="control-label "> Item Type :</label> <input
										type="text" class="form-control infoItemType"
										value="Item Type" readonly>

								</div>


							</form>
						

					</div>
				</div>


			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary borrowItem"
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
<script type="text/javascript" src="/js/inventory/inventoryBorrow.js">
</script>
@endsection
