@extends('inventory.inventory') 
@section('title', 'RS | Inventory Details') 

@section('header-page')
<div class="col-lg-10">
	<h2>Inventory Details</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Inventory Details</strong></li>
	</ol>
</div>
@endsection 

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">

			<div class="ibox-content">
				<div class="row">
				<form class="form-horizontal">
					<div class="form-group  col-md-4">
						<label class="control-label col-md-3">Search</label>
						<div class="col-md-9">
							<input class="form-control input-sm" id="filter" type="text">
						</div>
					</div>
				</form>
				</div>
				<br>
				<div class="table-responsive">
					<table
						class="footable table table-bordered table-hover detailedInventory toggle-arrow-tiny"
						data-filter="#filter">
						<thead>
							<tr>
								<th data-toggle="true">Unique Id</th>
								<th>Item No</th>
								<th>Station No</th>
								<th>Item Type</th>
								<th>Brand</th>
								<th>Model</th>
								<th>Status</th>
								
								
								
								<th data-hide="all">Morning Shift</th>
								<th data-hide="all">Night Shift</th>
								<th data-hide="all">Last Issue</th>
								<th data-hide="all">Last Broken</th>
								<th>Date Arrived</th>
								<th data-hide="all">Last Borrowed</th>
								<th data-hide="all">Last Returned</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($items as $item)
							<tr>
								<td>{{$item->unique_id}}</td>
								<td>{{$item->itemNo}}</td>
								<td>{{$item->stationNo}}</td>
								
								<td>{{$item->itemType}}</td>
								<td>{{$item->brand}}</td>
								<td>{{$item->model}}</td>
								<td>{{$item->itemStatus}}</td>
								
								<td>{{$item->morningClient}}</td>
								<td>{{$item->nightClient}}</td>
								<td>{{$item->lastIssue}}</td>
								<td>{{$item->lastBroken}}</td>
								<td>{{$item->dateArrived}}</td>
								<td>{{$item->dateBorrowed}}</td>
								<td>{{$item->dateReturned}}</td>
								<td>
									<button>Update</button>
									<button>Delete</button>
								</td>
							</tr>
							@endforeach


						</tbody>

					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$('table.detailedInventory').footable();
});
</script>
@endsection
