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

								<div class="ibox-content">

									<form role="form" id="report" novalidate="novalidate">

										<div>
											<label>Unique Identifier</label>
											<input type="text" placeholder="Unique Identifier" class="form-control" id="unique">
										</div>
										<div >
											<label>Item No.</label>
											<input type="text" placeholder="Item No." class="form-control" id="itemNo">
										</div>

										<div class="form-group">
											<label>Item</label>
											<input type="text" placeholder="Item" class="form-control" required="" aria-required="true" id="item">
										</div>
										<div class="form-group">
											<label>Model</label>
											<input type="text" placeholder="Model" class="form-control" id="model">
										</div>

										<div class="form-group">
											<label>Brand</label>
											<input type="text" placeholder="Brand" class="form-control" id="brand">
										</div>
										<div class="form-group">
											<label>Unique Identifier</label>
											<input type="text" placeholder="Unique Identifier" class="form-control" id="unique">
										</div>
										<div class="form-group">
											<label>Item No.</label>
											<input type="text" placeholder="Item No." class="form-control" id="itemNo">
										</div>

										<div class="form-group">
											<label>Lent</label>
											<input type="text" placeholder="Lent" class="form-control" id="lent">
										</div>
										<div class="form-group">
											<label>Borrower</label>
											<input type="text" placeholder="Borrower" class="form-control" id="borrower">
										</div>

										<div class="form-group" id="data_1">
											<label class>Date Borrowed</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" value="Pick Date" id="dateBorrowed">
											</div>
										</div>

										<div>

										</div>
									</form>
								</div>

								<div class="modal-footer">
									<button class="btn btn-w-m btn-primary" type="submit">
										<strong>Save</strong>
									</button>
									<button type="button" class="btn btn-w-m btn-danger" data-dismiss="modal">
										<strong>Cancel</strong>
									</button>
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
									<th>Morning Shift</th>
									<th>Night Shift</th>
									<th>Lent</th>
									<th>Borrower</th>
									<th>Date Borrowed</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

								@foreach($borrow as $row)
								<tr>
									<td>{{$row->item}}</td>
									<td>{{$row->model}}</td>
									<td>{{$row->brand}}</td>
									<td>{{$row->unique_identifier}}</td>
									<td>{{$row->item_no}}</td>
									<td>{{$row->morning_shift}}</td>
									<td>{{$row->night_shift}}</td>
									<td>{{$row->lent}}</td>
									<td>{{$row->borrower}}</td>
									<td>{{$row->date_borrowed}}</td>
									<td>
									<button>
										Update
									</button> &nbsp;
									<button>
										Delete
									</button></td>

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
									<th>Morning Shift</th>
									<th>Night Shift</th>
									<th>Lent</th>
									<th>Borrower</th>
									<th>Date Borrowed</th>
									<th>Action</th>
								</tr>
							</tfoot>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
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


				


<script>$('')</script>

Remote Staff Inventory Management System

@endsection
