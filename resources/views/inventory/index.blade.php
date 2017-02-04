@extends('layouts.inventory_basic') @section('title', 'RS | Dashboard')

@section('sidebarDashboard')
<li class="active"><a href="/inventory/index"><i class="fa fa-th-large"></i>
		<span class="nav-label">Dashboard</span></a></li>
@endsection @section('header-page')



<div class="col-lg-3">
	<br>
	<h3>Summary</h3>
	<ul class="list-group clear-list m-t">
		@foreach($itemCount as $item) @if($item['itemStatus'] == "Borrowed")
		<li class="list-group-item fist-item"><span class="pull-right">Borrowed
				Items</span> <span class="label label-success">{{$item['counter']}}</span></li>
		@elseif ($item['itemStatus'] == "In-stock")
		<li class="list-group-item"><span class="pull-right">Returned Items</span>
			<span class="label label-info">{{$item['counter']}}</span></li>
		@elseif ($item['itemStatus'] == "With Issue")
		<li class="list-group-item"><span class="pull-right">Items with Issues</span>
			<span class="label label-warning">{{$item['counter']}}</span></li>
		@elseif ($item['itemStatus'] == "Broken")
		<li class="list-group-item"><span class="pull-right">Broken Items</span>
			<span class="label label-danger">{{$item['counter']}}</span></li>
		@endif @endforeach



	</ul>

</div>
<div class="col-lg-6">
	<div class="flot-chart dashboard-chart">
		<div class="flot-chart-content" id="flot-dashboard-chart"></div>
	</div>
</div>
<div class="statistic-box">
	<div class="col-lg-3">
		<h3>Maintenance Schedule</h3>
		<div class="widget style1">
			<div class="row">
				<div class="col-xs-4 text-center">
					<i class="fa fa-calendar fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">

					<h2 class="font-bold">{{$mScheds}}</h2>
					<span> Scheduled Today </span>
				</div>
			</div>
		</div>
		<button class="btn btn-block btn-white" id="maintenance">List</button>

	</div>

</div>


@endsection @section('content')

<div class="row">

	<div class="ibox">
		<div class="ibox-content">
			<div class="tabs-container">
				<a href="/inventory/detailed"
					class="btn btn-primary btn-sm pull-right">View All</a>
				<ul class="nav nav-tabs">
					<li class="active"><a id="itemType" data-toggle="tab" href="#all">All</a></li>
					<li class=""><a id="itemType" data-toggle="tab" href="#laptop">Laptop</a></li>
					<li class=""><a id="itemType" data-toggle="tab" href="#mouse">Mouse</a></li>
					<li class=""><a id="itemType" data-toggle="tab" href="#headset">Headset</a></li>
					<li class=""><a id="itemType" data-toggle="tab" href="#projector">Projector</a></li>

				</ul>
				<div class="tab-content">
					<div id="all" class="tab-pane active">
						<div class="panel-body">
							<div class="table-responsive" id="stockAll">
								<table id="detailed" class="footable table table-hover"
									data-striping="false">
									<thead>
										<tr>
											<th>Item No</th>
											<th>Serial No.</th>
											<th>Station No</th>
											<th>Brand</th>
											<th>Model</th>
											<th>Item Type</th>
											<th>Status</th>
											<th>Morning User</th>
											<th>Night User</th>
											<th>Date Reported</th>
										</tr>
									</thead>
									<tbody>
										@foreach($items as $item)
										<tr>
											<td><a href="/inventory/items/{{$item->itemNo}}">{{$item->itemNo}}</a></td>
											<td>{{$item->unique_id}}</td>
											<td>{{$item->stationNo}}</td>
											<td>{{$item->brand}}</td>
											<td>{{$item->model}}</td>
											<td>{{$item->itemType}}</td>
											<td>{{$item->itemStatus}}</td>
											<td>{{$item->morning_FN.' '.$item->morning_FN}}</td>
											<td>{{$item->night_FN.' '.$item->night_LN}}</td>
											<td>{{$item->updated_at->diffForHumans()}}</td>
										</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<td colspan="10">
												<ul class="pagination pull-right"></ul>
											</td>
										</tr>
									</tfoot>
								</table>

							</div>
						</div>
					</div>



				</div>


			</div>
		</div>
	</div>



