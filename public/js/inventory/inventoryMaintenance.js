$(function(){
	
	$(document).ready(function() {
		$('.chosen-select', this).chosen('destroy').chosen();
		$('table#activityList').DataTable({
			dom: 'fgtp',
		});
		var maintenanceItems = null;
		$.ajax(
				{
					type : "GET",
					url : "/inventory/maintenanceItems/",
					success: function(data){
						maintenanceItems = data;
						
						$(maintenanceItems).each(function(i,e){
							console.log(e.stationNo);
							if(e.itemStatus == "Broken"){
								$('ellipse[id='+ e.stationNo +']').attr('fill',"#ff0000");
							}else if(e.itemStatus == "With Issue"){
								if ($('ellipse[id='+ e.stationNo +']').attr('fill') != "#ff0000"){
									$('ellipse[id='+ e.stationNo +']').attr('fill',"#ffff00");
								}
							}else{
								if ($('ellipse[id='+ e.stationNo +']').attr('fill') != "#ff0000"){
									$('ellipse[id='+ e.stationNo +']').attr('fill',"#00FF00");
								}
							}
						});
					}
				});
		
		
	     
		$('span.text-danger').hide();
		
	    $('.i-checks').iCheck({
	        checkboxClass: 'icheckbox_square-green',
	        radioClass: 'iradio_square-green'
	    });
	    $('.input-group.date').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		
		$('input.clockPicker').clockpicker({ autoclose:true });
		
	    $('input.datePicker').datepicker({
	    	format : 'yyyy-mm-dd',
	    	defaultDate: new Date(),
	        todayBtn: "linked",
	        keyboardNavigation: false,
	        forceParse: false,
	        autoclose: true
	    });

	    $('div#itemSummary').summernote({
			height: 150,
			minHeight: 150,             // set minimum height of editor
			maxHeight: 150,
			toolbar : [
						[
								'style',
								[
										'bold',
										'italic',
										'underline',
										'clear' ] ],
						[ 'fontname',
								[ 'fontname' ] ],
						[ 'fontsize',
								[ 'fontsize' ] ],
						[ 'color', [ 'color' ] ],
						[
								'para',
								[ 'ul', 'ol',
										'paragraph' ] ],
						 ] 
		 });

	    
	/* initialize the external events
	 -----------------------------------------------------------------*/


	$('#external-events div.external-event').each(function() {

	    // store data so the calendar knows to render an event upon drop
	    $(this).data('event', {
	        title: $.trim($(this).text()), // use the element's text as the event title
	        stick: true // maintain when user navigates (see docs on the renderEvent method)
	    });

	    // make the event draggable using jQuery UI
	    $(this).draggable({
	        zIndex: 1111999,
	        revert: true,      // will cause the event to go back to its
	        revertDuration: 0  //  original position after the drag
	    });

	});


	/* initialize the calendar
	 -----------------------------------------------------------------*/
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	var events = [];
	$.ajax(
			{
				async: false,
				type : "GET",
				url : "/inventory/maintenanceSchedules",
				success: function(data){
					events = data;
				}
			});
	$('#calendar').fullCalendar({
	    header: {
	        left: 'prev,next today',
	        center: 'title',
	        right: 'month,agendaWeek,agendaDay'
	    },
	    
	    eventLimit: true,
	    
	    drop: function() {
	        // is the "remove after drop" checkbox checked?
	        if ($('#drop-remove').is(':checked')) {
	            // if so, remove the element from the "Draggable Events" list
	            $(this).remove();
	        }
	    },
	    events:events,
	    
	    eventClick: function(calEvent, jsEvent, view) {
	    	swal({
	    		  title: calEvent.title,
	    		  text: moment(calEvent.start).format("hh:mm:ss a") + " - " + moment(calEvent.end).format("hh:mm:ss a") +"\n"+
	  			  calEvent.description,
	    		  showCancelButton: true,
	    		  confirmButtonColor: "#DD6B55",
	    		  confirmButtonText: "Edit",
	    		  closeOnConfirm: true
	    		},
	    		function(){
	    			$("input:checkbox").prop('checked', false);
	    			$('.icheckbox_square-green').removeClass('checked');
	    			$.ajax(
	    					{
	    						type : "GET",
	    						url : "/inventory/maintenanceSched/" + calEvent.id,
	    						success: function(data){
	        						
	        						var activities = data['activities'].split(",");
	        						console.log(activities);
	        							$('input#schedID').val(data['id']);
	    								$('input#editTitle').val(data['title']);
	    								$('select#editArea').val(data['area']);
	    								$('input#updateStartScheduleDate').val(moment(data['start_date']).format("YYYY-MM-DD"));
	    								$('input#updateStartScheduleTime').val(moment(data['start_date']).format("hh:mma"));
	    								$('input#updateEndScheduleDate').val(moment(data['end_date']).format("YYYY-MM-DD"));
	    								$('input#updateEndScheduleTime').val(moment(data['end_date']).format("hh:mma"));
										$('select#editAgentSelect').val(data['agents'].split(','));
										$('select#editStatus').val(data['status']);
	    								$(activities).each(function(i,e){
											$('#activity'+i).prop( "checked", true );
											$('#activity'+i).parent().addClass('checked');
	        							});

	    								$("#updateSchedule").modal('show');
	    							}
	    					});      	
	    			
	    		});
	    }   
	});
	});
	
	function convert_time(time_str){
		if(time_str == null)
			return false;
		
		    var time = time_str.match(/(\d+):(\d+)(\w)/);
		    var hours = Number(time[1]);
		    var minutes = Number(time[2]);
		    var meridian = time[3];

		    if (meridian == 'P' && hours < 12) {
		      hours = hours + 12;
		    }
		    else if (meridian == 'A' && hours == 12) {
		      hours = hours - 12;
		      
		    }
		    if (hours < 10){
				hours = "0"+ hours;
			    }
		    console.log(hours)
		    return String(hours+":"+minutes+":00");
		};
	$('button.saveSchedule').click(function(){
		$('div').removeClass('has-error');
		
		var start_date = $('input#startScheduleDate').val();
		
		var start_time =  ($('input#startScheduleTime').val() == "") ? "": convert_time($('input#startScheduleTime').val());
		
		var end_date = $('input#endScheduleDate').val();
		var end_time = convert_time($('input#endScheduleTime').val());
			
			$.ajax({
						type : "POST",
						url : "/inventory/addSchedule",
						data : $('form#addSchedule')
								.serialize(),
						success: function(data){
							
							var schedDescription = "Activities: " + "\n";
							$('input:checkbox.activity:checked').each(function () {
								schedDescription = schedDescription + $('label#'+$(this).val()).text() + ", ";
							  });
							var myEvent = {
									id: data.response['id'],
									  title:"Maintenance",
									  start: $.fullCalendar.moment(start_date + "T" + start_time),
									  end: $.fullCalendar.moment(end_date + "T"+ end_time),
									  description : schedDescription
									};
							$('form#addActivity').trigger('reset');
							$('div#addSchedule').modal('toggle');
									$('#calendar').fullCalendar( 'renderEvent', myEvent,true );
									var start = start_date + "T" + start_time+".196Z";
									console.log(start);
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

	$('button#updateSchedule').click(function(){
		var start_date = $('input#updateStartScheduleDate').val();
		
		var start_time =  ($('input#updateStartScheduleTime').val() == "") ? "": convert_time($('input#updateStartScheduleTime').val());
		
		var end_date = $('input#updateEndScheduleDate').val();
		var end_time = convert_time($('input#updateEndScheduleTime').val());
		$.ajax(
				{
					type : "PUT",
					url : "/inventory/updateMaintenanceSched",
					data : $('form#updateSchedule').serialize(),
					success: function(data){
						console.log("Success");
						var schedDescription = "Activities: " + "\n";
						$('input:checkbox.activity:checked').each(function () {
							schedDescription = schedDescription + $('label#'+$(this).val()).text() + ", ";
						  });
						var myEvent = {
								id: data.response,
								  title: $('input#editTitle').val(),
								  start: $.fullCalendar.moment(start_date + "T" + start_time),
								  end: $.fullCalendar.moment(end_date + "T"+ end_time),
								  description : schedDescription
								};
						$('form#addActivity').trigger('reset');
						$('div#updateSchedule').modal('toggle');
						$('#calendar').fullCalendar( 'removeEvents', [data.response] );
						$('#calendar').fullCalendar( 'renderEvent', myEvent,true );
								var start = start_date + "T" + start_time+".196Z";
								console.log(start);
						swal('Success!','','success');
						}
				});
	});

	$('button#addActivity').click(function(){
		console.log('clicked');
		$.ajax(
				{
					type : "POST",
					url : "/inventory/addActivity",
					data : $('form#addActivity')
							.serialize(),
					success: function(data){
							swal({
								title: "Success",
								text: "New Activity added",
								type: 'success'
								});
							var newActivity = "<div class='col-lg-3'>"+
							"<input type='checkbox' class='i-checks activity' name='activity[]' value='"+ data.response['id'] +"'> "+
							"<label id='"+ data.response['id'] +"'>"+ data.response['activity'] +"</label></div>";
							
							$('div.activity').prepend(newActivity);

							$('.i-checks').iCheck({
								checkboxClass : 'icheckbox_square-green',
								radioClass : 'iradio_square-green',
							});
							$('form#addActivity').trigger('reset');
							$('#newActivity').modal('hide');
							$('div#addSchedule').modal('toggle');
						}
				});
		
	});
	$('#addSchedule').on('shown.bs.modal', function () {
		$('.chosen-select', this).chosen('destroy').chosen();
		});
	$('#updateSchedule').on('shown.bs.modal', function () {
		$('.chosen-select', this).chosen('destroy').chosen();
		});
	$('#newActivity').on('shown.bs.modal', function () {
		$('#addSchedule').modal('hide');
		$('#updateSchedule').modal('hide');
		});
	$('#agentSelect').change(function(){
		console.log($(this).val());
	});
	$(document).on('change', 'select.mtItem', function() {
		var itemNo = $(this).attr('id');
		if($(this).val() == 'With Issue'){
			$('div#stationStatus').modal('toggle');
			$('div#stationStatus').on('hidden.bs.modal', function () {
				$('div#itemReport').modal('show');
				$('input#itemNo').val(itemNo);
				$('div.itemStatus').hide();
				$('button#issueItem').show();
				$('button#brokenItem').hide();
			});
			
		}else if($(this).val() == 'Broken') {
			$('div#stationStatus').modal('toggle');
			$('div#stationStatus').on('hidden.bs.modal', function () {
				$('div#itemReport').modal('show');
				$('input#itemNo').val(itemNo);
				$('div.itemStatus').show();
				$('button#issueItem').hide();
				$('button#brokenItem').show();
			});
			
		}else{
			$('div#stationStatus').modal('toggle');
			$('div#stationStatus').on('hidden.bs.modal', function () {
				$('div#repairReport').modal('show');
				$('input#repairItemNo').val(itemNo);
			});
		}
	});
	$('ellipse').click(function(){
		var stationNo = $(this).attr('id');
		$('span#stationDescription').text('Station Description');
		$('input#ipAddress').val('');
		$('div#stationStatus').modal('toggle');
		$('span#stationId').text(stationNo);
		$('tbody#stationStatus').html('');
		$.ajax(
				{
					type : "GET",
					url : "/inventory/maintenanceItem/" + stationNo,
					success: function(data){
						
						$(data.response).each(function(i,e){
							var html ="";
							html += '<tr><td class="text-center"><label class="control-label">' + e.itemType + ' - '+ e.itemNo + '</label></td>'+
								'<td><select class="form-control mtItem" id=' + e.itemNo + '>'+
								'<option value="">Working</option>'+
								'<option value="With Issue">With Issue</option>'+
								'<option value="Broken" selected> Broken</option>'+
							'</select></td></tr>';
							
							$('tbody#stationStatus').append(html);
							if(e.itemStatus == "With Issue" || e.itemStatus == "Broken"){
								$('select#' + e.itemNo ).val(e.itemStatus);
							}else{
								$('select#' + e.itemNo ).val('');
							}								
						});
						
						$(data.station).each(function(i,e){
							if(e.description){
								$('span#stationDescription').text(e.description);
							}
							if(e.ipAddress){
								$('input#ipAddress').val(e.ipAddress);
							}							
						});
						$('.i-checks').iCheck({
						        checkboxClass: 'icheckbox_square-green',
						        radioClass: 'iradio_square-green'
						    });
					    
						}
				}
				);
		
	});
	
var brokenItem = $('button#brokenItem').ladda();
	
	brokenItem.click(function(){
		$('input#itemSummary').val($('div#itemSummary').summernote('code'));
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "POST",
			url : "/inventory/brokenItem",
			data : $('form#itemReport').serialize() ,
		}).done(function(data){
			if(data.success == false){
				brokenItem.ladda('stop');
				if(data.errors["itemNo"]){
					
					$('span.itemNo').text(data.errors["itemNo"]).show();
					
					
					
						$('span.brokeneItemNo').text(data.errors["itemNo"]).show();
						$('div.itemNo').addClass('has-error');
					
					
				}
				if(data.errors["damage"]){
					$('span.itemDamage').text(data.errors["damage"]).show();
					$('div.itemDamage').addClass('has-error');
				}
				if(data.errors["summary"]){
					$('span.itemSummary').text(data.errors["summary"]).show();
					$('div.itemSummary').addClass('has-error');
				}
				
				if(data.errors["item_user"]){
					$('span.itemUser').text(data.errors["item_user"]).show();
					$('div.itemUser').addClass('has-error');
				}
				if(data.errors["status"]){
					$('span.itemStatus').text(data.errors["status"]).show();
					$('div.itemStatus').addClass('has-error');
				}
				if(data.errors["dateBroken"]){
					$('span.dateReported').text(data.errors["dateBroken"]).show();
					$('div.dateReported').addClass('has-error');
				}
			}else{
				brokenItem.ladda('stop');
				$('div#itemReport').modal('hide');
				swal('','Item Reported','success');
				$('ellipse[id=' + $('input#itemNo').val() +' ]').attr('fill',"#ff0000");
				
			}
			
		});
	});
	
var issueItem = $('button#issueItem').ladda();
	
	issueItem.on('click',function(){
		$('input#itemSummary').val($('div#itemSummary').summernote('code'));
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		
		$.ajax({
			type : "POST",
			url : "/inventory/issueItem",
			data : $('form#itemReport').serialize() ,
			error : function (data){
				var errors = data.responseJSON;
				issueItem.ladda('stop');
				
				if(errors.errors["itemNo"]){
					if(errors.errors['itemNo'] == "The selected item no is invalid."){
						$('span.itemNo').text("This Item is already Reported").show();
						$('div.itemNo').addClass('has-error');
					}
					$('span.itemNo').text(errors.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				if(errors.errors["item_user"]){
					$('span.itemUser').text(errors.errors["item_user"]).show();
					$('div.itemUser').addClass('has-error');
				}
				if(errors.errors["damage"]){
					$('span.itemDamage').text(errors.errors["damage"]).show();
					$('div.itemDamage').addClass('has-error');
				}
				if(errors.errors["issue"]){
					$('span.itemIssue').text(errors.errors["issue"]).show();
					$('div.itemIssue').addClass('has-error');
				}
				if(errors.errors["dateReported"]){
					$('span.dateReported').text(errors.errors["dateReported"]).show();
					$('div.dateReported').addClass('has-error');
				}
			},
			success : function(data){
				issueItem.ladda('stop');
				$('div#itemReport').modal('hide');
				swal('','Item Reported','success');
				$('ellipse[id=' + $('input#itemNo').val() +' ]').attr('fill',"#ffff00");
			}
		});
		
	});
var repairItem = $('button.repairItem').ladda();
	
	repairItem.click(function(){
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		console.log($('form#repairItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/repairItem",
			data : $('form#repairItem').serialize() ,
		}).done(function(data){
			if(data.success == false){
				repairItem.ladda('stop');
				
				if(data.errors["itemNo"]){
					$('span.repairItemNo').text(data.errors["itemNo"]).show();
					$('div.repairItemNo').addClass('has-error');
				}
				
				if(data.errors["dateRepair"]){
					$('span.dateRepair').text(data.errors["dateRepair"]).show();
					$('div.dateRepair').addClass('has-error');
				}
			}else{
				repairItem.ladda('stop');
				$('div#repairReport').modal('toggle');
				swal('','Item repaired.','success');
			}
			
		});
	});
	$('button#accomplishSchedule').click(function(){
		
		$.ajax({
			headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
			type : "PUT",
			url : "/inventory/accomplishMSched",
			data : { id : $('input#schedID').val()} ,
			success: function(data){
				$("#updateSchedule").modal('toggle');
				swal('','Schedule Accomplished','success');
			},
		});
	});
	$('button#updateActivity').click(function(){
		
		$.ajax({
			headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
			type : "PUT",
			url : "/inventory/updateActivity",
			data : $('form#updateActivity').serialize() ,
			success: function(data){
				const prevActivity = data.response['activity'];
				const prevDescription = data.response['description'];
				console.log(prevActivity);
				$("div#updateActivity").modal('toggle');
				$("label:contains('" + prevActivity + "')").text($('input#editActivity').val());
				$("td:contains('" + prevActivity + "')").text($('input#editActivity').val());
				$("td:contains('" + prevDescription + "')").text($('input#editDescription').val());
				swal('','Activity Updated','success');
			},
		});
	});
	$(document).on('click', 'a[href="#updateActivity"]', function() {
		const activityId = $(this).attr('id');
		$.ajax({
			headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
			type : "get",
			url : "/inventory/activityDetail",
			data : { id : activityId} ,
			success: function(data){
				$('form#updateActivity').trigger('reset');
				$('input#editActivityID').val(data.id);
				$('input#editActivity').val(data.activity);
				$('textarea#editDescription').val(data.description);
				$('div#updateActivity').modal('toggle');
			},
		});
	});
	$(document).on('click', 'a[href="#deleteActivity"]', function() {
		const activityId = $(this).attr('id');
		const rowActivity = $(this);
		swal({
			  title: "Are you sure?",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonText: "Yes",
			  closeOnConfirm: false
			},
			function(){
				$.ajax({
					headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
					type : "Delete",
					url : "/inventory/deleteActivity",
					data : { id : activityId} ,
					success: function(data){
						var table = $('table#activityList').DataTable();
						table.row($(rowActivity).parents('tr')).remove().draw();
						swal('','Activity Deleted','success');
						
					},
				});
			});
		
	});
	$('button#deleteSchedule').click(function(){
		const deleteSchedule = $('input#schedID').val();
		console.log(deleteSchedule);
		swal({
			  title: "Are you sure?",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonText: "Yes",
			  closeOnConfirm: false
			},
			function(){
				$.ajax({					
					type : "Delete",
					url : "/inventory/deleteSchedule",
					data : $('form#updateSchedule').serialize() ,
					success: function(data){
						var calendar = $('#calendar').fullCalendar( 'removeEvents', deleteSchedule );
						$('div#updateSchedule').modal('toggle');
						$('form').trigger('reset');
						swal('','Schedule Deleted','success');						
					},
				});
			});
	});
	$('a[href="#editStationDescription"]').click(function(){
		//$('div#stationStatus').modal('toggle');
		swal({
			  title: "Change Station Description",
			  text: "Type Station's new description",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  confirmButtonText: "Save",
			  inputPlaceholder: "Write something"
			},
			function(inputValue){
			  if (inputValue === false) return false;
			  
			  if (inputValue === "") {
			    swal.showInputError("You need to write something!");
			    return false
			  }
			  
			  $.ajax({
				  	headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
					type : "PUT",
					url : "/inventory/updateStationDescription",
					data : {id : $('span#stationId').text(),description : inputValue } ,
					success: function(data){
						swal('','Description Updated','success');
						$('span#stationDescription').text(inputValue);
					},
					error: function(){
						swal('Ooops','Something went wrong','error');
					}
				});
			  
			});
	});
	$('button#editStationIpAddress').click(function(){
		
		swal({
			  title: "Are you sure?",
			  text: "Change Station's I.P Address to " + $('input#stationIpAddress').val(),
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  confirmButtonText: "Save",
			  inputPlaceholder: "Write something"
			},
			function(inputValue){
				  if (inputValue === false) return false;
				  
				  if (inputValue === "") {
				    swal.showInputError("You need to write something!");
				    return false
				  }
				  
				 $.ajax({
					  	headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
						type : "PUT",
						url : "/inventory/updateStationIpAddress",
						data : {id : $('span#stationId').text(), ipAddress : inputValue } ,
						success: function(data){
							swal('','I.P. Address Updated','success');	
							$('input#ipAddress').val(inputValue);
						},
						error: function(){
							swal('Ooops','Something went wrong','error');
						}
					});
			});
		
		
	});
});