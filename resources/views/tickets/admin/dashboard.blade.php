@extends('tickets.ticketadminlayout')

@section('body')
<?php
$ntime = date('Y-m-d');
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
					<span> New Tickets </span>
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
</div>

<div class="row">

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

	<div class="col-md-6">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Tickets By Status</h5>
			</div>
			<div class="ibox-content">
				<br>
				<div id="pie"></div>

			</div>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-lg-9">
		<div class="ibox animated fadeInDown ">
			<div class="ibox-title">
				<div class="pull-right">

					<button type="button" class="ladda-button btn btn-primary btn-sm noSupport">
						Apply
					</button>

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
								<th>Topic </th>
								<th>Subject </th>
								<th>Date </th>
								<th>Assign Support </th>

							</tr>
						</thead>
						<tbody>
							@foreach($noSupport as $noSup)
							@if($noSup->assigned_support == 0)
							<tr>
								
								<td>{{$noSup->id}}</td>
								<td>{{$noSup->description}}</td>
								<td>{{$noSup->subject}}</td>
								<td>{{$noSup->created_at}}</td>
								<td>
								<select name="{{$noSup->id}}" class="form-control noSupport">
									<option value="" selected > Assign a support... </option>
									@foreach ($agent as $agents)
									<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
									@endforeach
								</select></td>

							</tr>
							@endif
							@endforeach
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
						<button type="button" class="btn btn-xs btn-default topSupportWeek">
							This Week
						</button>
						<button type="button" class="btn btn-xs btn-default topSupportMonth">
							This Month
						</button>
						<button type="button" class="btn btn-xs btn-default topSupportYear">
							This Year
						</button>
					</div>
				</center>
				<br>
				<table class="table table-striped" >
					<tbody class="topSupport">
						<tr>
							<td>No Support Found</td>							
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
	$(document).ready(function() {
			$.ajax({
			type:'get',
			url:'/admin/ticketCount',
		}).done(function(data){
			console.log(data);
			$('h2.newTickets').text(data.newTickets);
			$('h2.pendingTickets').text(data.pendingTickets +  data.openTickets);
			$('h2.overdueTickets').text(data.overdueTickets);
			$('h2.closedTickets').text(data.closedTickets);
		});
		$.ajax({
			type : "GET",
			url : "/admin/ticketStatus",
			
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#pie',
				size : {
					height : 302
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

