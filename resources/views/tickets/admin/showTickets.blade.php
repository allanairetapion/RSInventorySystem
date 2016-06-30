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
							<a href="/admin/tickets-Assigned"><i class="fa fa-tasks "></i>My Tickets </a>
						</li>
						@if(Auth::guard('admin')->user()->user_type == 'admin')	
						<li>
							<a href="/admin/tickets-Open"><i class="fa fa-ticket"></i>Open Tickets </a>
						</li>						
						<li>
							<a href="/admin/tickets-Pending"><i class="fa fa-warning"></i>Pending Tickets </a>
						</li>
						@endif
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

			<form method="get" action="index.html" class="pull-right mail-search">
				<div class="input-group">
					<input type="text" class="form-control input-sm" name="search" placeholder="Search email">
					<div class="input-group-btn">
						<button type="button" class="btn btn-sm btn-primary">
							Search
						</button>
					</div>
				</div>
			</form>
			<h2> Inbox </h2>
			<div class="mail-tools tooltip-demo m-t-md">
				<div class="btn-group pull-right">
					
					<a href="{{$tickets->previousPageUrl()}}"class="btn btn-white btn-sm">
						<i class="fa fa-arrow-left"></i>
					</a>
					@if($tickets->hasMorePages())
					<a href="{{$tickets->nextPageUrl()}}" class="btn btn-white btn-sm">
						<i class="fa fa-arrow-right"></i>
					</a>
					@else
					<a href="{{$tickets->nextPageUrl()}}" disabled class="btn btn-white btn-sm">
						<i class="fa fa-arrow-right"></i>
					</a>
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
				<tbody>
					@foreach ($tickets as $ticket)
					
					<tr class="read" onclick="window.document.location='/admin/tickets/{{$ticket->id}}'">
						
						<td class="check-mail">
						<input type="checkbox" class="i-checks" name="id" value="{{$ticket->id}}">
						</td>
						<td class="mail-ontact">
						@if($ticket->priority == "High")	
						<span class="label label-danger pull-right">{{$ticket->priority}}</span>						
						@elseif($ticket->priority == "Normal")
						<span class="label label-warning pull-right">{{$ticket->priority}}</span>				
						@else
						<span class="label label-primary pull-right">{{$ticket->priority}}</span>					
						@endif
						</td>
						
						<td class="mail-subject"><span class="font-bold">{{ str_limit(strip_tags($ticket->subject), $limit = 40, $end = '...') }} </span> </td>
						
						<td class="text-right mail-date">{{$ticket->created_at}}</td>
					</tr>
					@endforeach

				</tbody>
			</table>
			</form>

		</div>
	</div>

	@endsection
