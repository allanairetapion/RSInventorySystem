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
				<div class="input-group m-b">
					<input type="text" class="form-control" id="filter" placeholder="Search...">
					<div class="input-group-btn">
						<button class="btn btn-white" id="borrowAdvancedSearch" type="button">
							Search Options <span class="caret"></span>
						</button>
					</div>
				</div>
				<div class="table-responsive">
					<table id="detailed" class="footable table table-bordered table-hover toggle-arrow-tiny"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>
								<th data-toggle="true">Item No</th>
								<th>Unique Id</th>
								<th>Station No</th>
								<th>Item Type</th>
								<th>Brand</th>
								<th>Model</th>
								<th>Status</th>
								
								
								
								<th data-hide="all">Morning Shift</th>
								<th data-hide="all">Night Shift</th>
								<th data-hide="all">Last Recorded Issue</th>
								<th data-hide="all">Last Recorded Broken</th>
								<th>Date Arrived</th>
								<th data-hide="all">Last Borrowed</th>
								<th data-hide="all">Last Returned</th>
								
							</tr>
						</thead>
						<tbody>
							@foreach($items as $item)
							<tr>
								<td><a href="/inventory/items/{{$item->itemNo}}">{{$item->itemNo}}</a></td>
								<td>{{$item->unique_id}}</td>
								<td>{{$item->stationNo}}</td>
								
								<td>{{$item->itemType}}</td>
								<td>{{$item->brand}}</td>
								<td>{{$item->model}}</td>
								<td>{{$item->itemStatus}}</td>
								
								<td>{{$item->morningClient}}</td>
								<td>{{$item->nightClient}}</td>
								<td>{{$item->lastIssue}}</td>
								<td>{{$item->lastBroken}}</td>
								<td>{{$item->created_at}}</td>
								<td>{{$item->dateBorrowed}}</td>
								<td>{{$item->dateReturned}}</td>
								
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
	$('table#detailed').footable();
});
</script>
@endsection
