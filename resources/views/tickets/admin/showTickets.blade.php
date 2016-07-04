@extends('tickets.ticketadminlayout')
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
	<div class="col-lg-9 animated fadeInRight mail">
		<div class="mail-box-header">

		
				<button type="button" class="btn btn-sm btn-primary advancedSearch pull-right">
					Advanced Search
				</button>
				<h2> Inbox </h2>
		
			<div id="advancedSearch" class=" gray-bg" style="padding:5px;">
				<br>
				<form class="advancedTicket" method="POST" action="/admin/advancedEmailSearch">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-3">
							<label class="control-label">Date Sent:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateSent" class="form-control dateSent" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Date Closed:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateClosed" class="form-control dateClosed" value="">
							</div>
						</div>
							<div class="col-md-3">
								<label class="control-label">Topic:</label>
								<select name="topicSearch"class="form-control topic">
								<option value="" selected hidden> Choose a topic... </option>
								@foreach ($topics as $topic)														
								<option value="{{$topic->description}}"> {{$topic->description}}</option>								
								@endforeach
							</select>
								
							</div>
						<div class="col-md-3">
							<label class="control-label">Status:</label>
							<select name="statusSearch"class="form-control statusSearch">
								<option value="" selected hidden>Search by status...</option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
							</select>
						</div>

				
					<div class="col-md-3">
						<label class="control-label">Email:</label>
						<input type="email" name="email" class="form-control email"/>
					</div>
					<div class="col-md-3">
								<label class="control-label">Assigned to:</label>
								<select name="agentSent" class="form-control agentSent">
									<option value="" selected hidden>Select agent...</option>
									@foreach ($agent as $agents)
									<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3">
								<label class="control-label">Closed by:</label>
								<select name="agentClosed" class="form-control agentClosed">
									<option value="" selected hidden>Select agent...</option>
									@foreach ($agent as $agents)
									<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
									@endforeach
								</select>
							</div>
					

						<div class="col-md-3 text-center">
							<br>

							<button type="submit" class="btn btn-primary  advancedEmailSearch">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="reset" class="btn btn-warning  ">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
	</div>
					

				</form>
				<br>
			</div>

			<div class="mail-tools tooltip-demo m-t-md">
				<div class="btn-group pagination pull-right">

					<a href="{{$tickets->previousPageUrl()}}"class="btn btn-white btn-sm"> <i class="fa fa-arrow-left"></i> </a>
					@if($tickets->hasMorePages())
					<a href="{{$tickets->nextPageUrl()}}" class="btn btn-white btn-sm"> <i class="fa fa-arrow-right"></i> </a>
					@else
					<a href="{{$tickets->nextPageUrl()}}" disabled class="btn btn-white btn-sm"> <i class="fa fa-arrow-right"></i> </a>
					@endif
				</div>

				<button class="btn btn-white btn-sm refreshBtn" data-toggle="tooltip" data-placement="left" title="Refresh inbox">
					<i class="fa fa-refresh"></i> Refresh
				</button>
				@if(Auth::guard('admin')->user()->user_type == 'admin')
				<button class="btn btn-white btn-sm ticketDelete" data-toggle="tooltip" data-placement="top" title="Delete">
					<i class="fa fa-trash-o"></i>
				</button>
				@endif

			</div>
		</div>
		<div class="mail-box">
			<form class="selectedTickets">
				{!! csrf_field() !!}
				<table class="table table-hover table-mail">
					<tbody class="ticketReport">
						@foreach ($tickets as $ticket)

						<tr class="read" onclick="window.document.location='/admin/tickets/{{$ticket->id}}'">

							<td class="check-mail">
							<input type="checkbox" class="i-checks" name="id" value="{{$ticket->id}}">
							</td>
							<td class="mail-ontact"> {{$ticket->sender}} </td>

							<td class="mail-subject"><span class="font-bold">{{ str_limit(strip_tags($ticket->subject), $limit = 40, $end = '...') }} </span></td>
							<td> @if($ticket->priority_level == "High") <span class="label label-danger">{{$ticket->priority_level}}</span> @elseif($ticket->priority_level == "Normal") <span class="label label-warning">{{$ticket->priority_level}}</span> @else <span class="label label-primary">{{$ticket->priority_level}}</span> @endif <span class="label label-default">{{$ticket->description}}</span></td>
							<td class="text-right mail-date">{{$ticket->created_at}}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</form>

		</div>
	</div>

	<script>
		$(document).ready(function() {
			$.ajax({
				type : 'get',
				url : '/admin/ticketCount',
			}).done(function(data) {
				console.log(data);
				$('span.openTickets').text(data.newTickets);
				$('span.pendingTickets').text(data.pendingTickets);
				$('span.unresolvedTickets').text(data.overdueTickets);
				$('span.assignedTickets').text(data.assignedTickets);
			});
		});
	</script>
	@endsection
