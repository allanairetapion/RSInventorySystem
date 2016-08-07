<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Print Ticket</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>

	</head>
	<body class="white-bg">
		<div class="row">
			<div class="mail-box-header">
			<form class="form-inline ticketStatus">
				{!! csrf_field() !!}
				<input type="hidden" name="id" value="{{Session::get('id')}}">
				<input type="hidden" name="assignedTo" class="assignedTo" value="">
				
				<div class="row">
					<div class="pull-right">																	
						<div class="form-group">
							<label class="">Status: </label>
							@if((Session::get('status') == "Closed" || $restrictions[0]->agent == "0")&& Auth::guard('admin')->user()->user_type == "agent")
							<select readonly name="ticket_status" class="form-control ticketStatus">
								<option selected value="{{Session::get('status')}}">{{Session::get('status')}}</option>
							@else
							<select name="ticket_status" class="form-control ticketStatus">
								@if(Session::get('status') == "Open")
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								@elseif(Session::get('status') == "Pending")	
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								@else
								<option value="Unresolved">Unresolved</option>
								<option value="Closed">Closed</option>
								<option value="Pending">Pending</option>
								@endif
							@endif
								
							</select>

						</div>
					</div>
					<h2> &nbsp; Ticket ID: {{Session::get('id')}} - {{Session::get('status')}}</span></h2>
				</div>
			</form>

			<div class="mail-tools tooltip-demo m-t-md">

				<h5><span class="pull-right"><span class="font-noraml">Topic:</span> {{Session::get('topic')}}</span></h5><h3><span class="font-noraml">Subject: </span>{{Session::get('subject')}}</h3>
				<h5><span class="pull-right"><span class="font-noraml">Priority:</span> {{Session::get('priority')}}</span><span class="font-noraml">Department: </span>{{Session::get('department')}} </h5>
				
				
				
				@if(Session::get('status') != "Open")
				<h5>
					@if(Session::get('status') == "Closed")
					<span class="pull-right"><span class="font-noraml">Closed by: </span>{{Session::get('closed_by')}} </span>
					@endif
					<span class="font-noraml">Assigned to: </span>{{Session::get('assigned_support')}} </h5>
				@endif
			</div>

		</div>
		
		
		<div class="mail-box">			
			<div class="mail-body">
			<h4>Description:</h4>
				<div  class="panel panel-info ">
					<div class="panel-heading"> 
						By: {{Session::get('email')}} <span class="pull-right"> {{Session::get('date_sent')}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode(Session::get('summary'))!!}
				 </p>
					</div>
					
				</div>
				@if(Session::get('status') != "Open" && $messages != null)
					
					@foreach($messages as $message)
					
				<div  class="panel panel-default ">
					<div class="panel-heading"> 
						By: {{$message->sender}}  <span class="pull-right"> {{$message->created_at}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode($message->message)!!}
				 </p>
					</div>
					
				</div>
					@endforeach
					
				@endif
				
				@if(Session::get('status') == "Closed")
				<h4>Closing Report:</h4>
				<div  class="panel panel-success ">
					<div class="panel-heading"> 
						By: {{Session::get('closed_by')}}  <span class="pull-right"> {{Session::get('date_modified')}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode(Session::get('closing_report'))!!}
				 </p>
					</div>
					
				</div>
				
				
				 @endif
				
			</div>

		
			<div class="clearfix"></div>

		</div>
			
		</div>	
			
			<script>
				$(document).ready(function(){
					window.print();
				});
			</script>
	</body>
</html>