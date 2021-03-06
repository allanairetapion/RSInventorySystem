@extends('layouts.ticket_summernote') @section('body')


<div class="row">
	<div class="col-md-3">
		<div class="ibox float-e-margins">
			<div class="ibox-content mailbox-content">
				<div class="file-manager">
					<a class="btn btn-block btn-primary compose-mail"
						href="/admin/createTicket">Create Ticket</a>
					<div class="space-25"></div>
					<h5>Folders</h5>
					<ul class="folder-list m-b-md" style="padding: 0">
						<li><a href="/admin/tickets"><i class="fa fa-inbox "></i>All
								Tickets </a></li>
						<li><a href="/admin/tickets-Assigned"><i class="fa fa-tasks "></i>Assigned
								Tickets <span
								class="pull-right label label-info assignedTickets">1</span></a>
						</li>

						<li><a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open
								Tickets <span class="pull-right label label-info openTickets">1</span></a>
						</li>
						<li><a href="/admin/tickets-Pending"><i class="fa fa-clock-o"></i>Pending
								Tickets <span class="pull-right label label-info pendingTickets">1</span></a>
						</li>
						<li><a href="/admin/tickets-Unresolved"><i class="fa fa-warning"></i>Unresolved
								Tickets <span
								class="pull-right label label-info unresolvedTickets">1</span></a>
						</li>
						<li><a href="/admin/tickets-Closed"><i class="fa fa-thumbs-o-up"></i>Closed
								Tickets <span class="pull-right label label-info closedTickets">0</span> </a></li>
					</ul>
					<h5>Categories</h5>
					<ul class="category-list" style="padding: 0">
						<li><a href="#"> <i class="fa fa-circle text-primary"></i> Open
						</a></li>
						<li><a href=""> <i class="fa fa-circle text-warning"></i> Pending
						</a></li>
						<li><a href="#"> <i class="fa fa-circle text-navy"></i> Closed
						</a></li>
						<li><a href="#"> <i class="fa fa-circle text-danger"></i>
								Unresolved
						</a></li>
					</ul>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 animated fadeInRight mailView">
		<div class="mail-box-header">
			
			<h2 class="font-bold">Reply</h2>
		</div>
		<div class="mail-box">
			<form class="form-horizontal ticketReply">
				{!! csrf_field() !!} 
				<input type="hidden" name="email" class="form-control email" value="{{Session::get('email')}}" readonly> 
				<input type="hidden" name="ticket_id" value="{{ Session::get('ticket_id') }}" class="ticket_id">
				<input type="hidden" name="message" value="" class="ticketReply">
			</form>
			<div class="mail-text ">
				<div class="ticketReplySummernote "></div>
				<div class="clearfix"></div>
			</div>
			<div class="mail-body text-right tooltip-demo">
				<button class="ladda-button btn btn-sm btn-primary ticketReply"
					data-toggle="tooltip" data-placement="top" title="Send"
					data-style="zoom-in">
					<i class="fa fa-send"></i> Send
				</button>
				<a href="/admin/tickets" class="btn btn-white btn-sm"
					data-toggle="tooltip" data-placement="top" title="Discard email"><i
					class="fa fa-times"></i> Discard</a>

			</div>
			<div class="clearfix"></div>

		</div>
	</div>

</div>
@endsection
@section('scripts')
<script type="text/javascript" src="/js/ticketing/ticketReply.js">
</script>
@endsection