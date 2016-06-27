@extends('tickets.ticketadminlayout')
@section('body')
<div class="row">

	<div class="col-md-6 animated fadeInDown">
		
		<div class="ibox">
			<div class="ibox-title">
				My Assignments
			</div>
			<div class="ibox-content">
				<div class="row">
					
					<div class="col-md-6  b-r">
						<p>
							Assigned <span class="pull-right label label-info">Total</span>
						</p>
						<h2 class="font-bold">{{count($tickets)}}</h2>
					</div>
					<div class="col-md-6">
						<p>
							Closed <span class="pull-right label label-info">Today</span>
						</p>
						<h2 class="font-bold">{{count($closedTickets)}}</h2>
					</div>
					
					<div class="col-md-12">
						<br>
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th> Ticket No.</th>
									<th> Topic</th>
									<th> Date</th>
								</tr>
							</thead>
							<tbody>
								@foreach($tickets as $ticket)
								<tr onclick="window.document.location='/admin/tickets/{{$ticket->id}}'">
									
									<td>{{$ticket->id}}</td>
									<td>{{$ticket->topic_id}}</td>
									<td>{{$ticket->created_at}}</td>
									
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6 animated fadeInDown">
		<div class="ibox">
			<div class="ibox-title">
				Ticket Stat
			</div>
			<div class="ibox-content">
				<div>
					<div id="slineChart" ></div>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$.ajax({
			type : "GET",
			url : "/admin/ticketStat"
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#slineChart',
				
				data : {
					x : 'x',
					columns : data,

					type : 'spline',
					groups : [['data1', 'data2']]
				},
				axis : {
					x : {
						type : 'category',

					}
				}
			});

		});
	});

</script>
@endsection