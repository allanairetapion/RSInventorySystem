$(function(){
	$(document).ready(function(){
		$('.footable').footable();
		$('span.text-danger').hide();
		$('.chosen-select', this).chosen();
		$('input.clockPicker').clockpicker({ autoclose:true });
		
	    $('input.datePicker').datepicker({
	    	format : 'yyyy-mm-dd',
	    	defaultDate: new Date(),
	        todayBtn: "linked",
	        keyboardNavigation: false,
	        forceParse: false,
	        autoclose: true
	    });
		var summaryData;
		var summaryXAxis;
		$.ajax(
				{
					type : "GET",
					url : "/inventory/dashboardSummary/",
					success: function(data){
						console.log(data.summaryData);
						summaryData = data.summaryData;
						summaryXAxis = data.summaryXaxis;
				
	var data1 = summaryData;
	       
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
	                    	 ticks: summaryXAxis
	                     },
	                     yaxis: {
	                         ticks: 4
	                     },
	                     tooltip: true
	                 }
	         );
					}
				});
	});
	         
	$('#maintenance').click(function(){
		$('.vertical-container').html('');
		$.ajax(
				{
					type : "GET",
					url : "/inventory/maintenanceDashboard/",
					success: function(data){
						var index = 1;
						var currentDate = "";
						var previousDate = "";
						$(data).each(function(i,e){
							var html = "";
						    $(e).each(function(i,e){
							    var status = "";
							    if(e['status'] == 'urgent'){
								    status = "red-bg";
								}else{
									status = "navy-bg";
								}
							   
							    	html = '<div class="vertical-timeline-block">'+
			                        		'<div class="vertical-timeline-icon ' + status + '">'+
			                            		'<i class="fa fa-calendar"></i>'+
			                        		'</div>'+
			                        	'<div class="vertical-timeline-content ' + status + '">'+
			                            '<h2>' + e['title']+ '</h2>'+
			                            '<p>'+ e['activities'] + '</p>'+
			                            '<span>' + moment(e['start_date']).format("MMM DD") + '<span>'+
			                            '</div> </div>';
		                            currentDate = moment(e['start_date']).format("MMM DD");   
			                                       
							});
						    if(currentDate == previousDate){
								$('#tab-'+(index)+' .vertical-container').append(html);
								console.log('true');
							}else{
								index = i + 1;
								$('a[href="#tab-'+(index)+'"]').removeClass('hide').text(currentDate);
								$('#tab-'+(index)+' .vertical-container').append(html);
							}
							previousDate = currentDate;     
							
						});
						
						}
				});      	
		
		$('#mSchedule').modal('toggle');
		
		
	});
	$('a#itemType').click(function(){
		console.log('click');
		if($(this).text() == "All"){
			$('.footable').trigger('footable_clear_filter');
		}else{
			$('.footable').trigger('footable_filter', {filter: $(this).text()});
		}
	});
	$('button#addSchedule').click(function(){
		$('div#mSchedule').modal('toggle');
		$('div#mSchedule').on('hidden.bs.modal', function () {
			$('div#addSchedule').modal('show');
		});
	});
	$('button.saveSchedule').click(function(){
			
			$.ajax({
						type : "POST",
						url : "/inventory/addSchedule",
						data : $('form#addSchedule')
								.serialize(),
						success: function(data){
							
							var schedDescription = "Activities: " + "\n";
							$('form#addActivity').trigger('reset');
							$('div#addSchedule').modal('toggle');
							swal('Success!','New schedule has been added','success');
							},
						error: function(data){
							console.log("here");
							var errors = data.responseJSON;
							if(errors.errors['title']){
								$('span.title').show().text(errors.errors['title']);
								$('div.title').addClass('has-error');
								}
							
							
							if(errors.errors['area']){
								$('span.area').show().text(errors.errors['area']);
								$('div.area').addClass('has-error');
								}
							
							
					if(errors.errors['startScheduleDate']){
						$('span.startScheduleDate').show().text(errors.errors['startScheduleDate']);
						$('div.startScheduleDate').addClass('has-error');
						}
					if(errors.errors['startScheduleTime']){
						$('span.startScheduleTime').show().text(errors.errors['startScheduleTime']);
						$('div.startScheduleTime').addClass('has-error');
						}
					if(errors.errors['endScheduleDate']){
						$('span.endScheduleDate').show().text(errors.errors['endScheduleDate']);
						$('div.endScheduleDate').addClass('has-error');
						}
					if(errors.errors['endScheduleTime']){
						$('span.endScheduleTime').show().text(errors.errors['endScheduleTime']);
						$('div.endScheduleTime').addClass('has-error');
						}
					if(errors.errors['activity']){
						swal('Ooops...','Select an activity','error');
						}
					
					}			
					});
			
	});
	
});