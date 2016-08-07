@extends('tickets.ticketclientlayout')

@section('body')
<div class="row">
	
	<div class="col-lg-12 animated fadeInRight mailView">	
		<div class="mail-box-header">
				<div class="row">		
					<h2> &nbsp; Ticket ID: {{Session::get('id')}} - {{Session::get('status')}}</span></h2>
				</div>
			<div class="mail-tools tooltip-demo m-t-md">
				<h5><span class="pull-right"><span class="font-noraml">Topic:</span> {{Session::get('topic')}}</span></h5><h3><span class="font-noraml">Subject: </span>{{Session::get('subject')}}</h3>
				<h5><span class="pull-right"><span class="font-noraml">Priority:</span> {{Session::get('priority')}}</span><span class="font-noraml">Department: </span>{{Session::get('department')}} </h5>				
				@if(Session::get('status') != "Pending")
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
				<div  class="panel panel-default ">
					<div class="panel-heading"> 
						By: {{Auth::guard('user')->user()->email}} <span class="pull-right"> {{Session::get('date_sent')}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode(Session::get('summary'))!!}
				 </p>
					</div>
					
				</div>
				@if(Session::get('status') != "Open" && $messages != null)
				@if(!empty($messages))
				<h4>Replies:</h4>
				@endif
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
				<div  class="panel panel-default ">
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
</div>







@endsection