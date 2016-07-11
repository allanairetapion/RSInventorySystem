@extends('inventory.inventory')

@section('title', 'Remote Staff Inventory System')

@section('header-page')

<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<div class="col-lg-10">
	<h2>Manage Accounts</h2>
	<ol class="breadcrumb">
		<li>
			<a href="/inventory/index">Home</a>
		</li>

		<li class="active">
			<strong>Authorizations</strong>
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
					<h5>Items List</h5>

				</div>

				<div class="ibox-content">

					<div class="table-responsive">
						<form role="form" id="usertypeUpdate" method="POST">
							{!! csrf_field() !!}
							<table class="table table-striped table-bordered table-hover dataTables-example items" id="items">

								<thead>
									<tr>

										<th>First Name</th>
										<th>Last Name</th>
										<th>Email Address</th>
										<th>Account Status</th>
										<th>User Type</th>

									</tr>
								</thead>
								<tbody>

									@foreach($users as $row)

									<td>{{$row->first_name}}</td>
									<td>{{$row->last_name}}</td>
									<td>{{$row->email}}</td>

									@if ($row->confirmed == "1")
									<td><strong>Confirmed</strong></td>
									@else
									<td></td>
									@endif

									<td>
									<select class="form-control userTypeSelection" data-id="{{$row->id}}">
										<option>{{$row->user_type}}</option>

										@if ($row->user_type == "Admin")
										<option disabled=""></option>
										<option value="Technical Support">Technical Support</option>
										@elseif ($row->user_type == "Technical Support")
										<option disabled="" ></option>
										<option value="Admin">Admin</option>
										@else
									
										<option value="Admin">Admin</option>
										<option value="Technical Support">Technical Support</option>
										@endif

									</select></td>

									</tr>
									@endforeach

								</tbody>

							</table>
							<button type="button" id="usertypeBtn" class="btn btn-w-m btn-primary">
								Save
							</button>
						</form>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
Remote Staff Inventory Management System

@endsection

<!--
@section('scripts')

@endsection
-->
