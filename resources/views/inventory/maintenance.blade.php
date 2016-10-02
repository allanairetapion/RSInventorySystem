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
	<div class="col-lg-12 sample">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<div class="pull-right">
					<button class="btn btn-primary btn-sm addSchedule">Add Schedule</button>
				</div>
				<h4>Maintenance Schedule</h4>

			</div>
			<div class="ibox-content">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
	<div id="addSchedule" class="col-lg-12 hidden">
		<div class="ibox float-e-margins">
			<div class="ibox-title">

				<h4>Add Schedule</h4>

			</div>
			<div class="ibox-content">
				<form class="form-horizontal" id="addSchedule">
					{!! csrf_field() !!}
					<div class="form-group">
						<div class="title">
							<label class="col-lg-2 control-label">Title :</label>

							<div class="col-lg-5">
								<input type="text" name="title" value="Scheduled Maintenance"
									class="form-control"> <span
									class="help-block text-danger title">Example block-level help
									text here.</span>
							</div>
						</div>
						<div class="area">
							<label class="col-lg-1 control-label">Area :</label>

							<div class="col-lg-4">
								<select name="area" class="col-lg-5 form-control">
									<option value="" selected></option> @foreach($areas as $area)
									{{$area->area}}
									</option> @endforeach


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
									id="startScheduleDate" class="form-control datePicker"> <span
									class="help-block text-danger startScheduleDate">Example
									block-level help text here.</span>
							</div>
						</div>
						<div class="startScheduleTime">
							<div class="col-lg-5">
								<input type="text" name="startScheduleTime"
									id="startScheduleTime" class="timePicker form-control"
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
									class="form-control datePicker"> <span
									class="help-block text-danger endScheduleDate">Example
									block-level help text here.</span>
							</div>
						</div>
						<div class="endScheduleTime">
							<div class="col-lg-5">
								<input type="text" name="endScheduleTime" id="endScheduleTime"
									class="timePicker form-control" placeholder="End Time"> <span
									class="help-block text-danger endScheduleTime">Example
									block-level help text here.</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Activities:</label>
						<div class="activity">
						@foreach($activities as $activity)
						<div class="col-lg-3">
							<input type="checkbox" class="i-checks activity"
								name="activity[]" value="{{$activity->id}}"> <label
								id="{{$activity->id}}">{{$activity->activity}}</label>
						</div>
						@endforeach
						</div>
						<div class="col-lg-3">
							<a href="#" data-toggle="modal" data-target="#myModal"><i
								class="fa fa-plus"></i> Add new Activity...</a>
						</div>
					</div>
					<hr>

					<div class="text-right">

						<button type="button"
							class="ladda-button btn btn-w-m btn-primary saveSchedule"
							data-style="zoom-in">Save</button>
						<button type="button"
							class="btn btn-w-m btn-danger btn-outline cancelSchedule ">Cancel</button>

					</div>

				</form>
			</div>
		</div>
	</div>
</div>



<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog ">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Borrow Report</h4>
			</div>

			<div class="ibox-content">
				<form id="addActivity" class="form-horizontal">
				{!! csrf_field() !!}
				<div class="row">
					<div class="form-group">
						<label class="control-label col-lg-2">Activity:</label>
						<div class="col-lg-10">
						<input type="text" name="activity" class="form-control">
						
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-lg-2">Description:</label>
						<div class="col-lg-10">
						<textarea rows="2" cols="" name="description" class="form-control"></textarea>
						
						</div>
					</div>
					</div>
				</form>
				
				

			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary" id="addActivity"
					type="button">
					<strong>Save</strong>
				</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('span.text-danger').hide();
	var date = new Date();
	var hour = (date.getHours() >= 12) ? parseInt(date.getHours()) - 12 : date.getHours();
	var ampm = (date.getHours() >= 12) ? "PM" : "AM";
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
	
	var y = 1000 * 60 * 30;
	var x = new Date(Math.round(new Date().getTime() / y) * y);

    $('input.timePicker').timepicki({
			start_time: [hour,date.getMinutes(),ampm]
        });

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
    dayClick: function(date, jsEvent, view) {

        alert('Clicked on: ' + date.format());

        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
		alert(jsEvent.title);
        alert('Current view: ' + view.name);


    },
    eventClick: function(calEvent, jsEvent, view) {

		swal(calEvent.title,
			moment(calEvent.start).format("hh:mm:ss a") + " - " + moment(calEvent.end).format("hh:mm:ss a") +"\n"+
			calEvent.description,
			'info');
        

    }
    
});


});

$('button.addSchedule').click(function(){
	$('div.sample').slideToggle();
	
	$('div#addSchedule').removeClass('hidden');
	
	});

$('button.cancelSchedule').click(function(){
	
	$('div#addSchedule').addClass('hidden');
	$('div.sample').slideToggle();
	
	
	
	})

$('button.saveSchedule').click(function(){
	var start_date = $('input#startScheduleDate').val();
	var start_time =  ($('input#startScheduleTime').val() == "") ? "": convert_time($('input#startScheduleTime').val());
	
	var end_date = $('input#endScheduleDate').val();
	var end_time = convert_time($('input#endScheduleTime').val());
	
	function convert_time(time_str){
		if(time_str != null)
			return false;
		
		    var time = time_str.match(/(\d+):(\d+) (\w)/);
		    var hours = Number(time[1]);
		    var minutes = Number(time[2]);
		    var meridian = time[3];
	
		    if (meridian == 'P' && hours < 12) {
		      hours = hours + 12;
		    }
		    else if (meridian == 'A' && hours == 12) {
		      hours = hours - 12;
		      hours = "0"+ hours.toString();
		      
		    }
		    console.log(hours)
		    return hours+":"+minutes+":00";
		};
		
		$
		.ajax(
				{
					type : "POST",
					url : "/inventory/addSchedule",
					data : $('form#addSchedule')
							.serialize(),
					success: function(){
						var schedDescription = "Activities: " + "\n";
						$('input:checkbox.activity:checked').each(function () {
							schedDescription = schedDescription + $('label#'+$(this).val()).text() + ", ";
						  });
						var myEvent = {
								  title:"Maintenance",
								  start: start_date + "T" + start_time + ".196Z",
								  end: end_date + "T"+ end_time + ".196Z",
								  description : schedDescription
								};
							$('div.sample').slideToggle();
								
								$('div#addSchedule').addClass('hidden');
								$('#calendar').fullCalendar( 'renderEvent', myEvent,true );
								
							
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
						$('div#myModal').modal('toggle');
					}
			});
});

</script>
@endsection
