@extends('layouts.inventory_basic') @section('title', 'RS |
InventoryDetails') @section('header-page')
<div class="col-lg-10">
	<h2>Inventory Details</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Inventory Details</strong></li>
	</ol>
</div>
@endsection @section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">

			<div class="ibox-content">

				<div class="row">
					<div class="col-lg-3">
						<select id="stockType" class="form-control">
							<option selected value="">All</option>
							<option value="In-stock">In-stock</option>
							<option value="Borrowed">Borrowed</option>
							<option value="With Issue">With Issue</option>
							<option value="Broken">Broken</option>
						</select>
					</div>
					<div class="col-lg-2">
						<button class="btn btn-primary btn-block" data-toggle="modal"
							data-target="#itemLevel">Level</button>
					</div>
					<div class="col-md-offset-3 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="detailedSearch"
								name="detailedSearch" placeholder="Search...">
							<div class="input-group-btn">
								<button id="detailedSearch" class="btn btn-primary"
									type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" id="itemAdvancedSearch"
									type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>
					</div>
					<div id="itemAdvancedSearch" class="panel panel-default ">
					<div class="panel-body">
						<form class="form-horizontal" id="itemAdvancedSearch">
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
									<label class="control-label col-md-4">Morning User:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="morning_user">
											<option value="" selected></option> @foreach($names as $name)
											<option value="{{$name->id}}">{{$name->first_name.'
												'.$name->last_name}}</option> @endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Night User:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="night_user">
											<option value="" selected></option> @foreach($names as $name)
											<option value="{{$name->id}}">{{$name->first_name.'
												'.$name->last_name}}</option> @endforeach
										</select>
									</div>
								</div>


								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control"
												name="dateArrived">

										</div>
									</div>
								</div>
								<div class=" col-md-4 text-center">
									<br>

									<button type="button"
										class="btn btn-primary" id="detailAdvanceSearch">
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
					
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li class="active"><a id="itemType" data-toggle="tab"
									href="#tab-1"> All</a></li>
								<li class=""><a id="itemType" data-toggle="tab" href="#tab-2">Laptop</a></li>
								<li class=""><a id="itemType" data-toggle="tab" href="#tab-3">Mouse</a></li>
								<li class=""><a id="itemType" data-toggle="tab" href="#tab-4">Headset</a></li>
								<li class=""><a id="itemType" data-toggle="tab" href="#tab-5">Projector</a></li>

							</ul>
							<div class="tab-content">
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
										<div class="table-responsive" id="stockAll">
											<table id="detailed" class="footable table table-hover"
												data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody>
													@foreach($items as $item)
													<tr>
														<td><a href="/inventory/items/{{$item->itemNo}}">{{$item->itemNo}}</a></td>
														<td>{{$item->unique_id}}</td>
														<td>{{$item->stationNo}}</td>
														<td>{{$item->brand}}</td>
														<td>{{$item->model}}</td>
														<td>{{$item->itemStatus}}</td>
														<td>{{$item->morning_FN.' '.$item->morning_LN}}</td>
														<td>{{$item->night_FN.' '.$item->night_LN}}</td>
														<td>{{$item->created_at}}</td>
													</tr>
													@endforeach
												</tbody>
												<tfoot>
													<tr>
														<td colspan="9">
															<ul class="pagination pull-right"></ul>
														</td>
													</tr>
												</tfoot>
											</table>

										</div>
									</div>
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table table-hover"
												data-striping="false" id="stockLaptop">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockLaptop">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table  table-hover"
												data-striping="false" id="stockMouse">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockMouse">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-4" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table table-hover"
												data-striping="false" id="stockHeadset">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockHeadset">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-5" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table table-hover"
												data-striping="false" id="stockProjector">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockProjector">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-6" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table  table-hover"
												data-striping="false" id="detailSearch">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="detailSearch">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
							</div>


						</div>

					
				
			</div>
		</div>
	</div>
</div>

<div id="itemInfo" class="modal inmodal fade" role="dialog">
	<div class="modal-dialog modal-sm">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>

			<div class="modal-body">
				<p>
					<strong>Status:</strong>&nbsp;<span id="itemInfoStatus"> </span>
				</p>
				<p>
					Brand: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Model: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Serial No.: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Date Arrived: &nbsp; <span id="itemInfoBrand"></span>
				</p>
			</div>

			<div class="modal-footer">

				<button class="btn btn-white btn-w-m" data-dismiss="modal">Done</button>

			</div>
		</div>

	</div>
</div>

<div id="itemLevel" class="modal inmodal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Summary</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="stocked"></div>
					</div>
				</div>
			</div>

			<div class="modal-footer">

				<button class="btn btn-primary btn-w-m" data-dismiss="modal">Done</button>

			</div>
		</div>

	</div>
</div>

<script>

</script>
@endsection @section('scripts')
<script type="text/javascript" src="/js/inventory/inventoryDetailed.js">
</script>
@endsection
