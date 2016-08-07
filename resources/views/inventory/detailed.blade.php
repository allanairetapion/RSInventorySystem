@extends('inventory.inventory')

@section('title', 'RS | Detailed Inventory')

@section('header-page')
					<div class="col-lg-10">
						<h2>Detailed Inventory Form</h2>
						<ol class="breadcrumb">
							<li>
								<a href="index.html">Home</a>
							</li>

							<li class="active">
								<strong>Detailed Inventory Form</strong>
							</li>
						</ol>
					</div>
@endsection	
@section('content')
				
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
													<th>Station No.</th>
													<th>Company</th>
													<th>Item</th>
													<th>Model</th>
													<th>Brand</th>
													<th>Unique Identifier</th>
													<th>Item No.</th>
													<th>Morning Shift</th>
													<th>Night Shift</th>
													<th>Issue</th>
													<th>Broken</th>
													<th>Date Arrived</th>
													<th>Last Borrowed</th>
													<th>Last Returned</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<tr class="gradeX">
													<th>text</th>
													<th>RemoteStaff</th>
													<td>Laptop</td>
													<td>dell i3</td>
													<td>Dell</td>
													<td>DELLDC4A2C</td>
													<td class="center">30</td>
													<td class="center">X</td>
													<td></td>
													<td>Hard disk failure</td>
													<td>text</td>
													<td>text</td>
													<td>text</td>
													<td>text</td>
													<td>
													<button>
														Update
													</button> &nbsp;
													<button>
														Delete
													</button></td>
												</tr>

											</tbody>
											
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
					

@endsection
