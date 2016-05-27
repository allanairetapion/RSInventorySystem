@extends('inventory.inventory')

@section('title', 'RS | Add Items')

@section('header-page')

<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

	<div class="col-lg-10">
		<h2>Add Items</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/inventory/index">Home</a>
			</li>

			<li class="active">
				<strong>Add Items</strong>
			</li>
		</ol>
	</div>


@endsection

@section('content')
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="ibox ">
				<div class="ibox-title">
					<h5>Add new item</h5>
				</div>
				<div class="ibox-content">
					<form role="form" id="form" novalidate="novalidate">
						<div class="form-group">
							<label>Station no.</label>
							<input type="text" placeholder="Station no." class="form-control" required="" aria-required="true">
						</div>
						<div class="form-group">
							<label>Brand</label>
							<input type="text" placeholder="Brand" class="form-control" name="">
						</div>

						<div class="form-group">
							<label>Company</label>
							<input type="text" placeholder="Company" class="form-control" name="">
						</div>
						<div class="form-group">
							<label>Item name</label>
							<input type="text" placeholder="Item name" class="form-control" name="">
						</div>
						<div class="form-group">
							<label>Model</label>
							<input type="text" placeholder="Model" class="form-control" name="">
						</div>

						<div class="form-group">
							<label>Unique identifier</label>
							<input type="text" placeholder="Unique identifier" class="form-control" name="">
						</div>
						<div class="form-group">
							<label>Item number</label>
							<input type="text" placeholder="Item number" class="form-control" name="">
						</div>
						<!--
						<div class="form-group">
						<label>Morning Shift</label>
						<input type="text" placeholder="Morning Shift" class="form-control" name="">
						</div>
						<div class="form-group">
						<label>Night Shift</label>
						<input type="text" placeholder="Night Shift" class="form-control" name="">
						</div>
						<div class="form-group">
						<label>Issue</label>
						<input type="text" placeholder="Issue" class="form-control" name="">
						</div>
						<div class="form-group">
						<label>Broken</label>
						<input type="text" placeholder="Broken" class="form-control" name="">
						</div>-->

						<div class="form-group" id="data_1">
							<label class>Date Arrived</label>
							<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control" value="Pick Date">
							</div>
						</div>

						<!--
						<div class="form-group">
						<label>Date Borrowed</label>
						<input type="text" placeholder="Date Borrowed" class="form-control" name="">
						</div>
						<div class="form-group">
						<label>Date Returned</label>
						<input type="text" placeholder="Date Returned" class="form-control" name="">
						</div>
						-->
							<button class="btn btn-w-m btn-primary" type="submit">
								<strong>Submit</strong>
							</button>
						</div>
					</form>
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
