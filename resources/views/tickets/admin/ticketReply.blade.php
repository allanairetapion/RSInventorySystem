@extends('tickets.ticketadminlayout')

@section('body')


	<div class="row">
	<div class="col-md-3">
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
							<a href="/admin/tickets-Assigned"><i class="fa fa-tasks "></i>My Tickets <span class="pull-right label label-info assignedTickets">1</span></a>
						</li>

						<li>
							<a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open Tickets <span class="pull-right label label-info openTickets">1</span></a>
						</li>
						<li>
							<a href="/admin/tickets-Pending"><i class="fa fa-clock-o"></i>Pending Tickets <span class="pull-right label label-info pendingTickets">1</span></a>
						</li>
						<li>
							<a href="/admin/tickets-Unresolved"><i class="fa fa-warning"></i>Unresolved Tickets <span class="pull-right label label-info unresolvedTickets">1</span></a>
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

	<div class="col-md-9 animated fadeInRight mailView">
		<div class="mail-box-header">
			<div class="pull-right tooltip-demo">
				
				<a href="/admin/tickets" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
			</div>
			<h2> Compose mail </h2>
		</div>
		<div class="mail-box">

			<div class="mail-body">

				<form class="form-horizontal ticketReply">
					{!! csrf_field() !!}
					<div class="form-group email">
						<label class="col-sm-2 control-label">To:</label>

						<div class="col-sm-10">
							<input type="text" name="email" class="form-control email" value="{{Session::get('email')}}">
							<label class="text-danger email">Label</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Subject:</label>

						<div class="col-sm-10">
							<input type="text" name="subject" class="form-control" value="">
						</div>
					</div>
					
					<input type="hidden" name="message" value="" class="ticketReply">
				</form>

			</div>

			<div class="mail-text h-200">
				
				<div class="ticketReplySummernote ">
					
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="mail-body text-right tooltip-demo">
				<button class="ladda-button btn btn-sm btn-primary ticketReply" data-toggle="tooltip" data-placement="top" title="Send" data-style="zoom-in"><i class="fa fa-send"></i> Send</button>
				<a href="/admin/tickets" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
				
			</div>
			<div class="clearfix"></div>

		</div>
	</div>

</div>

<script>
	$(document).ready(function(){
		$('label.email').hide()
		
		$.ajax({
				type : 'get',
				url : '/admin/ticketCount',
			}).done(function(data) {
				console.log(data);
				$('span.openTickets').text(data.openTickets);
				$('span.pendingTickets').text(data.pendingTickets);
				$('span.unresolvedTickets').text(data.overdueTickets);
				$('span.assignedTickets').text(data.assignedTickets);
			});
	});		
</script>

@endsection