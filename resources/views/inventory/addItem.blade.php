@extends('layouts.inventory_dropzone') @section('title', 'RS | Add Items')

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

			<div class="ibox-content">
				<div class="row">
					<div class="col-md-2">
						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#addItem">Add Item</button>
					</div>
					<div class="col-md-offset-6 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="addSearch"
								placeholder="Search...">
							<div class="input-group-btn">
								<button tabindex="-1" class="btn btn-primary" type="button" id="addSearch">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" data-toggle="collapse" data-target="#advancedSearch"
									type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>
				</div>
				<div id="advancedSearch" class="panel panel-default collapse">
					<div class="panel-body">
						<form class="form-horizontal" id="addItemSearch">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-md-4">
									<label class="control-label col-md-4">Item No:</label>
									<div class="col-md-8">
										<input type="text" name="itemNo" class="form-control">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Unique ID:</label>
									<div class="col-md-8">
										<input class="form-control" name="unique_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Item Type:</label>
									<div class="col-md-8">
										<select class="form-control" name="itemType">
											<option value="" selected></option> @foreach($itemTypes as
											$itemType)
											<option value="{{$itemType->itemType}}">{{$itemType->itemType}}</option>
											@endforeach
										</select>
									</div>
								</div>
								</div>
								<div class="row">
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Brand:</label>
									<div class="col-md-8">
										<input class="form-control" name="brand" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Model:</label>
									<div class="col-md-8">
										<input class="form-control" name="model" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateBorrowed">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateBorrowed"
												name="dateArrived">

										</div>
									</div>
								</div>



								<div class=" col-md-4 ">
									<br>

									<button type="button" class="btn btn-primary btn-w-m"
										id="addItemSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning btn-w-m">
										<i class="fa fa-refresh"></i> Clear
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>
				<table id="itemList" class="table table-bordered"
					data-filter="#filter" data-striping="false">
					<thead>
						<tr>
							<th>Item Tag</th>
							<th>Item Type</th>
							<th>Serial No</th>
							<th>Service Tag</th>
							<th>Brand + Model</th>
							<th>Date Arrived</th>
						</tr>
					</thead>
					<tbody id="itemList">
						@foreach($items as $item)
						<tr>
							<td>{{$item->itemNo}}</td>
							<td>{{$item->itemType}}</td>
							<td>{{$item->unique_id}}</td>
							<td>{{$item->serviceTag}}</td>
							<td>{{$item->brand}} - {{$item->model}}</td>
							<td>{{$item->created_at}}</td>
						</tr>
						@endforeach
					</tbody>
					
				</table>

			</div>
		</div>
	</div>


</div>
<div id="addItem" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Item</h4>
			</div>

			<div class="ibox-content">
				<form class="addItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group itemTag">
								<label class="control-label"> Item Tag:</label> <input type="text"
									class="form-control" placeholder="Item Tag" name="itemTag"
									id="itemTag"> <span class="help-block text-danger itemTag">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group brand">
								<label class="control-label"> Brand:</label> <input type="text"
									class="form-control" placeholder="Brand" name="brand"
									id="brand" required> <span class="help-block text-danger brand">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group model">
								<label class="control-label"> Model:</label> <input type="text"
									class="form-control" placeholder="Model" name="model"
									id="model" required> <span class="help-block text-danger model">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group serial_no">
								<label class="control-label">Serial No:</label> <input
									type="text" class="form-control" placeholder="Serial Number"
									id="serial_no" name="serial_no" required> <span
									class="help-block text-danger serial_no">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group serviceTag">
								<label class="control-label">Service Tag:</label> <input
									type="text" class="form-control" placeholder="Service Tag"
									id="serial_no" name="serviceTag" required> <span
									class="help-block text-danger serviceTag">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group company">
								<label class="control-label"> Company:</label> 
								<input name="company" id="company" class="form-control" type="text">
								 <span class="help-block text-danger company">192.168.100.200</span>
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group specification">
								<label class="control-label"> Specification:</label> <input
									type="hidden" id="specification" name="specification">
								<div id="itemDescription"></div>
								<span class="help-block text-danger specification">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemType">
								<label class="control-label"> Item Type:</label> <select
									class="form-control" name="itemType" id="itemType">
									<option value="" selected></option>
									<option value="Laptop">Laptop</option>
									<option value="Keyboard">Keyboard</option>
									<option value="Mouse">Mouse</option>
									<option value="Headset">Headset</option>
									<option value="Monitor">Monitor</option>
									<option value="" id="otherItemType">Other</option>
								</select> <input type="text" class="form-control hidden"
									id="otherItemType" placeholder="Please Specify"> <span
									class="help-block text-danger itemType">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group dateArrived">
								<label class="control-label "> Date Arrived:</label>

								<div class="input-group date dateArrived">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateArrived"
										name="dateArrived" id="dateArrived">

								</div>

								<span class="help-block text-danger dateArrived">192.168.100.200</span>

							</div>
						</div>



						<div class="col-lg-12">
							<br>
							<div id="itemPhoto" class="dropzone">
								<div class="dropzone-previews"></div>
								<div class="dz-message" data-dz-message>
									<span>Click to upload or Drop Item's photo here.</span><br> <span>Image
										files only. </span><br> <span>2 MB per image limit. 10 MB
										total limit.</span>
								</div>
							</div>
						</div>




					</div>
				</form>

			</div>

			<div class="modal-footer">
				<button class="btn btn-w-m btn-primary addItem" type="button">
					<strong>Submit</strong>
				</button>
				<button class="btn btn-danger btn-w-m" data-dismiss="modal">Cancel</button>

			</div>
		</div>

	</div>
</div>
@endsection @section('scripts')
<script type="text/javascript" src="/js/inventory/inventoryAddItem.js">
</script>
@endsection

