<table class="table table-hover table-mail ticketsNew">
	<tbody>
		@foreach ($tickets as $ticketStat)
		<tr class="read {{$ticketStat->id}}" data-toggle="modal" data-target="#{{$ticketStat->id}}">

			@if($ticketStat->ticket_status == "New")
			<td class="check-mail">
			<input type="checkbox" class="i-checks">
			</td>
			<td class="mail-ontact"><a href="#">{{$ticketStat->sender}}</a></td>
			<td><label class="label label-primary">New!</label></td>
			<td class="mail-subject"><a href="#">{{$ticketStat->subject}}</a></td>

			<td class="text-right mail-date">{{$ticketStat->created_at}}</td>
			@elseif($ticketStat->ticket_status == "Open" && ($ticketStat->created_at < $ntime))
			<td class="check-mail">
			<input type="checkbox" class="i-checks">
			</td>
			<td class="mail-ontact"><a href="#">{{$ticketStat->sender}}</a></td>
			<td><label class="label label-danger">Overdue</label></td>
			<td class="mail-subject"><a href="#">{{$ticketStat->subject}}</a></td>

			<td class="text-right mail-date">{{$ticketStat->created_at}}</td>
			@endif
		</tr>

		<div class="modal inmodal fade" id="{{$ticketStat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-12">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
							</button>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<div class="input-group m-b">
									<div class="input-group-btn">
										<button tabindex="-1" class="btn btn-white" type="button">
											Assign Support
										</button>
									</div>
									<select class="form-control">
										Assign a support <option value="" disabled selected hidden>Assign a support</option>
										@foreach ($agent as $agents)
										<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<div class="input-group m-b">
									<div class="input-group-btn">
										<button tabindex="-1" class="btn btn-white" type="button">
											Status
										</button>
									</div>
									<select name="ticketStatus" class="form-control selectticketStatus">
										<option value="Open">Open</option>
										<option value="Pending">Pending</option>
										<option value="Closed">Closed</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-body">
						<div class="mail-box-header">
							<div class="pull-right tooltip-demo">
								<form class="ticketId">
									{!! csrf_field() !!}
									<input type="hidden" name="ticket_id" value="{{$ticketStat->id}}">
								</form>
								<button type="button" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print this email">
									<i class="fa fa-print"></i>
								</button>
								<button type="button" class="btn btn-white btn-sm deleteTicket" data-toggle="tooltip" data-placement="top" title="Delete this ticket">
									<i class="fa fa-trash-o"></i>
								</button>
							</div>
							<h2> View Message </h2>
							<div class="mail-tools tooltip-demo m-t-md">

								<h3><span class="font-noraml">Subject: </span>{{$ticketStat->subject}} </h3>
								<h5><span class="pull-right font-noraml">{{$ticketStat->created_at}}</span><span class="font-normal">From: </span><span class="senderEmail">{{$ticketStat->sender_email}} </span></h5>
							</div>
						</div>
						<div class="mail-box">

							<div class="mail-body gray-bg">

								<h4>Summary:</h4>

								<q>{!! html_entity_decode($ticketStat->summary) !!}</q>

							</div>

						</div>
						<input type="hidden" class="form-control topic"  rows="5" name="summary">
						<div class="panel panel-default ticketReply">

							<div class="panel-heading">
								<h4 class="font-bold">Reply</h4>
							</div>
							<div class="panel-body">
								<div class="ticketsummernote">

								</div>
							</div>
							<div class="panel-footer text-right">
								<button class="btn btn-primary">
									<i class="fa fa-send"></i> Send
								</button>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary btn-outline ticketReply">
								<i class="fa fa-reply"></i> Reply
							</button>
							<button type="button" class="btn btn-success btn-outline">
								<i class="fa fa-floppy-o"></i> Save
							</button>
							<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">
								<i class="fa fa-times"></i> Close
							</button>

						</div>

					</div>
				</div>
			</div>
			@endforeach
	</tbody>
</table>

