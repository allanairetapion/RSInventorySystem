@extends('inventory.inventory') @section('title', 'RS | Add Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Add Items</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><strong>Add Items</strong></li>
	</ol>
</div>
<div class="col-lg-2"></div>

@endsection @section('content')

	<div class="row">
		<div class="col-lg-12">
			<div class="ibox ">
				<div class="ibox-title">
					<h5>Add new item</h5>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal addItem">
						{!! csrf_field() !!}
						<div class="row">
							<div class="form-group col-lg-7 unique_id">
								<label class="control-label col-lg-3"> Unique Identifier:</label>
								<div class="col-lg-9">
									<input type="text" class="form-control"
										placeholder="Unique Identifier" name="unique_id" required> <span
										class="help-block text-danger unique_id">192.168.100.200</span>
								</div>

							</div>
							<div class="form-group col-lg-5 itemNo">
								<label class="control-label col-lg-4"> Item No:</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Item No."
										name="itemNo" required> <span
										class="help-block text-danger itemNo">192.168.100.200</span>
								</div>

							</div>
							<div class="form-group col-lg-7 company">
								<label class="control-label col-lg-3"> Company:</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" placeholder="Company"
										name="company" required> <span
										class="help-block text-danger company">192.168.100.200</span>
								</div>
							</div>
							<div class="form-group col-lg-5 stationNo">
								<label class="control-label col-lg-4"> Station No:</label>
								<div class="col-lg-8">
									<input type="text" class="form-control"
										placeholder="Station No." name="stationNo" required> <span
										class="help-block text-danger stationNo">192.168.100.200</span>
								</div>
							</div>
							<div class="form-group col-lg-7 brand">
								<label class="control-label col-lg-3"> Brand:</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" placeholder="Brand"
										name="brand" required> <span
										class="help-block text-danger brand">192.168.100.200</span>
								</div>
							</div>
							<div class="form-group col-lg-7 model">
								<label class="control-label col-lg-3"> Model:</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" placeholder="Model"
										name="model" required> <span
										class="help-block text-danger model">192.168.100.200</span>
								</div>
							</div>
							<div class="form-group col-lg-7 itemType">
								<label class="control-label col-lg-3"> Item Type:</label>

								<div class="col-lg-9">
									<input type="text" class="form-control" placeholder="Item Type"
										name="itemType" required> <span
										class="help-block text-danger itemType">192.168.100.200</span>
								</div>
							</div>
							<div class="form-group col-lg-7 dateArrived">
								<label class="control-label col-lg-3 "> Date Arrived:</label>
								<div class="col-lg-9">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
											type="text" class="form-control dateArrived"
											name="dateArrived" required>

									</div>
									<span class="help-block text-danger dateArrived">192.168.100.200</span>
								</div>

							</div>
							<div class="col-lg-12">
								<button class="btn btn-w-m btn-primary pull-right addItem"
									type="button">
									<strong>Submit</strong>
								</button>

							</div>


						</div>



					</form>
				</div>
			</div>

		</div>

	</div>

<script>
	$(document).ready(function(){
			$('span.text-danger').hide();
		});
</script>
@endsection

