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

<div class="wrapper wrapper-content animated fadeInRight">

	<div class="row">

		<div class="col-lg-12">

			<div class="ibox float-e-margins">

				<div class="ibox-title">

					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>

						<a class="close-link"> <i class="fa fa-times"></i> </a>
					</div>
				</div>
				<!-- Trigger the modal with a button -->

				<div class="ibox-content">
					<!--
					<div class="col-lg-6">
					<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
					Report
					</button>
					</div>-->

					<div class="table-responsive">

						<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
						
							<div id="DataTables_Table_0_filter" class="dataTables_filter">
								<label>Unique Identifier:
									<input type="search" class="form-control input-sm" placeholder="" id="uniqueIdentifierSearch" aria-controls="DataTables_Table_0">
								</label>
								
									<label>Item no.:
									<input type="search" class="form-control input-sm" placeholder="" id="itemNoSearch" aria-controls="DataTables_Table_0">
								</label>
									<label>Date Borrowed:
									<input type="search" class="form-control input-sm" placeholder="" id="dateBorrowedSearch" aria-controls="DataTables_Table_0">
								</label>
								<hr>
								</hr>
							</div>
							<button type="button" class="btn btn-primary" id="reportBtn" onclick="javascript:toggleDiv('myContent');">

								Report
							</button>
						</div>

						<div id="myContent" >
							<label style="display: none" id="borrowLbl">Borrowed Items</label>
							
							<table class="table table-striped table-bordered table-hover" style="display: none" id="tbody">
								<thead>
									<tr>
										<th >Item Type</th>
										<th>Model</th>
										<th>Brand</th>
										<th >Unique Identifier</th>
										<th>Item No.</th>
										<th>Morning Shift</th>
										<th>Night Shift</th>
										<th>Lent By</th>
										<th>Borrower</th>
										<th>Date Borrowed</th>

									</tr>
								</thead>
								<tbody >

									@foreach($borrow_tbl as $row)
									<tr >

										<td><a id="#itemTypeLink"  data-toggle="modal" data-target="#{{$row->unique_identifier}}">{{$row->item_type}}</a></td>
										<td><a id="#itemModelLink"  data-toggle="modal" data-target="#{{$row->unique_identifier}}">{{$row->model}}</a></td>
										<td>{{$row->brand}}</td>
										<td id="uniqueI">{{$row->unique_identifier}}</td>
										<td>{{$row->item_no}}</td>
										<td>{{$row->morning_shift}}</td>
										<td>{{$row->night_shift}}</td>
										<td>{{$row->support_on_duty}}</td>
										<td>{{$row->borrower}}</td>
										<td>{{$row->date_borrowed}}</td>

									</tr>

									<!-- Modal -->
									<div id="{{$row->unique_identifier}}" class="modal fade" role="dialog">
										<div class="modal-dialog">

											<!-- Modal content-->
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														&times;
													</button>
													<h4 class="modal-title">Add Report</h4>
												</div>


												<div class="modal-body">

													<form id="returnForm" method="POST">
														{!! csrf_field() !!}
														<div class="form-group">
															<label>Item Type</label>
															<input type="text" placeholder="Item Type"  class="form-control" name="itemTypeText" id="itemType" readonly="" value="{{$row->item_type}}">
														</div>
														<div class="form-group">
															<label>Model</label>
															<input type="text" placeholder="Model" class="form-control" name="modelText" id="model" readonly="" value="{{$row->model}}">
														</div>

														<div class="form-group">
															<label>Brand</label>
															<input type="text" placeholder="Brand" class="form-control" name="brandText" id="brand" readonly="" value="{{$row->brand}}">
														</div>
														<div class="form-group">
															<label>Unique Identifier</label>
															<input type="text" placeholder="Unique Identifier" class="form-control" name="uniqueIdentifier" id="uniqueSrch"  readonly="" value="{{$row->unique_identifier}}">
														</div>
														<div class="form-group">
															<label>Item No.</label>
															<input type="text" placeholder="Item No." class="form-control" name="itemNoText" id="itemNo" readonly="" value="{{$row->item_no}}">
														</div>

														<div class="form-group">
															<label>Lent By</label>
															<input type="text" placeholder="Lent By" class="form-control" name="lent" id="lent-id" readonly="" value="{{$row->support_on_duty}}">
														</div>
														<div class="form-group">
															<label>Borrower</label>
															<input type="text" placeholder="Borrower" class="form-control" id="borrower-id" name="borrower" readonly="" value="{{$row->borrower}}">
														</div>

														<div class="form-group">
															<label>Date Borrowed</label>

															<input type="text" class="form-control" placeholder="Date Borrowed" id="dateBorrowed-id" name="dateBorrowed" readonly="" value="{{$row->date_borrowed}}">

														</div>

															<div class="form-group" id="data_1">
														<label class>Date Returned</label>
														<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control" placeholder="Pick Date" id="dateReturned-id" name="dateReturned">
														</div>
														</div>
														<div class="form-group">
															<label>Receiver</label>
															<input type="text" readonly="" placeholder="Receiver" style="text-transform: capitalize;" class="form-control" name="receiver" value="{{{ Auth::guard('inventory')->user()->first_name }}} {{{ Auth::guard('inventory')->user()->last_name }}}">
														</div>

														<div>
															<button class="btn btn-sm btn-primary m-t-n-xs" type="button" id="returnBtn">
																<strong>Save</strong>
															</button>
														</div>
											
													<div class="modal-body"></div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">
															Close
														</button>
													</div>
												</div>

											</div>
										</div>

										@endforeach
										</form>
								</tbody>
								<tfoot>
									<tr>
										<th>Item</th>
										<th>Model</th>
										<th>Brand</th>
										<th>Unique Identifier</th>
										<th>Item No.</th>
										<th>Morning Shift</th>
										<th>Night Shift</th>
										<th>Lent</th>
										<th>Borrower</th>
										<th>Date Borrowed</th>

									</tr>
								</tfoot>

							</table>
						</div>

					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-12">

			<div class="ibox float-e-margins">

				<div class="ibox-title">
					<label>Returned Items Log</label>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>

						<a class="close-link"> <i class="fa fa-times"></i> </a>
					</div>
				</div>
				<!-- Trigger the modal with a button -->

				<div class="ibox-content">
					<!--
					<div class="col-lg-6">
					<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
					Report
					</button>
					</div>-->

					<div class="table-responsive">

						<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
						
							<div id="DataTables_Table_0_filter" class="dataTables_filter">
								<label>Advanced Search:
									<input type="search" class="form-control input-sm" placeholder="" id="advancedSearch" aria-controls="DataTables_Table_0">
								</label>
							</div>

						</div>

						<table class="table table-striped table-bordered table-hover dataTables-example">
							<thead>
								<tr>
									<th>Item ID</th>

									<th>Unique Identifier</th>
									<th>Date Returned</th>

									<th>Receiver</th>
								</tr>
							</thead>
							<tbody>

								@foreach($return_tbl as $row)
								<tr >
									<td>{{$row->id}}</td>
									<td>{{$row->unique_identifier}}</td>
									<td>{{$row->date_returned}}</td>
									<td>{{$row->receiver}}</td>
								</tr>

					</div>

					@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th>Item ID</th>

							<th>Unique Identifier</th>
							<th>Date Returned</th>

							<th>Receiver</th>

						</tr>
					</tfoot>

					</table>
				</div>

			</div>

		</div>
	</div>
</div>
</div>


	<script>
			
			$('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>',
                buttons: [
                  
                    {extend: 'csv'},
                    {extend: 'excel', title: 'RS Return logs excel file'},
                    {extend: 'pdf', title: 'RS Return logs pdf file'},

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

	<script type="text/javascript">
		function toggleDiv() {
			$('#borrowLbl').toggle(200);
			$('#tbody').toggle(400);
	
		}
	</script>
	
	
	




<script>
	   $("#uniqueIdentifierSearch").keyup(function(){
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#table tr td:#uniqueI"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
               $(this).hide();
            else
               $(this).show();                
        });
    }); 

</script>



Remote Staff Inventory Management System

@endsection

