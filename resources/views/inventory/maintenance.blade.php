 @extends('inventory.inventory') @section('title', 'RS | Maintenance')

@section('header-page')
<div class="col-lg-10">
	<h2>Maintenance</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Maintenance</strong></li>
	</ol>
</div>

@endsection @section('content')
<div class="row">
	<div class="col-lg-8 sample">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<div class="pull-right">
					<button class="btn btn-primary btn-sm addSchedule"
						data-toggle="modal" data-target="#addSchedule">Add Schedule</button>
				</div>
				<h4>Maintenance Schedule</h4>

			</div>
			<div class="ibox-content">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
	
	<div id="addSchedule" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Schedule</h4>
				</div>

				<div class="modal-body">
					<form class="form-horizontal" id="addSchedule">
						{!! csrf_field() !!}
						<div class="form-group">
							<div class="title">
								<label class="col-lg-2 control-label">Title :</label>

								<div class="col-lg-10">
									<input type="text" name="title" value="Scheduled Maintenance"
										class="form-control"> <span
										class="help-block text-danger title">Example block-level help
										text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="area">
								<label class="col-lg-2 control-label">Area :</label>

								<div class="col-lg-10">
									<select name="area" class="col-lg-5 form-control">
										<option value="" selected></option> @foreach($areas as $area)
										<option value="{{$area->id}}">{{$area->area}}</option>
										@endforeach


									</select> <span class="help-block text-danger area">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="startScheduleDate">
								<label class="col-lg-2 control-label">From:</label>

								<div class="col-lg-5">
									<input type="text" name="startScheduleDate"
										placeholder="Start Date" id="startScheduleDate"
										class="form-control datePicker"> <span
										class="help-block text-danger startScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="startScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="startScheduleTime"
										id="startScheduleTime" class="clockPicker form-control"
										placeholder="Start Time"> <span
										class="help-block text-danger startScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="endScheduleDate">
								<label class="col-lg-2 control-label">To:</label>

								<div class="col-lg-5">
									<input type="text" name="endScheduleDate" id="endScheduleDate"
										placeholder="End Date" class="form-control datePicker"> <span
										class="help-block text-danger endScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="endScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="endScheduleTime" id="endScheduleTime"
										class="clockPicker form-control" placeholder="End Time"> <span
										class="help-block text-danger endScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Agents:</label>
							<div class="col-lg-10">
								<div class="input-group">
									<select data-placeholder="Choose an agent" id="agentSelect"
										name="agents[]" class="chosen-select form-control" multiple
										style="width: 350px;" tabindex="4">
										<option value="">Select</option> @foreach($agents as $agent)
										<option value="{{$agent->id}}">{{$agent->first_name.'
											'.$agent->last_name}}</option> @endforeach
									</select>
								</div>

							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Status:</label>
							<div class="col-lg-10">
								<select name="status" class="form-control">
									<option value="" selected></option>
									<option value="urgent">Urgent</option>
									<option value="normal">Normal</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Activities:</label>
							<div class="activity">
								@foreach($activities as $activity)
								<div class="col-lg-3">
									<input type="checkbox" class="i-checks activity"
										name="activity[]" value="{{$activity->activity}}"> <label
										id="{{$activity->id}}">{{$activity->activity}}</label>
								</div>
								@endforeach
							</div>
							<div class="col-lg-3">
								<a href="#" data-toggle="modal" data-target="#myModal"><i
									class="fa fa-plus"></i> Add new Activity...</a>
							</div>
						</div>



					</form>


				</div>

				<div class="modal-footer">
					<button type="button"
						class="ladda-button btn btn-w-m btn-primary saveSchedule"
						data-style="zoom-in">Save</button>
					<button type="button" class="btn btn-w-m btn-danger btn-outline"
						data-dismiss="modal">Cancel</button>
				</div>
			</div>

		</div>
	</div>

	...
	
	<div id="updateSchedule" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Schedule</h4>
				</div>

				<div class="modal-body">
					<form class="form-horizontal" id="updateSchedule">
						{!! csrf_field() !!}
						<input name="schedID" id="schedID" type="hidden">
						<div class="form-group">
							<div class="title">
								<label class="col-lg-2 control-label">Title :</label>

								<div class="col-lg-10">
									<input type="text" name="title" value="Scheduled Maintenance"
										id="editTitle" class="form-control"> <span
										class="help-block text-danger title">Example block-level help
										text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="area">
								<label class="col-lg-2 control-label">Area :</label>
								<div class="col-lg-10">
									<select name="area" id="editArea" class="col-lg-5 form-control">
										<option value="" selected></option> @foreach($areas as $area)
										<option value="{{$area->id}}">{{$area->area}}</option>
										@endforeach


									</select> <span class="help-block text-danger area">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="startScheduleDate">
								<label class="col-lg-2 control-label">From:</label>
								<div class="col-lg-5">
									<input type="text" name="startScheduleDate"
										placeholder="Start Date" id="updateStartScheduleDate"
										class="form-control datePicker"> <span
										class="help-block text-danger startScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="startScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="startScheduleTime"
										id="updateStartScheduleTime" class="clockPicker form-control"
										placeholder="Start Time"> <span
										class="help-block text-danger startScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="endScheduleDate">
								<label class="col-lg-2 control-label">To:</label>

								<div class="col-lg-5">
									<input type="text" name="endScheduleDate" id="updateEndScheduleDate"
										placeholder="End Date" class="form-control datePicker"> <span
										class="help-block text-danger endScheduleDate">Example
										block-level help text here.</span>
								</div>
							</div>
							<div class="endScheduleTime">
								<div class="col-lg-5">
									<input type="text" name="endScheduleTime" id="updateEndScheduleTime"
										class="clockPicker form-control" placeholder="End Time"> <span
										class="help-block text-danger endScheduleTime">Example
										block-level help text here.</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Agents:</label>
							<div class="col-lg-10">
								<div class="input-group">
									<select data-placeholder="Choose an agent" id="editAgentSelect"
										name="agents[]" class="chosen-select form-control" multiple
										style="width: 350px;" tabindex="4">
										<option value="">Select</option> @foreach($agents as $agent)
										<option value="{{$agent->id}}">{{$agent->first_name.'
											'.$agent->last_name}}</option> @endforeach
									</select>
								</div>

							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Status:</label>
							<div class="col-lg-10">
								<select name="status" class="form-control" id="editStatus">
									<option value="" selected></option>
									<option value="urgent">Urgent</option>
									<option value="normal">Normal</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Activities:</label>
							<div class="activity">
								@foreach($activities as $activity)
								<div class="col-lg-3">
									<input type="checkbox" class="i-checks activity"
										name="activity[]" id="activity{{$activity->id}}" value="{{$activity->activity}}"> 
										<label id="{{$activity->id}}">{{$activity->activity}}</label>
								</div>
								@endforeach
							</div>
							<div class="col-lg-3">
								<a href="#" data-toggle="modal" data-target="#myModal"><i
									class="fa fa-plus"></i> Add new Activity...</a>
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button"
						class="ladda-button btn btn-w-m btn-primary updateSchedule"
						data-style="zoom-in" id="updateSchedule">Save</button>
					<button type="button" class="btn btn-w-m btn-danger btn-outline"
						data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>



<script type="text/javascript">
$(document).ready(function() {
	
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
     
	$('span.text-danger').hide();
	
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
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
    events:<?php echo json_encode($schedules); ?>,
    
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
        						var sched = data[0];
        						var activities = sched['activities'].split(",");
        						console.log(activities);
        							$('input#schedID').val(sched['id']);
    								$('input#editTitle').val(sched['title']);
    								$('select#editArea').val(sched['area']);
    								$('input#updateStartScheduleDate').val(moment(sched['start_date']).format("YYYY-MM-DD"));
    								$('input#updateStartScheduleTime').val(moment(sched['start_date']).format("hh:mma"));
    								$('input#updateEndScheduleDate').val(moment(sched['end_date']).format("YYYY-MM-DD"));
    								$('input#updateEndScheduleTime').val(moment(sched['end_date']).format("hh:mma"));
									$('select#editAgentSelect').val(sched['agents'].split(','));
									$('select#editStatus').val(sched['status']);
    								$(activities).each(function(i,e){
										$('#activity'+e).prop( "checked", true );
										$('#activity'+e).parent().addClass('checked');
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
$('#agentSelect').change(function(){
	console.log($(this).val());
});
</script>
	@endsection