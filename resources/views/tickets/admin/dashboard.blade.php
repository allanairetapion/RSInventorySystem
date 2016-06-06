@extends('tickets.ticketadminlayout')

@section('body')
<?php
$ntime = date('Y-m-d');
?>


<div class="row">
	<div class="col-md-3 animated fadeInDown">
		<div class="widget style1 navy-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-ticket fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">					
					<span> New Tickets </span>
					<h2 class="font-bold">{{count($newTickets)}}</h2>
					<small>Today</small>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<div class="widget style1 yellow-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="glyphicon glyphicon-time  fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Pending Tickets </span>
					<h2 class="font-bold">{{count($pendingTickets)}}</h2>
					<small>Today</small>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<div class="widget style1 red-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-warning fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Overdue Tickets </span>
					<h2 class="font-bold">{{count($overdueTickets)}}</h2>
					<small>Today</small>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 animated fadeInDown">
		<div class="widget style1 lazur-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-thumbs-o-up fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Closed Tickets </span>
					<h2 class="font-bold">{{count($closedTickets)}}</h2>
					<small>Today</small>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Ticket Report</h5>
				<div class="pull-right">
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-default">
							This Week
						</button>
						<button type="button" class="btn btn-xs btn-default">
							This Month
						</button>
						<button type="button" class="btn btn-xs btn-default">
							This Year
						</button>
					</div>
				</div>

			</div>
			<div class="ibox-content">
				<div>
					<canvas id="barChart" height="140"></canvas>
				</div>
			</div>
		</div>

	</div>

	<div class="col-md-3">
		<div class="ibox animated fadeInDown float-e-margins">
			<div class="ibox-title">
				<h5>Top Issues</h5>

			</div>
			<div class="ibox-content">						
				<center>
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-default topIssueWeek ">
							This Week
						</button>
						<button type="button" class="btn btn-xs btn-default topIssueMonth">
							This Month
						</button>
						<button type="button" class="btn btn-xs btn-default topIssueYear">
							This Year
						</button>
					</div>
				</center>
				<br>
				<div class="flot-chart">
					<div class="flot-chart-pie-content" id="flot-pie-chart"></div>
				</div>
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
						<button type="button" class="btn btn-xs btn-default">
							This Week
						</button>
						<button type="button" class="btn btn-xs btn-default">
							This Month
						</button>
						<button type="button" class="btn btn-xs btn-default">
							This Year
						</button>
					</div>
				</center>
				<br>
				<table class="table table-striped" >
					<tbody class="">
						<tr>
							<td><span class="label label-info">30</span></td>
							<td><small>Ako</small></td>
						</tr>
						<tr>
							<td><span class="label label-info">25</span></td>
							<td><small>Ako</small></td>
						</tr>
						<tr>
							<td><span class="label label-info">20</span></td>
							<td><small>Ako</small></td>
						</tr>
						<tr>
							<td><span class="label label-info">15</span></td>
							<td><small>Ako</small></td>
						</tr>
						<tr>
							<td><span class="label label-info">10</span></td>
							<td><small>Ako</small></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox animated fadeInDown ">
			<div class="ibox-title">
				<div class="pull-right">

					<button type="button" class="btn btn-primary btn-sm">
						Apply
					</button>

				</div>
				<h3 class="font-bold">Assign a Support</h3>

			</div>
			<div class="ibox-content">
				@if(count($noSupport) != 0)
				<div class="table-responsive">

					<table class="table table-striped noSupport">
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
							@if($noSup->assigned_support == null)
							<tr>
								<td>{{$noSup->id}}</td>
								<td>{{$noSup->description}}</td>
								<td>{{$noSup->subject}}</td>
								<td>{{$noSup->created_at}}</td>
								<td>
								<select name="assigned_support" class="form-control topic">
									<option value="" disabled selected hidden> Assign a support... </option>
									@foreach ($agent as $agents)
									<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
									@endforeach
								</select></td>

							</tr>
							@endif
							@endforeach
						</tbody>
					</table>
				</div>
				@else
				<div class="jumbotron">
					<h1>No Tickets found</h1>
				</div>
				@endif
			</div>
		</div>
	</div>

</div>
        

        <script src="/js/jquery-2.1.1.js"></script>
		
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Flot -->
		<script src="/js/plugins/flot/jquery.flot.js"></script>
		<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
		<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
		<script src="/js/plugins/flot/jquery.flot.pie.js"></script>
		
<script type="text/javascript">	
		
			$(function() {
                
                var data1= <?php echo json_encode($sample); ?> ;
                
                console.log(data1);				

				var plotObj = $.plot($("#flot-pie-chart"), data1, {
					series : {
						pie : {
							show : true
						}
					},
					grid : {
						hoverable : true
					},
					tooltip : true,
					tooltipOpts : {
						content : "%p.0%, %s", // show percentages, rounding to 2 decimal places
						shifts : {
							x : 20,
							y : 0
						},
						defaultTheme : false
					}
				});

			});
			
			$('button.topIssueWeek').click(function(){
				$(this).addClass('Active');
				$('button.topIssueMonth').removeClass('Active');
				$('button.topIssueYear').removeClass('Active');
			});
			
			$('button.topIssueMonth').click(function(){
				$(this).addClass('Active');
				$('button.topIssueWeek').removeClass('Active');
				$('button.topIssueYear').removeClass('Active');								
			});
			
			$('button.topIssueYear').click(function(){
				$(this).addClass('Active');
				$('button.topIssueWeek').removeClass('Active');
				$('button.topIssueMonth').removeClass('Active');
			});
				
</script>
	
@endsection

