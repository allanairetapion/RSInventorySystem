@extends('inventory.inventory') 

@section('title', 'RS | Dashboard')

@section('sidebarDashboard')
<li class="active"><a href="/inventory/index"><i class="fa fa-th-large"></i>
		<span class="nav-label">Dashboard</span></a></li>
@endsection 

@section('header-page')



<div class="col-lg-3">
	<h2>Welcome {{
		Auth::guard('inventory')->user()->adminProfile->first_name }}!</h2>
		<h3> Summary </h3>
	<ul class="list-group clear-list m-t">
	
		<li class="list-group-item fist-item"><span class="pull-right">Borrowed
				Items</span> <span class="label label-success">{{$brokenCount}}</span>
		</li>
		<li class="list-group-item"><span class="pull-right">Returned Items</span>
			<span class="label label-info">{{$returnCount}}</span></li>
		<li class="list-group-item"><span class="pull-right">Items with Issues</span>
			<span class="label label-warning">{{$issueCount}}</span></li>
		<li class="list-group-item"><span class="pull-right">Broken Items</span>
			<span class="label label-danger">{{$brokenCount}}</span></li>
	</ul>

</div>
<div class="col-lg-6">
	<div class="flot-chart dashboard-chart">
		<div class="flot-chart-content" id="flot-dashboard-chart"></div>
	</div>
</div>
<div class="col-lg-3">
	<div class="statistic-box">
		<h4>Inventory Count</h4>

		<div class="row text-center">

			<div class="col-lg-12">
				<canvas id="doughnutChart"></canvas>

			</div>
		</div>

	</div>
</div>


@endsection @section('content')

<div class="row">


	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-content">

				


				<table class="table table-bordered" id="dashboard">
					<thead>
						<tr>
							<th>Item Type</th>
							<th>Over All</th>
							<th>Working</th>
							<th>With Issue</th>
							<th>Broken</th>
						</tr>
					</thead>
					<tbody>
						@foreach($itemReports as $report)
						<tr>
							<td><a href="#" id="itemType" data-toggle="modal"
						data-target="#myModal">{{$report->itemType}}</a></td>
							<td>{{$report->overall}}</td>
							<td>{{($report->working == null) ? 0 : $report->working}}</td>
							<td>{{($report->issue == null) ? 0 : $report->issue}}</td>
							<td>{{($report->broken == null) ? 0 : $report->broken}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Borrow Report</h4>
			</div>

			<div class="ibox-content">
				<table class="table table-bordered" id="itemTypeSummary">
					<thead>
						<tr>
							<th>Unique Id</th>
							<th>Item No. </th>
							<th>Item Type </th>
							<th>Brand</th>
							<th>Model</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
			</div>

		</div>

	</div>
</div>



<!-- Flot -->
<script src="/js/plugins/flot/jquery.flot.js"></script>
<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/js/plugins/flot/jquery.flot.pie.js"></script>

<!-- ChartJS-->
<script src="/js/plugins/chartJs/Chart.min.js"></script>
<script>
$(document).ready(function(){
	$('table#itemTypeSummary').footable();
	
});
var data1 = <?php echo json_encode($summaryData); ?>;
         
         $("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), 
        		 data1,
         
                 {
                     series: {
                         lines: {
                             show: false,
                             fill: true
                         },
                         splines: {
                             show: true,
                             tension: 0.4,
                             lineWidth: 1,
                             fill: 0.4
                         },
                        
                         
                         legend: { show: true, placement: 'outsideGrid', container: $('#legendholder') },
                         points: {
                             radius: 0,
                             show: true
                         },
                         shadowSize: 2
                     },
                     grid: {
                         hoverable: true,
                         clickable: true,
                         tickColor: "#d5d5d5",
                         borderWidth: 1,
                         color: '#d5d5d5'
                     },
                     
                     xaxis:{
                    	 ticks: <?php echo json_encode($summaryXaxis); ?>
                     },
                     yaxis: {
                         ticks: 4
                     },
                     tooltip: true
                 }
         );
         function getRandomColor() {
        	    var letters = '0123456789ABCDEF'.split('');
        	    var color = '#';
        	    for (var i = 0; i < 6; i++ ) {
        	        color += letters[Math.floor(Math.random() * 16)];
        	    }
        	    return color;
        	}
		var itemTypes = <?php echo json_encode($types); ?>;
		
		$.each(itemTypes, function(i,v){
			itemTypes[i]["color"] = getRandomColor();
			itemTypes[i]["highlight"] = "#1ab394";
			});
		console.log(itemTypes);
         

                         var doughnutOptions = {
                             segmentShowStroke: true,
                             segmentStrokeColor: "#fff",
                             segmentStrokeWidth: 2,
                             percentageInnerCutout: 45, // This is 0 for Pie charts
                             animationSteps: 100,
                             animationEasing: "easeOutBounce",
                             animateRotate: true,
                             animateScale: false,
                             responsive: true,
                         };


                         var ctx = document.getElementById("doughnutChart").getContext("2d");
                         var myNewChart = new Chart(ctx).Doughnut(itemTypes, doughnutOptions);

         $('select#itemType').change(function(){
			$('table#dashboard').addClass('animated').addClass('fadeOut');
			setTimeout(function(){ 
				 $('table#dashboard').removeAttr('class').attr('class', 'table table-bordered');
	               
				$('table#dashboard').removeClass('animated');
				$('table#dashboard').addClass('animated').addClass('fadeIn'); }, 3000);
             });

         $('a#itemType').click(function(){
        	 $.ajax(
						{
							type : "GET",
							url : "/inventory/itemTypeSummary",
							data : { itemType : $(this).text()},
							success: function(data){
								var table = $('table#itemTypeSummary').data('footable');
								$('table#itemTypeSummary > tbody').empty();
								if(data.response != null){
									$.each(data.response,function(i, v) {
										var newRow = "<tr><td>" + v.unique_id + "</td><td>" + v.itemNo + " </td>"+
													"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
													"<td>" + v.itemStatus + "</td></tr>";
										table.appendRow(newRow);
										});
									}else{
									table.appendRow("<tr><td colspan='9' class='text-center'> No Data Found.</td></tr>");
									}
								}
						});
             });
</script>
@endsection


