@extends('layouts.ticket_basic') @section('body')
<div class="row animated fadeInRight">
	<div class="col-md-3">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h4>
					<strong>{{$profile->first_name.' '.$profile->last_name}}</strong>
				</h4>
			</div>
			<div>
				<div class="ibox-content no-padding border-left-right">
					<img alt="image" class="img-responsive"
						src="/img/agents/{{$profile->id}}.jpg"
						onerror="this.src = '/img/default-profile.jpg'">
				</div>
				<div class="ibox-content profile-content">
					<p>
						<i class="fa fa-user"></i> : <span id="agentId">{{$profile->id}}</span>
					</p>
					<p>
						<i class="fa fa-envelope-o"></i> : {{$profile->email}}
					</p>

					<br>
					<div>
						<table class="table">
							<tbody>
								<tr>
									<td><label class="label label-success m-r-sm">{{$ticketCounts[0]->ticketCount}}</label>
										{{$ticketCounts[0]->ticket_status}}</td>
								</tr>
								<tr>
									<td><label class="label label-primary m-r-sm">{{$ticketCounts[1]->ticketCount}}</label>
										{{$ticketCounts[1]->ticket_status}}</td>
								</tr>
								<tr>
									<td><label class="label label-danger m-r-sm">{{$ticketCounts[2]->ticketCount}}</label>
										{{$ticketCounts[2]->ticket_status}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Activities</h5>

			</div>

			<div class="ibox-content ">
				<div>
					<div id="slineChart"></div>
				</div>
			</div>
		</div>
		<div class="ibox">
			<div class="ibox-content">				
					<table class="table table-bordered table-hover" id="agentTickets">
						<thead>
							<tr>
								<th>Ticket Id</th>
								<th>Sender</th>
								<th>Topic</th>
								<th>Status</th>
								<th>Date Sent</th>
								<th>Date Updated</th>
							</tr>
						</thead>
						<tbody>
							@foreach($tickets as $ticket)
							<tr>
								<td>{{$ticket->id}}</td>
								<td>{{$ticket->first_name.' '.$ticket->last_name}}</td>
								<td>{{$ticket->description}}</td>
								<td>{{$ticket->ticket_status}}</td>
								<td>{{$ticket->created_at}}</td>
								<td>{{$ticket->updated_at}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>				
			</div>
		</div>
	</div>

	@endsection @section('scripts')
	<script type="text/javascript"
		src="/js/ticketing/ticketAgentProfile.js">
</script>
	@endsection