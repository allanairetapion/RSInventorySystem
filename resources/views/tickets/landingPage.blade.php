@extends('tickets.ticketclientlayout')
@section('body')
<div class="row">
<div class="col-md-6">
	<div class="col-md-6 animated bounceInLeft">
		<div class="widget style2 yellow-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="glyphicon glyphicon-time  fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Pending Tickets </span>
					<h2 class="font-bold">{{count($pendingTickets)}}</h2>
					<small>Total</small>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6 animated bounceInLeft">
		<a href="/tickets/createTicket">
		<div class="widget style2 navy-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-ticket fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span>To submit a ticket</span>
					<h2 class="font-bold">Click here</h2>
				</div>
			</div>
		</div> </a>
	</div>
	<div class="col-md-12 animated bounceInLeft">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Common Issue</h5>
			</div>
			<div class="ibox-content">
				<div>
					<div id="pie"></div>
				</div>
			</div>
		</div>
	</div>



</div>
<div class="col-md-6 animated bounceInLeft">
	<div class="ibox ">
		<div class="ibox-title">
			<div class="pull-right">
				<a href="/tickets/ticketStatus" class="btn btn-primary btn-sm"> View All </a>
				</div>
			<h3 class="font-bold">Your recent tickets</h3>
		</div>
		<div class="ibox-content">
			<table class="table table-hover issue-tracker">
				<thead>
					<tr>
					<th>Status</th>
						<th>Ticket No:</th>
						<th>Topic</th>
						
						
						<th>Date Sent</th>
						<th>Assigned to:</th>

					</tr>
				</thead>
				<tbody>
					
						@foreach ($tickets as $ticket)
						
						<tr class="read" data-href="/tickets/{{$ticket->id}}" >
							<td class="text-center">@if($ticket->ticket_status == "Open") <span
								class="label label-success">{{$ticket->ticket_status}}</span>
								@elseif($ticket->ticket_status == "Pending") <span
								class="label label-warning">{{$ticket->ticket_status}}</span>
								@elseif($ticket->ticket_status == "Closed") <span
								class="label label-primary">{{$ticket->ticket_status}}</span>
								@elseif($ticket->ticket_status == "Unresolved") <span
								class="label label-danger">{{$ticket->ticket_status}}</span>
								@endif
							</td>


						<td>{{$ticket->id}}</td>
						<td>{{$ticket->description}}</td>
						
						<td >{{$ticket->created_at}}</td>
						<td>{{$ticket->first_name.' '.$ticket->last_name}}</td>
					</tr>
					@endforeach				
										
				</tbody>
			</table>
			
		</div>

	</div>
</div>
</div>
<script src="/js/jquery-2.1.1.js"></script>
<script>
	$(document).ready(function(){
		$.ajax({
			type : "GET",
			url : "/tickets/topIssue",
			
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#pie',
				size : {
					height : 330
				},

				data : {
					json : data,
					type : 'pie'
				},
				pie : {
					label : {
						format : function(value, ratio, id) {
							return value;
						}
					}
				}

			});

		});
	});
</script>
@endsection