@extends('inventory.inventory') @section('title', 'RS | Dashboard')

@section('sidebarDashboard')
<li class="active"><a href="/inventory/index"><i class="fa fa-th-large"></i>
		<span class="nav-label">Dashboard</span></a></li>
@endsection @section('header-page')



<div class="col-lg-3">
	<h2>Welcome {{
		Auth::guard('inventory')->user()->adminProfile->first_name }}!</h2>
	<h3>Summary</h3>
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
<div class="statistic-box">
	<div class="col-lg-3">
		<h3>Maintenance Schedule</h3>
		<div class="widget style1">
			<div class="row">
				<div class="col-xs-4 text-center">
					<i class="fa fa-calendar fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">

					<h2 class="font-bold">{{$mScheds}}</h2>
					<span> Scheduled Today </span>
				</div>
			</div>
		</div>
		<button class="btn btn-block btn-white" id="maintenance">List</button>

	</div>

</div>


@endsection @section('content')

<div class="row">


	<div class="col-lg-3">
		<div class="row text-center">
			<h4>Inventory by Type</h4>


			<canvas id="doughnutChart"></canvas>

		</div>
	</div>



</div>


<div id="mSchedule" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Maintenance Schedule</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#tab-1"> Today</a></li>
								<li class=""><a data-toggle="tab" href="#tab-2"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-3"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-4"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-5"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-6"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-7"></a></li>
								<li class=""><a data-toggle="tab" href="#tab-8"></a></li>
							</ul>
							<div class="tab-content">
								
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-4" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-5" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-6" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
								<div id="tab-7" class="tab-pane">
									<div class="panel-body">
										<div id="vertical-timeline" class="vertical-container">
											<div class="vertical-timeline-block">
												<div class="vertical-timeline-icon red-bg">
													<i class="fa fa-calendar"></i>
												</div>

												<div class="vertical-timeline-content red-bg">
													
												</div>
											</div>



										</div>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>


			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-w-m btn-danger btn-outline"
					data-dismiss="modal">Close</button>
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
	
	 var config = {
             '.chosen-select'           : {},
             '.chosen-select-deselect'  : {allow_single_deselect:true},
             '.chosen-select-no-single' : {disable_search_threshold:10},
             '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
             '.chosen-select-width'     : {width:"95%"}
         }
         for (var selector in config) {
             $(selector).chosen(config[selector]);
         }
	
});
$('#maintenance').click(function(){
	$.ajax(
			{
				type : "GET",
				url : "/inventory/maintenanceSchedDetails/",
				success: function(data){
					var index = 7;
					$(data).each(function(i,e){
						var html = "";
					    $(e).each(function(i,e){
						    var status = "";
						    if(e['status'] == 'urgent'){
							    status = "red-bg";
							}else{
								status = "navy-bg";
							}
						    if(e['start_date']){
						    	html += '<div class="vertical-timeline-block">'+
		                        		'<div class="vertical-timeline-icon ' + status + '">'+
		                            		'<i class="fa fa-calendar"></i>'+
		                        		'</div>'+
		                        	'<div class="vertical-timeline-content ' + status + '">'+
		                            '<h2>' + e['title']+ '</h2>'+
		                            '<p>'+ e['activities'] + '</p></div> </div>';
							}else{
								html = '<div class="vertical-timeline-block">'+
                        		'<div class="vertical-timeline-icon ' + status + '">'+
                        		'<i class="fa fa-calendar"></i>'+
                    		'</div>'+
                    	'<div class="vertical-timeline-content ' + status + '">'+
                        '<h2> No Schedule </h2>></div> </div>';
							}
							
                            
						});
						if(data[i][0]){
							var date = data[i][0]['start_date'];
							$('a[href="#tab-' + (7- i) +'"').text(moment(date).format("MMM DD"));
						}else{
							if(i != 6){
								$('a[href="#tab-' + (7- i) +'"').addClass('hide');
							}
						}
						
						console.log(data[i][0] );
					    $('#tab-'+(7- i)+' .vertical-container').html(html);
						index--;
					});
					
					}
			});      	
	
	$('#vertical-timeline').toggleClass('center-orientation');
	$('#mSchedule').modal('toggle');
	
	
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

         
</script>
@endsection


