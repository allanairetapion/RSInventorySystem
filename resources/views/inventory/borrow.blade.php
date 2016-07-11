@extends('inventory.inventory')

@section('title', 'RS | Borrow')

@section('header-page')

<div class="col-lg-10">
	<h2>Borrow Form</h2>
	<ol class="breadcrumb">
		<li>
			<a href="/inventory/index">Home</a>
		</li>

		<li class="active">
			<strong>Borrow Form</strong>
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

					<!-- Trigger the modal with a button -->
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

								<form role="form" id="inputBorrow" method="POST">

									{!! csrf_field() !!}

									<div class="ibox-content">

										<br>

										<div class="form-group">

											<div class="row" id="divSrch">

												<div class="col-lg-5 col-lg-offset-1">
													<label for="uniqueSrch">Unique Identifier</label>
													<input type="text" autocomplete="on"placeholder="Unique Identifier" class="form-control uniqueSrch" id="uniqueSrch" name="uniqueSrch" >

												</div>

												<div class="col-lg-5">
													<label>Item No.</label>
													<input type="text" placeholder="Item No." class="form-control" id="itemNoSrch" name="itemNoSrch">
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
											<label>Lent</label>
											<input type="text" value="{{{ Auth::guard('inventory')->user()->first_name }}} {{{ Auth::guard('inventory')->user()->last_name }}}" class="form-control" id="lent" name="lent" readonly="">
										</div>
										<div class="form-group">
											<label>Borrower</label>
											<input type="text" placeholder="Borrower" class="form-control" id="borrower"name="borrower">
										</div>

										<div class="form-group" id="data_1">
											<label class>Date Borrowed</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" placeholder="Pick Date" id="dateBorrowed"name="dateBorrowed">
											</div>
										</div>

										<div class="modal-footer">
											<button class="btn btn-w-m btn-primary" type="button" id="inputBorrowBtn">
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

					

				<div class="table-responsive">
					
					<table class="table table-striped table-bordered table-hover dataTables-example" >
						<thead>
							<tr>
								<th>Item</th>
								<th>Model</th>
								<th>Brand</th>
								<th>Unique Identifier</th>
								<th>Item No.</th>
								<th>Lent</th>
								<th>Borrower</th>
								<th>Date Borrowed</th>
							</tr>
						</thead>
						<tbody  id="tbodyBrw">
						
					
							@foreach($borrow as $row)
							<tr >
								<td>{{$row->item_type}}</td>
								<td>{{$row->model}}</td>
								<td>{{$row->brand}}</td>
								<td>{{$row->unique_identifier}}</td>
								<td>{{$row->item_no}}</td>
								<td>{{$row->support_on_duty}}</td>
								<td>{{$row->borrower}}</td>
								<td>{{$row->date_borrowed}}</td>

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


		<script>
			
			$('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>',
                buttons: [
                  
                    {extend: 'csv'},
                    {extend: 'excel', title: 'RS Borrow logs excel file'},
                    {extend: 'pdf', title: 'RS Borrow logs pdf file'},

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



<!--
<script src="/js/jquery-2.1.1.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/js/plugins/jeditable/jquery.jeditable.js"></script>

<script src="/js/plugins/dataTables/datatables.min.js"></script>

<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
-->

Remote Staff Inventory Management System

@endsection
