@extends('layouts.ticket_basic') @section('body')

<div class="row">

	<div class="col-md-3 animated fadeInDown">
		<a href="/admin/tickets-Open">
			<div class="widget style1 navy-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-ticket fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Open Tickets </span>
						<h2 class="font-bold newTickets"></h2>

						<small>Today</small>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<a href="/admin/tickets-Pending">
			<div class="widget style1 yellow-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="glyphicon glyphicon-time  fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Pending Tickets </span>
						<h2 class="font-bold pendingTickets"></h2>
						<small>Today</small>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<a href="/admin/tickets-Unresolved">
			<div class="widget style1 red-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-warning fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Unresolved Tickets </span>
						<h2 class="font-bold overdueTickets"></h2>
						<small>Today</small>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<a href="/admin/tickets-Closed">
			<div class="widget style1 lazur-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-thumbs-o-up fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Closed Tickets </span>
						<h2 class="font-bold closedTickets"></h2>
						<small>Today</small>
					</div>
				</div>
			</div>
	
	</div>
	</a>


	<div class="col-md-6">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Ticket Summary</h5>
			</div>
			<div class="ibox-content">
				<div id="stocked"></div>

			</div>
		</div>

	</div>

	<div class="col-md-3">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Ticket Share Per Status</h5>
			</div>
			<div class="ibox-content">

				<div id="pie"></div>

			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Ticket Share Per Topic</h5>

			</div>
			<div class="ibox-content">

				<div id="pie2"></div>

			</div>
		</div>
	</div>

	@if(Auth::guard('admin')->user()->user_type == 'admin')
	<div class="col-lg-12">
		<div class="ibox animated fadeInDown ">
			<div class="ibox-title">
				<div class="pull-right"></div>
				<h3 class="font-bold">Assign a Support</h3>

			</div>
			<div class="ibox-content">
				@if(count($noSupport) != 0)
				<div class="table-responsive">
					<form class="noSupport">
						{!! csrf_field() !!}
						<table class="table table-bordered table-condensed noSupport">
							<thead>
								<tr>

									<th>Ticket Id</th>
									<th>Status</th>
									<th>Topic</th>
									<th>Subject</th>
									<th>Date</th>
									<th>Assign Support</th>

								</tr>
							</thead>
							<tbody>
								@foreach($noSupport as $noSup) @if($noSup->assigned_support == 0
								&& $noSup->ticket_status != "Closed")
								<tr>

									<td class="control-label"><a href="/admin/tickets/{{$noSup->id}}">{{$noSup->id}}</a></td>
									<td class="text-center control-label">@if($noSup->ticket_status == "Open") <span
										class="label label-success">{{$noSup->ticket_status}}</span>
										@elseif($noSup->ticket_status == "Pending") <span
										class="label label-warning">{{$noSup->ticket_status}}</span>
										@elseif($noSup->ticket_status == "Closed") <span
										class="label label-primary">{{$noSup->ticket_status}}</span>
										@elseif($noSup->ticket_status == "Unresolved") <span
										class="label label-danger">{{$noSup->ticket_status}}</span>
										@endif

									</td>
									<td class="control-label">{{$noSup->description}}</td>
									<td class="control-label">{{$noSup->subject}}</td>
									<td class="control-label">{{$noSup->created_at}}</td>
									<td ><select name="{{$noSup->id}}"
										class="form-control noSupport">
											<option value="" selected>Assign a support...</option>
											@foreach ($agent as $agents)
											<option value="{{$agents->id}}">{{$agents->first_name.'
												'.$agents->last_name}}</option> @endforeach
									</select></td>

								</tr>
								@endif @endforeach
							</tbody>
						</table>
					</form>
				</div>
				@else
				<div class="jumbotron">
					<h1>No Tickets found</h1>
				</div>
				@endif
			</div>
		</div>
	</div>

	@endif
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tickets</h4>
			</div>

			<div class="ibox-content">
				<div class="spiner">
					<div class="sk-spinner sk-spinner-three-bounce">
						<div class="sk-bounce1"></div>
						<div class="sk-bounce2"></div>
						<div class="sk-bounce3"></div>
					</div>
				</div>
				<table class="table table-bordered ticketStatusInfo"
					data-striping="false">
					<thead>
						<tr>
							<th>Id</th>
							<th>Sender</th>
							<th>Topic</th>
							<th>Status</th>
							<th class="status"></th>
							<th>Date Sent</th>
							<th>Date Updated</th>
						</tr>
					</thead>
					<tbody class="ticketStatusInfo">

					</tbody>
				</table>

			</div>


		</div>

	</div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tickets</h4>
			</div>

			<div class="ibox-content">
				<div class="spiner">
					<div class="sk-spinner sk-spinner-three-bounce">
						<div class="sk-bounce1"></div>
						<div class="sk-bounce2"></div>
						<div class="sk-bounce3"></div>
					</div>
				</div>
				<table class="table table-bordered topIssueInfo"
					data-striping="false">
					<thead>
						<tr>
							<th>Id</th>
							<th>Sender</th>
							<th>Topic</th>
							<th>Status</th>
							<th>Assigned To</th>
							<th>Closed By</th>
							<th>Date Sent</th>
							<th>Date Updated</th>
						</tr>
					</thead>
					<tbody class="topIssueInfo">

					</tbody>
				</table>

			</div>


		</div>

	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="/js/ticketing/ticketDashboard.js">
</script>
@endsection

