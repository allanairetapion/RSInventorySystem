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
												<tr>
													<td>Laptop</td>
													<td>Dell i3</td>
													<td>Dell</td>
													<td>DELLDC4A2C</td>
													<td class="center">30</td>
													<td class="center">X</td>
													<td></td>
													<td></td>
													<td>James Monreal</td>
													<td>4/20</td>
													<td>
													<button>
														Update
													</button> &nbsp;
													<button>
														Delete
													</button></td>
												</tr>

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
					
						<!-- Mainly scripts -->
						<script src="js/jquery-2.1.1.js"></script>
						<script src="js/bootstrap.min.js"></script>
						<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
						<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
						<script src="js/plugins/jeditable/jquery.jeditable.js"></script>

						<script src="js/plugins/dataTables/datatables.min.js"></script>

						<!-- Custom and plugin javascript -->
						<script src="js/inspinia.js"></script>
						<script src="js/plugins/pace/pace.min.js"></script>

						<!-- Page-Level Scripts -->
						<script>
						
						</script>
Remote Staff Inventory Management System
@endsection

