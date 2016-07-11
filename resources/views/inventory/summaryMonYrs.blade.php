@extends('inventory.inventory')

@section('title', 'RS | Summary for Month and Years')

@section('header-page')
                <div class="col-lg-10">
                    <h2>Summary for Month and Years</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                 
                        <li class="active">
                            <strong>Summary for Month and Years</strong>
                        </li>
                    </ol>
                </div>
                 @endsection	
@section('content')
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                  
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
							
								<form role="form" id="selectForm" name="selectForm"method="GET">
					<div class="row"> 
					
					<div class="col-lg-4"><img src="/img/label.jpg"/> </div>
				<div class="col-lg-offset-4 col-lg-2">
						
									<select class="form-control" id="monSelection" name="monSelection" data-id="">

										<option value="All months">All months</option>
										<option value="January">January</option>
										<option value="February">February</option>
										<option value="March">March</option>
										<option value="April">April</option>
										<option value="May">May</option>
										<option value="June">June</option>
										<option value="July">July</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="October">October</option>
										<option value="November">November</option>
										<option value="December">December</option>
										
									</select>
		</div>
		<div class="col-lg-2">
									<select class="form-control" id="yrSelection" name="yrSelection" data-id="">

										<option value="Current year">Current year</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
										<option value="2023">2023</option>
										<option value="2024">2024</option>
										<option value="2025">2025</option>
										
									</select>
						</div>
			</div>
					</form>
	
				<div class="row">
					<br>
					<div class="col-lg-4" align="center"> Mouse = </div>
	
				 	<div class="col-lg-4" align="center" id="laptopCount" name="laptopCount"></div>
	
					<div class="col-lg-4" align="center"> Monitor = </div>
					<br>
					<br>
				</div>				
				<div class="row">	
	
					<div class="col-lg-4" id="sparklineMouse" align="center"></div>
	
				 	<div class="col-lg-4" id="sparklineLaptop" align="center"></div>
	
					<div class="col-lg-4" id="sparklineMonitor" align="center"></div>
					
				</div>
				
    			<div class="row">
					<br>
				    <div class="col-lg-offset-2 col-lg-4" align="center"> Headset = </div>
				 	<div class="col-lg-4" align="center"> Keyboard = </div>
								<br>
					<br>
				</div>
    
                <div class="row">
            	    <div class="col-lg-offset-2 col-lg-4" id="sparklineHeadset"></div>
				 	<div class="col-lg-4" id="sparklineKeyboard"></div>
				</div>				
                
				
				</div>
			</div>			 
		 </div>
						             
						</div>	
						</div>
					
           
                   <!-- SUMMARY MONTH AND YEARS -->
	<script>
    
		$(document).ready(function() {
	
					
			$.ajax({
				type : "GET",
				url : "/inventory/sparkline",
				data : {
					months: $('#monSelection').val(),
					years: $('#yrSelection').val()
				}
			}).done(function(data) {
	
	
	console.log(data.brokenLaptopCount);
	console.log(data.workingLaptopCount);
	console.log(data.issueLaptopCount);
	console.log(data.laptopCount);
	
	var laptopCount = String (data.laptopCount);
					$('#laptopCount').text("Laptop = " + laptopCount);
	
	
			var sparklineCharts = function(){

                 $("#sparklineLaptop").sparkline([data.brokenLaptopCount,data.workingLaptopCount,data.issueLaptopCount], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#252B31', '#35E40E','#0E90E4']
                 });

				 $("#sparklineMouse").sparkline([data.brokenLaptopCount,data.workingLaptopCount,data.issueLaptopCount], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#252B31', '#35E40E','#0E90E4']
                 });
            		
            		};

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();
		
			});
	
		});
	

	</script>
	<!-- SUMMARY MONTH AND YEARS -->
	<script>
    
		$(document).ready(function() {
			$('#monSelection').add('#yrSelection').change(function() {
	
					
			$.ajax({
				type : "GET",
				url : "/inventory/sparkline",
				data : {
					months: $('#monSelection').val(),
					years: $('#yrSelection').val()
				}
			}).done(function(data) {
	
	
	console.log(data.brokenLaptopCount);
	console.log(data.workingLaptopCount);
	console.log(data.issueLaptopCount);
			
			var laptopCount = String (data.laptopCount);
					$('#laptopCount').text(laptopCount);
	
		
			var sparklineCharts = function(){

                $("#sparklineLaptop").sparkline([data.brokenLaptopCount,data.workingLaptopCount,data.issueLaptopCount], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#252B31', '#35E40E','#0E90E4']
                 });
            		
            
            
            
            
            
            
            
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();
		
			});
	
		});
	});
   

	</script>
	

Remote Staff Inventory Management System

@endsection