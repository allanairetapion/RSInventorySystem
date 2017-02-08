@extends('layouts.ticket_basic') @section('body')
<div class="row">
	<div class="col-lg-3">
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
								class="pull-right label label-info assignedTickets">0</span></a>
						</li>
						<li><a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open
								Tickets <span class="pull-right label label-info openTickets">0</span></a>
						</li>
						<li><a href="/admin/tickets-Pending"><i class="fa fa-clock-o"></i>Pending
								Tickets <span class="pull-right label label-info pendingTickets">0</span></a>
						</li>
						<li><a href="/admin/tickets-Unresolved"><i class="fa fa-warning"></i>Unresolved
								Tickets <span
								class="pull-right label label-info unresolvedTickets">0</span></a>
						</li>
						<li><a href="/admin/tickets-Closed"><i class="fa fa-thumbs-o-up"></i>Closed
								Tickets <span class="pull-right label label-info closedTickets">0</span></a>
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
						<li><a href="#"> <i class="fa fa-circle text-danger"></i>
								Unresolved
						</a></li>
					</ul>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 animated fadeInRight mail">
		<div class="mail-box-header">
			<form class="pull-right mail-search">
				<div class="input-group m-b">
							<input type="text" class="form-control" id="ticketSearch"
								name="search" placeholder="Search...">
							<div class="input-group-btn">
								<button id="ticketSearch" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" data-toggle="collapse"
									data-target="#advancedSearch" type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>
			</form>

			<h2>Inbox</h2>
			<div id="advancedSearch" class="panel panel-default collapse">
				<div class="panel-body">
					<form class="advancedTicket">

					<div class="row">

						<div class="col-md-3">
							<label class="control-label">Topic:</label> <select
								name="topicSearch" class="form-control topic">
								<option value="" selected hidden>Choose a topic...</option>
								@foreach ($topics as $topic)
								<option value="{{$topic->description}}">{{$topic->description}}</option>
								@endforeach
							</select>

						</div>
						<div class="col-md-3">
							<label class="control-label">Status:</label> <select
								name="statusSearch" class="form-control statusSearch">
								<option value="" selected hidden>Search by status...</option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								<option value="Unresolved">Unresolved</option>
							</select>
						</div>

					</div>
					<div class="row">
						<div class="col-md-3">
							<label class="control-label">Sort By:</label> <select
								name="dateSort" class="form-control topic">
								<option value="1">Date Sent</option>
								<option value="2">Date Updated</option>
							</select>

						</div>
						<div class="col-md-6">
							<label class="control-label">Date Range:</label>
							<div class="input-daterange input-group" id="datepicker">
								<span class="input-group-addon">From</span> <input type="text"
									class=" form-control" data-mask="9999-99-99" name="dateStart"
									value="" /> <span class="input-group-addon">to</span> <input
									type="text" class=" form-control" data-mask="9999-99-99"
									name="dateEnd" value="" />
							</div>
						</div>



						<div class="col-md-3 text-center">
							<br>

							<button type="button"
								class="ladda-button btn btn-primary allTicketSearch"
								data-style="zoom-in">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="reset" class="btn btn-warning">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
					</div>

				</form>
					
				</div>
			</div>

			<div class="mail-tools tooltip-demo m-t-md">
			
			
				<button class="btn btn-white btn-sm refreshBtn"
					data-toggle="tooltip" data-placement="left" title="Refresh inbox">
					<i class="fa fa-refresh"></i> Refresh
				</button>
				@if(Auth::guard('admin')->user()->user_type == 'admin')
				<button class="btn btn-white btn-sm ticketDelete"
					data-toggle="tooltip" data-placement="top" title="Delete">
					<i class="fa fa-trash-o"></i>
				</button>
				@endif

			</div>
		</div>
		<div class="mail-box">

			<form class="selectedTickets">
				{!! csrf_field() !!}
				<table class="table table-hover table-condensed issue-tracker showTickets"
					data-filter="#filter" data-striping="false">
					<thead>
						<tr>
							
							<th></th>
							<th>Status</th>
							<th>Topic & Subject</th>
							<th>Sender</th>
							<th>Priority</th>
							<th>Date</th>

						</tr>
					</thead>
					<tbody class="allTickets">



						@foreach ($tickets as $ticket)

						<tr>

							<td>
								<div class="input-group">
									<input type="checkbox" class="i-checks" name="id"
										value="{{$ticket->id}}"> <span class="input-group-btn">
										<button data-toggle="dropdown"
											class="btn btn-primary btn-xs dropdown-toggle">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a href="/admin/tickets/{{$ticket->id}}">View</a></li>
											<li><a href="#" id="closeTicket" data-toggle="modal"
												data-target="#closedBy" name="{{$ticket->id}}">Close</a></li>

										</ul>
									</span>

								</div>
							</td>
							<td class="text-center">
							@if($ticket->ticket_status == "Open") 
								<span class="label label-success">{{$ticket->ticket_status}}</span>
							@elseif($ticket->ticket_status == "Pending") 
								<span class="label label-warning">{{$ticket->ticket_status}}</span>
							@elseif($ticket->ticket_status == "Closed") 
								<span class="label label-primary">{{$ticket->ticket_status}}</span>
							@elseif($ticket->ticket_status == "Unresolved") 
								<span class="label label-danger">{{$ticket->ticket_status}}</span>
							@endif

							</td>

							<td class="issue-info"><a href="/admin/tickets/{{$ticket->id}}"><span
									class="font-bold">{{$ticket->description}} - {{$ticket->id}}</span>
									<small>{{$ticket->subject}}</small></a></td>

							<td>{{$ticket->first_name.' '.$ticket->last_name}}</td>

							<td class="text-center"><span class="label label-default">{{$ticket->priority_level}}</span>
							</td>
							<td><?php echo date('M d', strtotime($ticket->updated_at)); ?></td>


						</tr>
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<td colspan="7" class="text-right">
								{{ $tickets->links() }}

							</td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>

	<div class="modal inmodal" id="closedBy" tabindex="-1" role="dialog"
		aria-hidden="true">
		<div class="modal-lg modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title">Close ticket:</h4>

				</div>
				<form class="closeTicket">
					{!! csrf_field() !!} <input type="hidden" id="closing_report"
						name="closing_report" value=""> <input type="hidden" id="ticketId"
						name="id" value="">

					<div class="modal-body">
						<h4>This ticket require's a closing report to change it's status
							to "Closed".</h4>
						<div class="ibox-content">
							<div class="ticketsummernote"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white " data-dismiss="modal">
							Cancel</button>
						<button type="button" class="btn btn-primary closeTicket">Close
							Ticket</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	@endsection
	@section('scripts')
<script type="text/javascript" src="/js/ticketing/ticketShowTickets.js">
</script>
@endsection