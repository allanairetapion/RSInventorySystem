@extends('layouts.ticket_basic') @section('body')

<div class="row"></div>
<div class="ibox float-e-margins">


	<div class="ibox-content">
		<ul class="nav nav-tabs">
			<li class="ticketReport active"><a data-toggle="tab" href="#home">Home</a>
			</li>
			<li class="ticketReport"><a data-toggle="tab" href="#menu1">Open</a>
			</li>
			<li class="ticketReport"><a data-toggle="tab" href="#menu2">Pending</a>
			</li>
			<li class="ticketReport"><a data-toggle="tab" href="#menu3">Closed</a>
			</li>
			<li class="ticketReport"><a data-toggle="tab" href="#menu4">Unresolved</a>
			</li>

		</ul>
		<br>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<div class="row">
					<div class="col-md-offset-8 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="ticketSearch"
								name="search" placeholder="Search...">
							<div class="input-group-btn">
								<button id="ticketSearch" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" data-toggle="collapse"
									data-target="#advancedSearch" type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>
				</div>

				<div id="advancedSearch" class="panel panel-default collapse">
					<div class="panel-body">
						<form class="advancedTicket form-horizontal">
							{!! csrf_field() !!}

							<div class="row">

								<div class="form-group col-md-4">
									<label class="control-label col-md-4"> Topic:</label>
									<div class="col-md-8">
										<select name="topicSearch" class="topic form-control ">
											<option value="" selected></option> 
											@foreach ($topics as $topic)
											<option value="{{$topic->description}}">
												{{$topic->description}}</option> 
											@endforeach
										</select>
									</div>
								</div>

								<div class=" form-group col-md-4">
									<label class="control-label col-md-4">Status:</label>
									<div class="col-md-8">
										<select name="statusSearch" class="statusSearch form-control">
											<option value="" selected></option>
											<option value="Open">Open</option>
											<option value="Pending">Pending</option>
											<option value="Closed">Closed</option>
											<option value="Unresolved">Unresolved</option>
										</select>
									</div>
								</div>
								<div class="form-group col-md-4">
									<label class="control-label col-md-4">Assigned to:</label>
									<div class="col-md-8">
										<select name="agentSent" class="agentSent form-control">
											<option value="" selected></option> 
											@foreach ($agent as $agents)
											<option value="{{$agents->id}}">
											{{$agents->first_name.' '.$agents->last_name}}</option> 
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group col-md-4">
									<label class="control-label col-md-4">Closed by:</label>
									<div class="col-md-8">
										<select name="agentClosed" class="agentClosed form-control">
											<option value="" selected></option> 
											@foreach ($agent as $agents)
											<option value="{{$agents->id}}">
											{{$agents->first_name.' '.$agents->last_name}}</option> 
											@endforeach
										</select>
									</div>
								</div>

								<div class="form-group col-md-6">

									<label class="control-label col-md-2">Date:</label>
									<div class="col-md-4">
										<select name="dateSort" class="topic form-control">
											<option value="1">Date Sent</option>
											<option value="2">Date Updated</option>
										</select>
									</div>


									<div class="input-daterange input-group col-md-6"
										id="datepicker">
										<span class="input-group-addon">From</span> <input type="text"
											class=" form-control" data-mask="9999-99-99" name="dateStart"
											value="" /> <span class="input-group-addon">to</span> <input
											type="text" class=" form-control" data-mask="9999-99-99"
											name="dateEnd" value="" />
									</div>

								</div>



								<div class="col-md-2 text-right">

									<button type="button"
										class="btn btn-primary advancedTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="button"
										class="btn btn-warning advancedTicketReset">
										<i class="fa fa-refresh"></i> Reset
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>


				<div class="table-responsive">
					<form class="selectedTickets">
						{!! csrf_field() !!}
						<table class="table table-bordered ticketReport">
							<thead>
								<tr>

									<th>Ticket No.</th>
									<th>Sender</th>
									<th>Topic</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Department</th>
									<th>Assigned Support</th>
									<th>Closed By</th>
									<th>Date Sent</th>
									<th>Date Updated</th>
								</tr>
							</thead>

							<tbody class="ticketReport">

								@foreach($tickets as $ticketStat)
								<tr>
									<td>{{$ticketStat->id}}</td>
									<td>{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
									<td>{{$ticketStat->description}}</td>
									<td>{{$ticketStat->subject}}</td>
									<td>{{$ticketStat->ticket_status}}</td>
									<td>{{$ticketStat->department}}</td>
									<td>{{$ticketStat->assign_FN.' '.$ticketStat->assign_LN}}</td>
									<td>{{$ticketStat->close_FN.' '.$ticketStat->close_LN}}</td>
									<td>{{$ticketStat->created_at}}</td>
									<td>{{$ticketStat->updated_at}}</td>
								</tr>

								@endforeach

							</tbody>
						</table>
					</form>


				</div>


			</div>

			<div id="menu1" class="tab-pane fade in">

				<br>

				<div class="table-responsive">
					<form class="selectedTickets">
						{!! csrf_field() !!}
						<table class="table table-bordered ticketReport2"
							style="font-size: small;">
							<thead>
								<tr>
									<th>Ticket No.</th>
									<th>Sender</th>
									<th>Topic</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Department</th>
									<th>Assigned Support</th>
									<th>Closed By</th>
									<th>Date Sent</th>
									<th>Date Updated</th>
								</tr>
							</thead>
							<tbody>

								@foreach($tickets as $ticketStat) 
								@if($ticketStat->ticket_status == "Open")

								<tr id="{{$ticketStat->id}}">
									<td>{{$ticketStat->id}}</td>
									<td class="bg-primary">{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
									<td class="bg-primary">{{$ticketStat->description}}</td>
									<td class="bg-primary">{{$ticketStat->subject}}</td>
									<td class="bg-primary">{{$ticketStat->ticket_status}}</td>
									<td class="bg-primary">{{$ticketStat->department}}</td>
									<td class="bg-primary">{{$ticketStat->assign_FN.' '.$ticketStat->assign_LN}}</td>
									<td class="bg-primary">{{$ticketStat->close_FN.' '.$ticketStat->close_LN}}</td>
									<td class="bg-primary">{{$ticketStat->created_at}}</td>
									<td class="bg-primary">{{$ticketStat->updated_at}}</td>
								</tr>
								@endif 
								@endforeach

							</tbody>
						</table>
					</form>

				</div>

			</div>

			<div id="menu2" class="tab-pane fade in">
				<br>

				<div class="table-responsive">
					<form class="selectedTickets">
						{!! csrf_field() !!}
						<table class="table table-bordered ticketReport2"
							style="font-size: small;">
							<thead>
								<tr>
									<th>Ticket No.</th>
									<th>Sender</th>
									<th>Topic</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Department</th>
									<th>Assigned Support</th>
									<th>Closed By</th>
									<th>Date Sent</th>
									<th>Date Updated</th>
								</tr>
							</thead>
							<tbody>

								@foreach($tickets as $ticketStat) 
								@if($ticketStat->ticket_status == "Pending")
								<tr id="{{$ticketStat->id}}">
									<td>{{$ticketStat->id}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->description}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->subject}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->ticket_status}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->department}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->assign_FN.' '.$ticketStat->assign_LN}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->close_FN.' '.$ticketStat->close_LN}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->created_at}}</td>
									<td style="background-color: #F2F256;">{{$ticketStat->updated_at}}</td>
								</tr>
								@endif 
								@endforeach

							</tbody>
						</table>
					</form>


				</div>


			</div>

			<div id="menu3" class="tab-pane fade in">
				<br>

				<div class="table-responsive">
					<form class="selectedTickets">
						{!! csrf_field() !!}
						<table class="table table-bordered ticketReport2"
							style="font-size: small;">
							<thead>
								<tr>
									<th>Ticket No.</th>
									<th>Sender</th>
									<th>Topic</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Department</th>
									<th>Assigned Support</th>
									<th>Closed By</th>
									<th>Date Sent</th>
									<th>Date Updated</th>
								</tr>
							</thead>
							<tbody>

								@foreach($tickets as $ticketStat) 
								@if($ticketStat->ticket_status == "Closed")

								<tr id="{{$ticketStat->id}}">
									<td>{{$ticketStat->id}}</td>
									<td class="navy-bg">{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
									<td class="navy-bg">{{$ticketStat->description}}</td>
									<td class="navy-bg">{{$ticketStat->subject}}</td>
									<td class="navy-bg">{{$ticketStat->ticket_status}}</td>
									<td class="navy-bg">{{$ticketStat->department}}</td>
									<td class="navy-bg">{{$ticketStat->assign_FN.' '.$ticketStat->assign_LN}}</td>
									<td class="navy-bg">{{$ticketStat->close_FN.' '.$ticketStat->close_LN}}</td>
									<td class="navy-bg">{{$ticketStat->created_at}}</td>
									<td class="navy-bg">{{$ticketStat->updated_at}}</td>
								</tr>
								@endif 
								@endforeach

							</tbody>
						</table>
					</form>

				</div>


			</div>

			<div id="menu4" class="tab-pane fade in">
				<br>

				<div class="table-responsive">
					<form class="selectedTickets">
						{!! csrf_field() !!}
						<table class="table table-bordered ticketReport2"
							style="font-size: small;">
							<thead>
								<tr>									
									<th>Ticket No.</th>
									<th>Sender</th>

									<th>Topic</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Department</th>
									<th>Assigned Support</th>
									<th>Closed By</th>
									<th>Date Sent</th>
									<th>Date Updated</th>
								</tr>
							</thead>
							<tbody>

								@foreach($tickets as $ticketStat) 
								@if($ticketStat->ticket_status == "Unresolved")

								<tr id="{{$ticketStat->id}}">
									
									<td>{{$ticketStat->id}}</td>
									<td class="red-bg">{{$ticketStat->first_name.' '.$ticketStat->last_name}}</td>
									<td class="red-bg">{{$ticketStat->description}}</td>
									<td class="red-bg">{{$ticketStat->subject}}</td>
									<td class="red-bg">{{$ticketStat->ticket_status}}</td>
									<td class="red-bg">{{$ticketStat->department}}</td>
									<td class="red-bg">{{$ticketStat->assign_FN.' '.$ticketStat->assign_LN}}</td>
									<td class="red-bg">{{$ticketStat->close_FN.' '.$ticketStat->close_LN}}</td>
									<td class="red-bg">{{$ticketStat->created_at}}</td>
									<td class="red-bg">{{$ticketStat->updated_at}}</td>
								</tr>
								@endif 
								@endforeach

							</tbody>
						</table>
					</form>


				</div>


			</div>

		</div>

	</div>
</div>
@endsection @section('scripts')
<script type="text/javascript" src="/js/ticketing/ticketReport.js">
</script>
@endsection
