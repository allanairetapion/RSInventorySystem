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
				<div  class="panel panel-info ">
					<div class="panel-heading"> 
						By: {{Session::get('sender')}} <span class="pull-right"> {{Session::get('date_sent')}}</span>
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
				 <div id="Reply" class="panel panel-default">
				 <div class="panel-heading">
                                            Reply
                                        </div>
                                        <div class="panel-body">
                                        <form class="form-horizontal ticketReply">
				{!! csrf_field() !!} 
				<input type="hidden" name="email" class="form-control email" value="{{Session::get('email')}}" readonly> 
				<input type="hidden" name="ticket_id" value="{{ Session::get('ticket_id') }}" class="ticket_id">
				<input type="hidden" name="message" value="" class="ticketReply">
			</form>
                                           <div class="summernote"></div>
                                        </div>
                                        <div class="panel-footer">
                                        <button type="button" class="ladda-button btn btn-primary ticketReply" data-toggle="tooltip" data-placement="top" title="Send"
					data-style="zoom-in">Send</button>
                                        <button type="button" class="btn btn-danger btn-outline cancelReply"> Cancel</button></div>
				 
				 </div>
				
			</div>

			
				
				@if(Session::get('status') != "Closed")
					<div class="mail-body text-right tooltip-demo">
				<button class="btn btn-sm btn-white clientReply"><i class="fa fa-reply"></i> Reply</button>
				</div>
				@endif
								
				
			
			<div class="clearfix"></div>

		</div>
	</div>
</div>





<script type="text/javascript">

$(document).ready(function(){
	$('div#Reply').hide();
});

$('button.clientReply').click(function(){
	$(this).hide();
	$('div#Reply').slideToggle();
});
$('button.cancelReply').click(function(){
	$('div#Reply').slideToggle();
	$('button.clientReply').show();
});
</script>

@endsection