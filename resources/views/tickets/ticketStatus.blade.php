@extends('tickets.ticketclientlayout')
@section('body')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
					<h2 class="font-bold">All tickets</h2>
				</div>
				<div class="ibox-content">
					<table class="table table-hover issue-tracker">
				<thead>
					<tr>
					<th class="text-center">Status</th>
						<th>Ticket No:</th>
						<th>Topic</th>
						
						
						<th>Date Sent</th>
						<th>Assigned to:</th>
						<th>Closed By:</th>
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
						@foreach($closedby as $close)
							@if($close->id == $ticket->id)
							<td>{{$close->first_name.' '.$close->last_name}}</td>
							@endif
						@endforeach
					</tr>
					@endforeach				
										
				</tbody>
			</table>
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
