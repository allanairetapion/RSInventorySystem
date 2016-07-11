@extends('inventory.inventory')

@section('title', 'RS | Add Items')

@section('header-page')

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
			<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Add new item</h5>
				</div>
				<div class="ibox-content">

					<form role="form" id="addItemForm" method="POST">
						{!! csrf_field() !!}
						<div class="col-lg-6">
							<div class="form-group ">

								<label>Station no.</label>
								<input type="text" placeholder="Station no." name="station_no" class="form-control" autofocus="" aria-required="true">
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>Company</label>
								<!--	<input type="text" placeholder="Company" name="" class="form-control" name="">-->
								<select class="form-control" name="company">
									<option autofocus="" disabled selected >Select company</option>
									<option>Remote Staff</option>
									<option>Real Estate</option>

								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Item</label>
								<input type="text" placeholder="Item" name="item" class="form-control" id="item">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Model</label>
								<input type="text" placeholder="Model" name="model" class="form-control" id="model">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Brand</label>
								<input type="text" placeholder="Model" name="brand" class="form-control" id="brand">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Unique Identifier</label>
								<input type="text" placeholder="Unique Identifier" name="unique_identifier" class="form-control" id="unique_identifier">
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>Item no.</label>
								<input type="text" placeholder="Item no." name="item_no" class="form-control" id="item_no">
							</div>
								
						
						</div>
	
						<div class="col-lg-6">
							<div class="form-group" id="data_1">
								<label>Date Arrived</label>
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" class="form-control" name="date_arrived" placeholder="Pick Date" >
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group" id="data_1">
								<label>Date Deployed</label>
								<div class="input-group date" id="dateDep">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" class="form-control" id="date_deployed" name="date_deployed" placeholder="Pick Date" disabled="disabled">
								</div>
	
							</div>
						
					<div class="col-lg-13">
							<div class="form-group">
								<label>Night Shift</label>
								<input type="text" placeholder="Night Shift" class="form-control" name="night_shift" id="night_shift" disabled="disabled">
							</div>
	
						</div>
	
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>Morning Shift</label>
								<input type="text" placeholder="Morning Shift" class="form-control" name="morning_shift" id="morning_shift" disabled="disabled">
							</div>
						</div>

						<div class="col-lg-offset-4 col-lg-1">
<br>
								<button class="btn btn-w-m btn-primary" id="addItemBtn" type="button">
							<strong>Save</strong>
						</button>
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
						
						<div class="row">
						
						
	
	
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

					

				</div>
				</form>

			</div>

		</div>
	</div>


	Remote Staff Inventory Management System



	@endsection
	



