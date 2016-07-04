@extends('tickets.ticketadminlayout')
@section('body')

<div class="row">

	<div class="ibox float-e-margins">
		<div class="ibox-title">

			<h2 class="font-bold">Tickets</h2>
		</div>

		<div class="ibox-content">
			<ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#home">Home</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu1">Open</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu2">Pending</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu3">Closed</a>
				</li>
			</ul>

			<div class="row">
				<br>
				<div class="col-sm-3">

					<input type="text" placeholder="Search" class="input-sm form-control ticketSearch">

				</div>

				<div class="col-md-offset-6 col-md-3 text-right">
					@if(Auth::guard('admin')->user()->user_type == 'admin')
					<button class="btn btn-warning btn-sm  ticketDelete">
						<i class="fa fa-trash"></i> Delete
					</button>
					@endif
					<button class="btn btn-primary btn-sm advancedSearch">
						Advanced Search
					</button>
				</div>
			</div>
			<div id="advancedSearch" class=" gray-bg" style="padding:5px;">
				<br>
				<form class="advancedTicket" >
					{!! csrf_field() !!}

					<div class="row">
						<div class="col-md-3">
							<label class="control-label">Date Sent:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateSent" class="form-control dateSent" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Date Closed:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateClosed" class="form-control dateClosed" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Topic:</label>
							<select name="topicSearch"class="form-control topic">
								<option value="" selected hidden> Choose a topic... </option>
								@foreach ($topics as $topic)
								<option value="{{$topic->description}}"> {{$topic->description}}</option>
								@endforeach
							</select>

						</div>
						<div class="col-md-3">
							<label class="control-label">Status:</label>
							<select name="statusSearch"class="form-control statusSearch">
								<option value="" selected hidden>Search by status...</option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
							</select>
						</div>
					</div>
					<div class="row">
						<br>
						<div class="col-md-3">
							<label class="control-label">Email:</label>
							<input type="email" name="email" class="form-control email"/>
						</div>
						<div class="col-md-3">
							<label class="control-label">Assigned to:</label>
							<select name="agentSent" class="form-control agentSent">
								<option value="" selected hidden>Select agent...</option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Closed by:</label>
							<select name="agentClosed" class="form-control agentClosed">
								<option value="" selected hidden>Select agent...</option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-3 text-center">
							<br>

							<button type="button" class="btn btn-primary  advancedTicketSearch">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="reset" class="btn btn-warning  advancedTicketReset">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
					</div>

				</form>
				<br>
			</div>

			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">

					<br>

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Sender Id </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody class="ticketReport">

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Pending")
									<tr style="background-color: #F2F256;" id="{{$ticketStat->id}}">
										@elseif ($ticketStat->ticket_status == "Open")
									<tr class="bg-primary" id="{{$ticketStat->id}}">
										@else
									<tr class="navy-bg" id="{{$ticketStat->id}}">
										@endif
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->sender_id}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
										@foreach($closed_by as $closed)
										@if($closed->id == $ticketStat->id)
										<td>{{$closed->first_name.' '.$closed->last_name}}</td>
										@endif
										@endforeach
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->closed_at}}</td>
									</tr>

									@endforeach

								</tbody>
							</table>
						</form>
					</div>
					<div class="row">
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
				</div>
				<div id="menu1" class="tab-pane fade">
					<br>
					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered  ticketOpenReport"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox" class=" ticketOpenCB" >
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Sender Id</th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody class="ticketOpenReport">

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Open")

									<tr class="bg-primary" id="{{$ticketStat->id}}">

										<td>
										<input type="checkbox" id="openTicket"name="{{$ticketStat->id}}"  value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->sender_id}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>

										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->closed_at}}</td>
									</tr>
									@endif
									@endforeach

								</tbody>
							</table>
						</form>

					</div>
					<div class="row">
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
				</div>
				<div id="menu2" class="tab-pane fade">
					<br>
					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketPendingReport"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox" id="pendingTicket" class=" ticketPendingCB" >
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Sender Id </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody class="ticketPendingReport">

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Pending")

									<tr style="background-color: #F2F256;" id="{{$ticketStat->id}}">

										<td>
										<input type="checkbox" id="pendingTicket" name="{{$ticketStat->id}}"  value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->sender_id}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>

										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->closed_at}}</td>
									</tr>
									@endif
									@endforeach

								</tbody>
							</table>
						</form>
					</div>
					<div class="row">
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
				</div>
				<div id="menu3" class="tab-pane fade">
					<br>
					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketClosedReport" style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketClosedCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Sender Id </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody class="ticketClosedReport">

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Closed")

									<tr class="navy-bg" id="{{$ticketStat->id}}">

										<td>
										<input type="checkbox" id="closedTicket" name="{{$ticketStat->id}}" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->sender_id}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>

										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->closed_at}}</td>
									</tr>
									@endif
									@endforeach

								</tbody>
							</table>
						</form>

					</div>
					<div class="row">
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>

@endsection
