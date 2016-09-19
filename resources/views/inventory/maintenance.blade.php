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
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-lg-2 control-label">Title :</label>

						<div class="col-lg-10">
							<input type="text" name="titelSchedule" value="Scheduled Maintenance" class="form-control"> 
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">From:</label>

						<div class="col-lg-5">
							<input type="text" name="startScheduleDate" id="startScheduleDate" class="form-control datePicker"> <span
								class="help-block m-b-none">Example block-level help text here.</span>
						</div>
						<div class="col-lg-5">
							<input type="text" name="startScheduleTime" id="startScheduleTime" class="timePicker form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">To:</label>

						<div class="col-lg-5">
							<input type="text" name="endScheduleDate" id="endScheduleDate" class="form-control datePicker"> <span
								class="help-block m-b-none">Example block-level help text here.</span>
						</div>
						<div class="col-lg-5">
							<input type="text" name="endScheduleTime" id="endScheduleTime" class="timePicker form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Tasks:</label>

						<div class="col-lg-10">
							<textarea class="form-control" rows="3">

							</textarea>
							<span class="help-block m-b-none">Example block-level help text
								here.</span>
						</div>
					</div>
					<hr>
					<div class="text-right">

						<button type="button"
							class="ladda-button btn btn-w-m btn-primary saveSchedule"
							data-style="zoom-in" >Save</button>
						<button type="button" class="btn btn-w-m btn-danger btn-outline cancelSchedule ">Cancel</button>

					</div>

				</form>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
$(document).ready(function() {

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
	
	var y = 1000 * 60 * 30;
	var x = new Date(Math.round(new Date().getTime() / y) * y);

    $('input.timePicker').timepicker({
    	timeFormat: 'H:mm:ss',
    	defaultTime: x.toTimeString().split(" ")[0],
    	dropdown: true,
        scrollbar: true,
        interval: 30,
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
    dayClick: function(date, jsEvent, view) {

        alert('Clicked on: ' + date.format());

        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

        alert('Current view: ' + view.name);


    },
    
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
	
	
	var myEvent = {
	  title:"Maintenance",
	  start: $('input#startScheduleDate').val() + "T"+$('input#startScheduleTime').val()+".196Z",
	  end: $('input#endScheduleDate').val()+ "T"+$('input#endScheduleTime').val()+".196Z"
	};
$('div.sample').slideToggle();
	console.log(new Date());
	console.log($('input#startScheduleDate').val() + "T"+$('input#startScheduleTime').val()+".196Z");
	$('div#addSchedule').addClass('hidden');
	$('#calendar').fullCalendar( 'renderEvent', myEvent,true );
	
});

</script>
@endsection
