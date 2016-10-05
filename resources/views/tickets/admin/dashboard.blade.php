@extends('tickets.ticketadminlayout')

@section('body')
<?php
$ntime = date ( 'Y-m-d' );
?>

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
	<div class="col-lg-9">
		<div class="ibox animated fadeInDown ">
			<div class="ibox-title">
				<div class="pull-right">
				</div>
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
								@foreach($noSupport as $noSup) 
								@if($noSup->assigned_support == 0 && $noSup->ticket_status != "Closed")
								<tr>

									<td><a href="/admin/tickets/{{$noSup->id}}">{{$noSup->id}}</a></td>
									<td class="text-center">
							@if($noSup->ticket_status == "Open") 
								<span class="label label-success">{{$noSup->ticket_status}}</span>
							@elseif($noSup->ticket_status == "Pending") 
								<span class="label label-warning">{{$noSup->ticket_status}}</span>
							@elseif($noSup->ticket_status == "Closed") 
								<span class="label label-primary">{{$noSup->ticket_status}}</span>
							@elseif($noSup->ticket_status == "Unresolved") 
								<span class="label label-danger">{{$noSup->ticket_status}}</span>
							@endif

							</td>
									<td>{{$noSup->description}}</td>
									<td>{{$noSup->subject}}</td>
									<td>{{$noSup->created_at}}</td>
									<td><select name="{{$noSup->id}}"
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
	<div class="col-md-3">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Top Support</h5>

			</div>
			<div class="ibox-content">
				<center>
					<div class="btn-group">
						<button type="button"
							class="btn btn-xs btn-default topSupportWeek">This Week</button>
						<button type="button"
							class="btn btn-xs btn-default topSupportMonth">This Month</button>
						<button type="button"
							class="btn btn-xs btn-default topSupportYear">This Year</button>
					</div>
				</center>
				<br>
				<table class="table table-striped">
					<tbody class="topSupport">
						<tr>
							<td>No Support Found</td>
						</tr>
					</tbody>
				</table>
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
				<table class="table table-bordered ticketStatusInfo" data-striping="false">
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
					<tfoot>
                                <tr>
                                    <td colspan="7">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
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
				<table class="table table-bordered topIssueInfo" data-striping="false">
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
					<tfoot>
                                <tr>
                                    <td colspan="8">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
				</table>

			</div>


		</div>

	</div>
</div>



<script type="text/javascript">
	$(document).ready(function() {
			$('table.ticketStatusInfo').footable();
			$('table.topIssueInfo').footable();
			$.ajax({
			type:'get',
			url:'/admin/ticketCount',
		}).done(function(data){
			console.log(data);
			$('h2.newTickets').text(data.openTickets);
			$('h2.pendingTickets').text(data.pendingTickets);
			$('h2.overdueTickets').text(data.overdueTickets);
			$('h2.closedTickets').text(data.closedTicketsToday);
		});
		$.ajax({
			type : "GET",
			url : "/admin/ticketStatus",
			
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#pie',
				

				data : {
					json : data,
					type : 'pie',
					onclick: function (d,e){
						$('table.ticketStatusInfo').hide();
						$('div.spiner').show();
						if(d.id != "Closed"){
							$('th.status').text('Assigned To');
						}else{
							$('th.status').text('Closed By');
						}
						
						var html;
						$.ajax({
							type : 'GET',
							url : '/admin/ticketStatus/info',
							data : d,
						}).done(function(data) {
							$.each(data.tickets, function( i, v ) {
								if (v.ticket_status == "Open") {
									html += "<tr class='bg-primary'>";
								} else if (v.ticket_status == "Pending") {
									html += "<tr style='background-color: #F2F256;'>";
								} else if (v.ticket_status == "Closed") {
									html += "<tr class='navy-bg'>";
								}else{
									html += "<tr class='red-bg'>";
								}
								html +=  "<td>" + v.id + "</td>" +
								"<td>" + v.sender_id + "</td>"+
								"<td>" + v.description + "</td>" +
								"<td>" + v.ticket_status + "</td>";
								
						if(v.ticket_status != "Closed"){
							html += "<td>" + v.assigned_support + "</td>";
						}else{
							html += "<td>" + v.closed_by + "</td>";
						}
						
						html += "<td>" + v.created_at + "</td>" +
								"<td>" + v.updated_at + "</td> </tr>" ;
								});
							
						$('tbody.ticketStatusInfo').html(html);
						$('table.ticketStatusInfo').trigger('footable_initialize');	
						$('div.spiner').hide();
						
						$('table.ticketStatusInfo').show();
						$('div#myModal').modal('show');
						});
					
						console.log(d);
						
						}
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
// Issues Pie Graph

		$.ajax({
			type : "GET",
			url : "/admin/topIssue",
			
		}).done(function(data) {
			console.log(data);
			c3.generate({
				legend: {
			        show: false
			    },
				bindto : '#pie2',	
				data : {
					json : data,
					type : 'pie',
					onclick: function (d,e){
						$('table.topIssueInfo').hide();
						$('div.spiner').show();
						var html;
						$.ajax({
							type : 'GET',
							url : '/admin/topIssue/info',
							data : d,
						}).done(function(data) {
							console.log(data.topic)
							$.each(data.tickets, function( i, v ) {
								if (v.ticket_status == "Open") {
									html += "<tr class='bg-primary'>";
								} else if (v.ticket_status == "Pending") {
									html += "<tr style='background-color: #F2F256;'>";
								} else if (v.ticket_status == "Closed") {
									html += "<tr class='navy-bg'>";
								}else{
									html += "<tr class='red-bg'>";
								}
								
								html +=  "<td>" + v.id + "</td>" +
								"<td>" + v.sender_id + "</td>"+
								"<td>" + v.topic_id + "</td>" +
								"<td>" + v.ticket_status + "</td>" +
								"<td>" + v.assigned_support + "</td>" +
								"<td>" + v.closed_by + "</td>"+
								"<td>" + v.created_at + "</td>" +
								"<td>" + v.updated_at + "</td> </tr>" ;
							});
							
						$('tbody.topIssueInfo').html(html);
						$('table.topeIssueInfo').trigger('footable_initialize');	
						$('div.spiner').hide();
						$('table.topIssueInfo').show();
						$('div#myModal2').modal('show');
						});
						
						console.log(d);
						
						}
						
						
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
		
		$.ajax({
			type : "GET",
			url : "/admin/ticketSummary"
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#stocked',
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

		$.ajax({
			type : "GET",
			url : "/admin/topSupport",
			data : {
				topSupport : "Week"
			},
		}).done(function(data) {
			var html;
			
			console.log(data);
			$.each(data, function(index, v) {
				if(index == 5){
					return false;
				}
				html += "<tr><td><span class='label label-info'>" + v.total + "</span></td><td>" + v.name + "</td></tr>";

			});

			$('tbody.topSupport').html(html);
		});

	});

</script>

@endsection

