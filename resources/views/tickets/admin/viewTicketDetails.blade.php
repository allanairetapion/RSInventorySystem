@extends('tickets.ticketadminlayout')
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
					<a class="btn btn-block btn-primary compose-mail" href="/admin/ticketReply">Compose Mail</a>
					<div class="space-25"></div>
					<h5>Folders</h5>
					<ul class="folder-list m-b-md" style="padding: 0">
						<li>
							<a href="/admin/tickets"><i class="fa fa-inbox "></i>All Tickets </a>
						</li>
						<li>
							<a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open Tickets </a>
						</li>
						<li>
							<a href="/admin/tickets-Pending"><i class="fa fa-warning"></i>Pending Tickets </a>
						</li>
						<li>
							<a href="/admin/tickets-Closed"><i class="fa fa-thumbs-o-up"></i>Closed Tickets </a>
						</li>
					</ul>
					<h5>Priority Level</h5>
					<ul class="category-list" style="padding: 0">

						<li>
							<a href="#"> <i class="fa fa-circle text-danger"></i> High</a>
						</li>
						<li>
							<a href=""> <i class="fa fa-circle text-warning"></i> Normal</a>
						</li>
						<li>
							<a href="#"> <i class="fa fa-circle text-navy"></i> Low </a>
						</li>
					</ul>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 animated fadeInRight mailView">
		@if(Carbon\Carbon::today()->subDays(2)->endOfDay()  >=  Session::get('date_sent') && Session::get('status') != "Closed")
		<div class="alert alert-danger">
			<i class="fa fa-warning fa-2x"></i><strong> This ticket has been unresolved for more than two days</strong>
		</div>
		@endif
		
		<div class="mail-box-header">
			<form class="form-inline ticketStatus">
				{!! csrf_field() !!}
				<input type="hidden" name="id" value="{{Session::get('id')}}">
				<input type="hidden" name="assignedTo" class="assignedTo" value="">

				<div class="row">
					<div class="pull-right">
						<div class="form-group">
							<label class="">Topic: </label>
							@if(Session::get('status') == "Closed" && Auth::guard('admin')->user()->user_type == "agent")
							<select name="topic"class="form-control topic" readonly>

							@else
							<select name="topic"class="form-control topic">

							@endif
								<option selected  value="{{Session::get('topic_id')}}">{{Session::get('topic')}}</option>
								@foreach ($topics as $topic)}
								@if(Session::get('topic_id') != $topic->topic_id)
								<option value="{{$topic->topic_id}}">{{$topic->description}}</option>
								@endif
								@endforeach
							</select>

						</div>
						<div class="form-group">
							<label class="">Priority: </label>
							@if(Session::get('status') == "Closed" && Auth::guard('admin')->user()->user_type == "agent")
							<select name="priority" class=" form-control" readonly>
							@else
							<select name="priority" class=" form-control">
							@endif
							
								@if(Session::get('priority') == 'High')
								<option value="High">High</option>
								<option value="Normal">Normal</option>
								<option value="Low">Low</option>
								@elseif(Session::get('priority') == 'Normal')
								<option value="Normal">Normal</option>
								<option value="Low">Low</option>
								<option value="High">High</option>
								@else
								<option value="Low">Low</option>
								<option value="Normal">Normal</option>
								<option value="High">High</option>
								@endif
							</select>

						</div>

						<div class="form-group">
							<label class="">Status: </label>
							@if((Session::get('status') == "Closed" || $restrictions[0]->agent == "0")&& Auth::guard('admin')->user()->user_type == "agent")
							<select readonly name="ticket_status" class="form-control ticketStatus">
							@else
							<select name="ticket_status" class="form-control ticketStatus">
							@endif
								@if(Session::get('status') == "Open")
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								@elseif(Session::get('status') == "Pending")
								<option value="Pending">Pending</option>
								<option value="Open">Open</option>
								<option value="Closed">Closed</option>
								@else
								<option value="Closed">Closed</option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								@endif
							</select>

						</div>
					</div>
					<h2> &nbsp; View Message </h2>
				</div>
			</form>

			<div class="mail-tools tooltip-demo m-t-md">

				<h5><span class="pull-right"><span class="font-noraml">Ticket id:</span> {{Session::get('id')}}</span></h5><h3><span class="font-noraml">Subject: </span>{{Session::get('subject')}}</h3>
				<h5><span class="pull-right"><span class="font-noraml">Date sent:</span> {{Session::get('date_sent')}}</span><span class="font-noraml">From: </span>{{Session::get('email')}} </h5>
				
				
				
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
				Summary:
				<div class="ibox-content gray-bg">
				 <p>
					{!!html_entity_decode(Session::get('summary'))!!}
				 </p>
				</div>
				
				@if(Session::get('status') == "Closed")
				<br>
				Closing Report:
				<div class="ibox-content gray-bg">
				 <p>
					{!!html_entity_decode(Session::get('closing_report'))!!}
				 </p>
				 </div>
				 @endif
				
			</div>

			<div class="mail-body text-right tooltip-demo">
				@if(Session::get('status') != "Closed" || Auth::guard('admin')->user()->user_type == 'admin')
				<button type="button" class="ladda-button btn ticketSave btn-sm btn-white data-style="zoom-in" ">
					<i class="fa fa-save "></i> Save
				</button>
				@endif
				<a class="btn btn-sm btn-white" href="/admin/ticketReply/{{Session::get('id')}}"><i class="fa fa-reply"></i> Reply</a>
				@if(Auth::guard('admin')->user()->user_type == 'agent')
					@if($restrictions[4]->agent == 1)
						<a class="btn btn-sm btn-white" data-toggle="modal" data-target="#forward"><i class="fa fa-mail-forward"></i> Forward</a>
					@endif
				@endif
				<button class="btn btn-sm btn-white printMe">
					<i class="fa fa-print"></i> Print
				</button>
				@if(Auth::guard('admin')->user()->user_type == 'admin')
				<button title="" data-placement="top" data-toggle="tooltip" data-original-title="Delete Ticket" class="btn btn-sm btn-white deleteViewedTicket">
					<i class="fa fa-trash-o"></i> Delete
				</button>
				@endif
			</div>
			<div class="clearfix"></div>

		</div>
	</div>
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
					This ticket is assigned to: <span class="font-bold">{{Session::get('assigned_support')}}</span>. Choose an agent below if you want to change the assigned support.
				</p>
				<form class="forwardTo">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{Session::get('id')}}">
					<select name="agent" class="form-control assignAgent">
						<option value="" selected hidden>Select agent...</option>
						@foreach ($agent as $agents)
						@if($agents->first_name.' '.$agents->last_name != Session::get('assigned_support'))
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
				<i class="fa fa-thumbs-o-up modal-icon"></i>
				<h4 class="modal-title">Closed ticket:</h4>

			</div>
			<form class="closeTicket">
				{!! csrf_field() !!}
				<input type="hidden" id="closing_report"name="closing_report" value="{{Session::get('closing_report')}}">
				<input type="hidden" name="id" value="{{Session::get('id')}}">
				
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