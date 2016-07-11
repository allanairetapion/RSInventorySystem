@extends('inventory.inventory')

@section('title', 'RS | Broken Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Broken Items Form</h2>
	<ol class="breadcrumb">
		<li>
			<a href="index.html">Home</a>
		</li>

		<li class="active">
			<strong>Broken Items Form</strong>
		</li>
	</ol>
</div>

@endsection
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">

					<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
						Report
					</button>
					<div id="DataTables_Table_0_filter" class="dataTables_filter">
						<label>Advanced Search:
							<input type="search" class="form-control input-sm" placeholder="" id="advancedSrch" aria-controls="DataTables_Table_0">
						</label>

					</div>

<!-- Modal -->
					<div id="myModal" class="modal fade" role="dialog">
						<div class="modal-dialog">

							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">
										&times;
									</button>
									<h4 class="modal-title">Add borrow report</h4>
								</div>

								<form role="form" id="inputBroken" name="inputBroken"method="POST">

									{!! csrf_field() !!}

									<div class="ibox-content">

										<br>

										<div class="form-group">

											<div class="row" id="divSrch">

												<div class="col-lg-5 col-lg-offset-1">
													<label for="uniqueSrch">Unique Identifier</label>
													<input type="text" autocomplete="on"placeholder="Unique Identifier" class="form-control uniqueSrch" id="uniqueSrchBrkn" name="uniqueSrchBrkn" >

												</div>

												<div class="col-lg-5">
													<label>Item No.</label>
													<input type="text" placeholder="Item No." class="form-control" id="itemNoSrchBrkn" name="itemNoSrchBrkn">
												</div>
												<br>

											</div>

											<label>Item</label>
											<input type="text" placeholder="Item" class="form-control" required="" aria-required="true" id="item" name="item"  readonly>
										</div>
										<div class="form-group">
											<label>Model</label>
											<input type="text" placeholder="Model" class="form-control" id="model" name="model"   readonly>
										</div>

										<div class="form-group">
											<label>Brand</label>
											<input type="text" placeholder="Brand" class="form-control" id="brand"name="brand"  readonly >
										</div>
										<div class="form-group">
											<label>Unique Identifier</label>
											<input type="text" placeholder="Unique Identifier" class="form-control" id="unique"name="unique" readonly >

										</div>

										<div class="form-group">
											<label>Item No.</label>
											<input type="text" placeholder="Item No." class="form-control" id="itemNo"name="itemNo"  readonly>
										</div>

										<div class="form-group">
											<label>Support on duty</label>
											<input type="text" value="{{{ Auth::guard('inventory')->user()->first_name }}} {{{ Auth::guard('inventory')->user()->last_name }}}" class="form-control" id="brokenOnDuty" name="brokenOnDuty" readonly>
										</div>
										<div class="form-group">
											<label>Damage</label>
											<input type="text" placeholder="Damage" class="form-control" id="damage" name="damage">
										</div>

										<div class="form-group" id="data_1">
											<label class>Date being broken</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" placeholder="Pick Date" id="dateBroken"name="dateBroken">
											</div>
										</div>
						
										<div class="form-group">
									<select class="form-control" id="statusBox" name="statusBox">

										
									<option value="On supplier">On supplier</option>
									<option value="Broken">Broken</option>

									</select>
										</div>

										<div class="modal-footer">
											<button class="btn btn-w-m btn-primary" type="button" id="inputBrokenBtn">
												<strong>Save</strong>
											</button>
											<button type="button" class="btn btn-w-m btn-danger" id="cancelBtn" data-dismiss="modal">
												<strong>Cancel</strong>
											</button>
										</div>

								</form>
							</div>

						</div>

					</div>
				</div>


					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-wrench"></i> </a>
						<ul class="dropdown-menu dropdown-user">
							<li>
								<a href="#">Config option 1</a>
							</li>
							<li>
								<a href="#">Config option 2</a>
							</li>
						</ul>
						<a class="close-link"> <i class="fa fa-times"></i> </a>
					</div>
				</div>
				<div class="ibox-content">
<div class="tableBrokenLogs">
	
					<table class="table table-striped table-bordered table-hover brokenLogs" style="display:none">
						<thead>
							<tr>
								<th>Item</th>
									<th>Model</th>
									<th>Brand</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									<th>Support on duty</th>
									<th>Damage</th>
									<th>Date being broken</th>
									
							</tr>
						</thead>
						<tbody >
						
								@foreach($brokenLogs as $logs)
								<tr >
									<td>{{$logs->item_type}}</td>
									<td>{{$logs->model}}</td>
									<td>{{$logs->brand}}</td>
									<td>{{$logs->unique_identifier}}</td>
									<td>{{$logs->item_no}}</td>
									<td>{{$logs->support_on_duty}}</td>
									<td>{{$logs->damage}}</td>
									<td>{{$logs->date_being_broken}}</td>
																	</tr>
								@endforeach


						</tbody>
							<tfoot>
								<tr>
									<th>Item</th>
									<th>Model</th>
									<th>Brand</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									<th>Support on duty</th>
									<th>Damage</th>
									<th>Date being broken</th>
								
								</tr>
							</tfoot>

					</table>
</div>

					<br>
					<div class="table-responsive">
						<form role="form" id="statusUpdate" name="statusUpdate" method="POST">
						
						<table class="table table-striped table-bordered table-hover dataTables-example" >
							<thead>
								<tr>
									<th>Item</th>
									<th>Model</th>
									<th>Brand</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									<th>Support on duty</th>
									<th>Damage</th>
									<th>Date being broken</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="tbody">
								@foreach($brokenLatest as $latest)
								<tr >
									<td>{{$latest->item_type}}</td>
									<td>{{$latest->model}}</td>
									<td>{{$latest->brand}}</td>
									<td>{{$latest->unique_identifier}}</td>
									<td>{{$latest->item_no}}</td>
									<td>{{$latest->support_on_duty}}</td>
									<td>{{$latest->damage}}</td>
									<td>{{$latest->date_being_broken}}</td>
									<td>
									<select class="form-control statusSelection" data-id="{{$latest->unique_identifier}}">
										<option>{{$latest->status}}</option>

										@if ($latest->status == "On supplier")
										<option disabled=""></option>
										<option value="Broken">Broken</option>
										<option value="Available">Available</option>
										@elseif ($latest->status == "Broken")
										<option disabled="" ></option>
										<option value="On supplier">On supplier</option>
										<option value="Available">Availabe</option>
										
										@endif

									</select></td>
								</tr>
								@endforeach

							</tbody>
							<tfoot>
								<tr>
									<th>Item</th>
									<th>Model</th>
									<th>Brand</th>
									<th>Unique Identifier</th>
									<th>Item No.</th>
									<th>Support on duty</th>
									<th>Damage</th>
									<th>Date being broken</th>
									<th>Status</th>
								</tr>
							</tfoot>
						</table>
							<button type="button" id="statusBtn" class="btn btn-w-m btn-primary">
								Save
							</button>
					</form>
					</div>

				</div>
			</div>
		</div>
	</div>
	
	
	<script>
			
			$('.brokenLogs').DataTable({
                dom: '<"html5buttons"B>',
                buttons: [
                  
                    {extend: 'csv'},
                    {extend: 'excel', title: 'RS Broken logs excel file'},
                    {extend: 'pdf', title: 'RS Broken logs pdf file'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });
			
		</script>
	
	Remote Staff Inventory Management System
	@endsection

