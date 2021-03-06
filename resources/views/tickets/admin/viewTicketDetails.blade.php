@extends('layouts.ticket_summernote')
@section('style')
<style type="text/css">
	@media print {
		body * {
			visibility: hidden;
		}
		.mailView * {
			visibility: visible;
		}
		
		.mailView {
			position: absolute;
			top: 40px;
			left: 30px;
		}
	}
</style>
@endsection
@section('body')
<div class="row">
	<div class="col-lg-3">
		<div class="ibox float-e-margins">
			<div class="ibox-content mailbox-content">
				<div class="file-manager">
					<a class="btn btn-block btn-primary compose-mail" href="/admin/createTicket">Create Ticket</a>
                            <div class="space-25"></div>
					<h5>Folders</h5>
					<ul class="folder-list m-b-md" style="padding: 0">
						<li>
							<a href="/admin/tickets"><i class="fa fa-inbox "></i>All Tickets </a>
						</li>
						<li>
							<a href="/admin/tickets-Assigned"><i class="fa fa-tasks "></i>Assigned Tickets <span class="pull-right label label-info assignedTickets">0</span></a>
						</li>

						<li>
							<a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open Tickets <span class="pull-right label label-info openTickets">0</span></a>
						</li>
						<li>
							<a href="/admin/tickets-Pending"><i class="fa fa-clock-o"></i>Pending Tickets <span class="pull-right label label-info pendingTickets">0</span></a>
						</li>
						<li>
							<a href="/admin/tickets-Unresolved"><i class="fa fa-warning"></i>Unresolved Tickets <span class="pull-right label label-info unresolvedTickets">0</span></a>
						</li>
						<li>
							<a href="/admin/tickets-Closed"><i class="fa fa-thumbs-o-up"></i>ClosedTickets <span class="pull-right label label-info closedTickets">0</span></a>
						</li>
					</ul>
					<h5>Categories</h5>
					<ul class="category-list" style="padding: 0">					
						<li><a href="#"> <i class="fa fa-circle text-primary"></i> Open
						</a></li>
						<li><a href=""> <i class="fa fa-circle text-warning"></i> Pending
						</a></li>
						<li><a href="#"> <i class="fa fa-circle text-navy"></i> Closed
						</a></li>
						<li><a href="#"> <i class="fa fa-circle text-danger"></i> Unresolved
						</a></li>
					</ul>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 animated fadeInRight mailView">
		@if(Carbon\Carbon::today()->subDays(2)->endOfDay()  <=  $ticket->updated_at && $ticket->ticket_status != "Closed")
		<div class="alert alert-danger">
			<i class="fa fa-warning fa-2x"></i><strong> &emsp;This ticket has been unresolved for more than two days</strong>
		</div>
		@endif
		
		<div class="mail-box-header">
			<form class="form-inline ticketStatus">
				{!! csrf_field() !!}
				<input type="hidden" name="id" value="{{$ticket->id}}">
				<input type="hidden" name="assignedTo" class="assignedTo" value="">
				
				<div class="row">
					<div class="pull-right">																	
						<div class="form-group">
							<label class="">Status: </label>
							@if(($ticket->ticket_status == "Closed" || $restrictions[0]->agent == "0") && Auth::guard('admin')->user()->user_type == "agent")
							<select readonly name="ticket_status" class="form-control ticketStatus">
								<option selected value="{{$ticket->ticket_status}}">{{$ticket->ticket_status}}</option>
							@else
							<select name="ticket_status" class="form-control ticketStatus">
								@if($ticket->ticket_status == "Open")
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								@elseif($ticket->ticket_status == "Pending")	
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
					<h2> &nbsp; Ticket ID: {{$ticket->id}} - {{$ticket->ticket_status}}</span></h2>
				</div>
			</form>

			<div class="mail-tools tooltip-demo m-t-md">

				<h5><span class="pull-right"><span class="font-noraml">Topic:</span> {{$ticket->description}}</span></h5><h3><span class="font-noraml">Subject: </span>{{$ticket->subject}}</h3>
				<h5><span class="pull-right"><span class="font-noraml">Priority:</span> {{$ticket->priority_level}}</span><span class="font-noraml">Department: </span>{{$ticket->department}} </h5>
				
				
				
				@if($ticket->ticket_status != "Open")
				<h5>
					@if($ticket->ticket_status == "Closed")
					<span class="pull-right"><span class="font-noraml">Closed by: </span>{{$ticket->closedFN.' '.$ticket->closedLN}} </span>
					@endif
					<span class="font-noraml">Assigned to: </span>{{$ticket->assignFN.' '.$ticket->assignLN}} </h5>
				@endif
			</div>

		</div>

		<div class="mail-box">			
			<div class="mail-body">
			<h4>Description:</h4>
				<div  class="panel panel-info ">
					<div class="panel-heading"> 
						By: {{$ticket->first_name.' '.$ticket->last_name}} <span class="pull-right"> {{$ticket->created_at}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode($ticket->summary)!!}
				 </p>
				 <?php $ticket->attachments = explode(",",$ticket->attachments)?>
					 <div class="lightBoxGallery">
					 @foreach($ticket->attachments as $attachment)
					 @if($attachment != "")
					 <a href="{{$attachment}}" data-gallery="" > 
					<img src="{{$attachment}}" height="100" width="100" class="img-thumbnail"/>
				</a>
				@endif
					 @endforeach
				
				 
				 
				 </div>
				 	
					</div>
					
				</div>
				@if($ticket->ticket_status != "Open" && $messages != null)
					
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
				
				@if($ticket->ticket_status == "Closed")
				<h4>Closing Report:</h4>
				<div  class="panel panel-success ">
					<div class="panel-heading"> 
						By: {{$ticket->closedFN.' '.$ticket->closedLN}}  <span class="pull-right"> {{$ticket->updated_at}}</span>
					</div>
					<div class="panel-body">
						 <p>
					{!!html_entity_decode($ticket->closing_report)!!}
				 </p>
				
					</div>
					
				</div>
				
				
				 @endif
				
			</div>

			<div class="mail-body text-right tooltip-demo">
				@if($ticket->ticket_status != "Closed" || Auth::guard('admin')->user()->user_type == 'admin')
				<button type="button" class="ladda-button btn ticketSave btn-sm btn-white data-style="zoom-in" ">
					<i class="fa fa-save "></i> Save
				</button>
				@endif
				@if($ticket->ticket_status != "Closed")
					@if($restrictions[1]->agent == 1 || Auth::guard('admin')->user()->user_type == 'admin')
				<button class="btn btn-sm btn-white" onclick="window.document.location='/admin/ticketReply/{{$ticket->id}}'"><i class="fa fa-reply"></i> Reply</button>
					@endif
				@endif
				
				@if($ticket->ticket_status != "Closed")
					@if(Auth::guard('admin')->user()->user_type == 'admin' || $restrictions[2]->agent == 1)
						<a class="btn btn-sm btn-white" data-toggle="modal" data-target="#forward"><i class="fa fa-mail-forward"></i> Forward</a>
					@endif
				@endif				
				
				<button class="btn btn-sm btn-white" onclick="window.open('/admin/printTickets/{{$ticket->id}}')">
					<i class="fa fa-print"></i> Print
				</button>
				@if(Auth::guard('admin')->user()->user_type == 'admin')
				<button title="" data-placement="top" data-toggle="tooltip" data-original-title="Delete Ticket" class="btn btn-sm btn-white ticketDelete">
					<i class="fa fa-trash-o"></i> Delete
				</button>
				@endif
			</div>
			<div class="clearfix"></div>

		</div>
	</div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery">
                                <div class="slides"></div>
                                <h3 class="title"></h3>
                                <a class="prev">�</a>
                                <a class="next">�</a>
                                <a class="close">�</a>
                                <a class="play-pause"></a>
                                <ol class="indicator"></ol>
                            </div>
