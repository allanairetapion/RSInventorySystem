<table id="detailed" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Item No</th>
			<th>Unique Id</th>
			<th>Station No</th>
			<th>Brand</th>
			<th>Model</th>
			<th>Status</th>
			<th>Morning Shift</th>
			<th>Night Shift</th>
			<th>Date Arrived</th>
		</tr>
	</thead>
	<tbody>
		@foreach($items as $item)
		<tr>
			<td><a href="#" id="itemInfo">{{$item->itemNo}}</a></td>
			<td>{{$item->unique_id}}</td>
			<td>{{$item->stationNo}}</td>
			<td>{{$item->brand}}</td>
			<td>{{$item->model}}</td>
			<td>{{$item->itemStatus}}</td>
			<td>{{$item->morningClient}}</td>
			<td>{{$item->nightClient}}</td>
			<td>{{$item->created_at}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
<div class="pull-right">{{ $items->links() }}</div>