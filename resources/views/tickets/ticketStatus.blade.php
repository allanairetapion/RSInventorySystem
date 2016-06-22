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
					<table class="table table-bordered tickets">
						<thead >
							<tr>
								<th >Id</th>
								<th>Topic</th>
								<th>Status</th>
								<th>Date</th>
								<th>Assigned To:</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($tickets as $ticket)

							@if ($ticket->ticket_status == "Pending")
							<tr style="background-color: #F2F256;" id="{{$ticket->id}}">
								@elseif ($ticket->ticket_status == "Open")
							<tr class="bg-primary" id="{{$ticket->id}}">
								@else
							<tr class="navy-bg" id="{{$ticket->id}}">
								@endif

								<td>{{$ticket->id}}</td>
								<td>{{$ticket->description}}</td>
								<td>{{$ticket->ticket_status}}</td>
								<td >{{$ticket->created_at}}</td>
								<td>{{$ticket->first_name.' '.$ticket->last_name}}</td>
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
