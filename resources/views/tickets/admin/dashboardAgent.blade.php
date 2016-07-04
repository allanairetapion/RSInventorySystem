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
							New <span class="pull-right label label-info">Today</span>
						</p>
						<h2 class="font-bold newTickets"></h2>
					</div>
					<div class="col-md-6">
						<p>
							Pending <span class="pull-right label label-info">Total</span>
						</p>
						<h2 class="font-bold pendingTickets"></h2>
					</div>
					
					<hr/>
					
					<div class="col-md-6  b-r">
						<p>
							Overdue <span class="pull-right label label-info">Total</span>
						</p>
						<h2 class="font-bold overdueTickets"></h2>
					</div>
					<div class="col-md-6">
						<p>
							Closed <span class="pull-right label label-info">Today</span>
						</p>
						<h2 class="font-bold closedTickets"></h2>
					</div>
					
					<div class="col-md-12">
						<br>
						<h4>Assigned Tickets</h4>
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
									<td>{{$ticket->description}}</td>
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
			type:'get',
			url:'/admin/ticketCount',
		}).done(function(data){
			console.log(data);
			$('h2.newTickets').text(data.newTickets);
			$('h2.pendingTickets').text(data.pendingTickets);
			$('h2.overdueTickets').text(data.overdueTickets);
			$('h2.closedTickets').text(data.closedTickets);
		});
		
		
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