<div class="modal inmodal" id="forward" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<i class="fa fa-mail-forward modal-icon"></i>
				<h4 class="modal-title">Forward to:</h4>

			</div>
			<div class="modal-body">
				<p>
					This ticket is assigned to: <span class="font-bold">{{$ticket->assignFN.' '.$ticket->assignLN}}</span>. Choose an agent below if you want to change the assigned support.
				</p>
				<form class="forwardTo">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$ticket->id}}">
					<select name="agent" class="form-control assignAgent">
						<option value="" selected hidden>Select agent...</option>
						@foreach ($agent as $agents)
							@if($agents->first_name.' '.$agents->last_name != $ticket->assignFN.' '.$ticket->assignLN)
							<option value="{{$agents->id}}"> {{$agents->
								first_name.' '.$agents->last_name}}</option>
							@endif
						@endforeach
					</select>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
				<button type="button" class="btn btn-primary saveForwardTo">
					Save
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal" id="assign" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<i class="fa fa-mail-forward modal-icon"></i>
				<h4 class="modal-title">Assign to:</h4>

			</div>
			<form class="assignTo">
				<div class="modal-body">
					<p>
						This ticket require's a support to change it's status to "Open".
					</p>

					<div class="form-group assignAgent">
						<select name="assignAgent" id="assignTo" class="form-control assignAgent">
							<option selected value="">Select agent...</option>
							@foreach ($agent as $agents)

							<option value="{{$agents->id}}"> {{$agents->
								first_name.' '.$agents->last_name}}</option>

							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white cancelOpen" data-dismiss="modal">
						Cancel
					</button>
					<button type="button" class="btn btn-primary saveOpen" >
						Done
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal inmodal" id="closedBy" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-lg modal-dialog">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Closed ticket:</h4>

			</div>
			<form class="closeTicket">
				{!! csrf_field() !!}
				<input type="hidden" id="closing_report"name="closing_report" value="{{$ticket->closing_report}}">
				<input type="hidden" name="id" value="{{$ticket->id}}">
				
				<div class="modal-body">
					<h4>This ticket require's a closing report to change it's status to "Closed".</h4>
					<div class="ibox-content">
						<div class="ticketsummernote"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white " data-dismiss="modal">
						Cancel
					</button>
					<button type="button" class="btn btn-primary closeTicket" >
						Close Ticket
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
 @section('scripts')
	<script type="text/javascript"
		src="/js/ticketing/ticketViewTicketDetails.js">
</script>
	@endsection