</div>


<div id="mSchedule" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Maintenance Schedule</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<button class="btn btn-sm btn-primary pull-right" id="addSchedule">Add Schedule</button>
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" class="hide" href="#tab-1"> Today</a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-2"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-3"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-4"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-5"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-6"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-7"></a></li>
								<li class=""><a data-toggle="tab" class="hide" href="#tab-8"></a></li>
							</ul>
							<div class="tab-content">

								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container"></div>
									</div>
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container"></div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container"></div>
									</div>
								</div>
								<div id="tab-4" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg"></div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-5" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg"></div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-6" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg"></div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-7" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg"></div>
											</div>



										</div>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>


			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-w-m btn-danger btn-outline"
					data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
<div id="addSchedule" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Schedule</h4>
				</div>

				<div class="modal-body">
					<form class="form-horizontal" id="addSchedule">
						{!! csrf_field() !!}
						<div class="form-group">
							<div class="title">
								<label class="col-lg-2 control-label">Title :</label>

								<div class="col-lg-10">
									<input type="text" name="title" value="Scheduled Maintenance"
										class="form-control"> <span
										class="help-block text-danger title">Example block-level help
										text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="area">
								<label class="col-lg-2 control-label">Area :</label>

								<div class="col-lg-10">
									<select name="area" class="col-lg-5 form-control">
										<option value="" selected></option> @foreach($areas as $area)
										<option value="{{$area->id}}">{{$area->area}}</option>
										@endforeach


									</select> <span class="help-block text-danger area">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="startScheduleDate">
								<label class="col-lg-2 control-label">From:</label>

								<div class="col-lg-5">
									<input type="text" name="startScheduleDate"
										placeholder="Start Date" id="startScheduleDate"
										class="form-control datePicker"> <span
										class="help-block text-danger startScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="startScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="startScheduleTime"
										id="startScheduleTime" class="clockPicker form-control"
										placeholder="Start Time"> <span
										class="help-block text-danger startScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="endScheduleDate">
								<label class="col-lg-2 control-label">To:</label>

								<div class="col-lg-5">
									<input type="text" name="endScheduleDate" id="endScheduleDate"
										placeholder="End Date" class="form-control datePicker"> <span
										class="help-block text-danger endScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="endScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="endScheduleTime" id="endScheduleTime"
										class="clockPicker form-control" placeholder="End Time"> <span
										class="help-block text-danger endScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Agents:</label>
							<div class="col-lg-10">
								
									<select data-placeholder="Choose an agent" id="agentSelect"
										name="agents[]" class="chosen-select form-control" multiple tabindex="4">
										<option value="">Select</option> @foreach($agents as $agent)
										<option value="{{$agent->id}}">{{$agent->first_name.'
											'.$agent->last_name}}</option> @endforeach
									</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Status:</label>
							<div class="col-lg-10">
								<select name="status" class="form-control">
									<option value="" selected></option>
									<option value="urgent">Urgent</option>
									<option value="normal">Normal</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Activities:</label>
							<div class="activity">
								@foreach($activities as $activity)
								<div class="col-lg-3">
									<input type="checkbox" class="i-checks activity"
										name="activity[]" value="{{$activity->activity}}"> <label
										id="{{$activity->id}}">{{$activity->activity}}</label>
								</div>
								@endforeach
							</div>
							<div class="col-lg-3">
								<a href="#" data-toggle="modal" data-target="#newActivity"><i
									class="fa fa-plus"></i> Add new Activity...</a>
							</div>
						</div>



					</form>


				</div>

				<div class="modal-footer">
					<button type="button"
						class="ladda-button btn btn-w-m btn-primary saveSchedule"
						data-style="zoom-in">Save</button>
					<button type="button" class="btn btn-w-m btn-danger btn-outline"
						data-dismiss="modal">Cancel</button>
				</div>
			</div>

		</div>
	</div>

@endsection

@section('scripts')
<!-- Flot -->
<script src="/js/plugins/flot/jquery.flot.js"></script>
<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/js/plugins/flot/jquery.flot.pie.js"></script>

<script type="text/javascript"
		src="/js/inventory/inventoryIndex.js"></script>
@endsection

