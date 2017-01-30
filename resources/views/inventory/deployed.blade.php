@extends('layouts.inventory_basic') @section('title', 'RS | Deploy')

@section('header-page')
<div class="col-lg-10">
	<h2>Deployed Items</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><strong>Deployed Items</strong></li>
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
							data-toggle="modal" data-target="#myModal">Deploy Item</button>
					</div>
					<div class="col-md-offset-6 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="deploySearch"
								name="deploySearch" placeholder="Search...">
							<div class="input-group-btn">
								<button id="deploySearch" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" id="deployAdvancedSearch"
									type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>

				</div>
				<div id="deployAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="deployTicketSearch form-horizontal">
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
									<label class="control-label col-md-4">Deploy by:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="deployed_by">
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
										<div class="input-group date dateDeployed">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateDeployed"
												placeholder="Date Deployed" name="dateDeployed">

										</div>
									</div>
								</div>



								<div class=" col-md-3 text-center">
									<br>

									<button type="button"
										class="btn btn-primary btn-sm deployTicketSearch">
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
				<div class="table-responsive" id="deployResult">
					<table id="deploy" class="table table-bordered table-hover"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>

								<th>Item No.</th>
							<th>Unique Identifier</th>
							<th>Item Type</th>
							<th>Brand</th>
							<th>Model</th>
							<th>Station No</th>
							<th>Deployed By</th>
							<th>Date Deployed</th>

							</tr>
						</thead>
						<tbody class="deployItem">
							@if(count($deployedItems) == 0)
							<tr class="text-center">
								<td colspan="8">No item found.</td>
							</tr>
							@endif
							@foreach($deployedItems as $item)
								<tr>
									<td><a href="/inventory/items/{{$item->itemNo}}">{{$item->itemNo}}</a></td>
									<td>{{$item->unique_id}}</td>
									<td>{{$item->itemType}}</td>
									<td>{{$item->brand}}</td>
									<td>{{$item->model}}</td>
									<td>{{$item->stationNo}}</td>
									<td>{{$item->first_name.' '.$item->last_name}}</td>
									<td>{{$item->created_at}}</td>
								</tr>
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
				<table id="deploySearchResult"
					class="table table-bordered table-hover hide" data-striping="false">
					<thead>
						<tr>

							<th>Item No.</th>
							<th>Unique Identifier</th>
							<th>Item Type</th>
							<th>Brand</th>
							<th>Model</th>
							<th>Deployed By</th>
							<th>Date Deployed</th>

						</tr>
					</thead>
					<tbody>


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

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Deploy Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal deployItem" id="deployItem">
					{!! csrf_field() !!} <input type="hidden">
					<div class="row">
						<div class="form-group col-lg-6 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<select id="deployItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> @foreach($itemNumbers as
									$id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
							
						</div>
						<div class="form-group col-lg-6 stationNo">
							
							<label class="control-label col-lg-4"> Station No:</label>
							<div class="col-lg-8">
								<input type="number" name="stationNo" class="form-control" >
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
					<h2 class="text-center">Item is already deployed</h2>
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



					</div>
				</form>

			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary deployItem"
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
<script type="text/javascript" src="/js/inventory/inventoryDeploy.js">
</script>
@endsection
