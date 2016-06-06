@extends('tickets.ticketclientlayout')
@section('body')
<div class="row">
	<div class="col-md-8">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
					<h2 class="font-bold">View tickets</h2>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered tickets">
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
							<tr>

								<td>{{$ticket->id}}</td>
								<td>{{$ticket->description}}</td>
								<td>{{$ticket->ticket_status}}</td>
								<td >{{$ticket->created_at}}</td>
								<td>{{$ticket->assigned_support}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>

				</div>
			</div>

		</div>
	</div>
</div>
@endsection
