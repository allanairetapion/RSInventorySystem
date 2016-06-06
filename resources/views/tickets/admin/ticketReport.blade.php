@extends('tickets.ticketadminlayout')
@section('body')
<div class="container-fluid">
	<div class="row">

		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h2 class="font-bold">Tickets</h2>
			</div>

			<div class="ibox-content">

				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#home">Home</a>
					</li>
					<li>
						<a data-toggle="tab" href="#menu1">Open</a>
					</li>
					<li>
						<a data-toggle="tab" href="#menu2">Pending</a>
					</li>
					<li>
						<a data-toggle="tab" href="#menu3">Closed</a>
					</li>
				</ul>
				<div class="row">
					<br>
					<div class="col-sm-3">

						<div class="input-group">
							<input type="text" placeholder="Search" class="input-sm form-control">
							<span class="input-group-btn">
								<button type="button" class="btn btn-sm btn-primary">
									Go!
								</button> </span>
						</div>
					</div>

					<div class="col-md-offset-7 col-md-2">
						<button class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo">
							Advanced Search
						</button>
					</div>
					<div id="demo" class="collapse">
						<br>
						<form class="form-horizontal">
							<div class="container">
								<div class="row">
									<div class="col-md-3">
										<label>Search by Date:</label>
										<div class="input-group date" data-provide="datepicker">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" value="<?php echo date("Y/m/d")?>">
										</div>
									</div>
									<div class="col-md-3">
										<label>Status:</label>
										<select class="form-control">
											<option value="" selected hidden>Search by status...</option>
											<option value="Open">Open</option>
											<option value="Pending">Pending</option>
											<option value="Closed">Closed</option>
										</select>
									</div>
									<div class="col-md-3">
										<label>Closed by:</label>
										<select class="form-control">
											<option>Hello</option>
										</select>
									</div>
								</div>
							</div>
						</form>
					</div>

				</div>

				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<br>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped .table-condensed ticketReport">
								<thead>
									<tr>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Topic </th>

										<th>Summary</th>

										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Closing_Report</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody>

									@foreach($tickets as $ticketStat)
									<tr data-toggle="modal" data-target="#{{$ticketStat->id}}">
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->description}}</td>

										<td><small>click row to view</small></td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>
										<td>{{$ticketStat->closing_report}}</td>
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->updated_at}}</td>
									</tr>

									<div class="modal inmodal fade" id="{{$ticketStat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
													</button>
													<i class="fa fa-envelope modal-icon"></i>
												</div>
												<div class="pull-right ">
													<select class="col-lg-4 form-control">

														<option>Open</option>
														<option>Pending</option>
														<option>Closed</option>
													</select>
												</div>
												<div class="container">
													<h4 class="font-bold">Date Sent: {{$ticketStat->created_at}}</h4>
													<h4 class="font-bold">Sender: {{$ticketStat->sender}}</h4>
													<h4 class="font-bold">Email: {{$ticketStat->sender_email}}</h4>
													<h4 class="font-bold">Topic: {{$ticketStat->description}}</h4>
													<h4 class="font-bold">Subject: {{$ticketStat->subject}}</h4>
												</div>
												<div class="modal-body">

													<h4>Summary:</h4>

													<q>{!! html_entity_decode($ticketStat->summary) !!}</q>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-info ticketReply">
														Reply
													</button>
													<button type="button" class="btn btn-success">
														Save
													</button>
													<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">
														Close
													</button>

												</div>

											</div>
										</div>
									</div>
									@endforeach

								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="pagination pull-right">
								<?php echo $tickets -> render(); ?>
							</div>
						</div>
					</div>
					<div id="menu1" class="tab-pane fade">
						<br>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped ticketReport">
								<thead>
									<tr>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Summary</th>
										<th>Priority</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Closing_Report</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody>

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Open")
									<tr data-toggle="modal" data-target="#{{$ticketStat->id}}">
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>
										<td><small>click row to view</small></td>
										<td>{{$ticketStat->priority}}</td>
										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>
										<td>{{$ticketStat->closing_report}}</td>
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->updated_at}}</td>
									</tr>

									<div class="modal inmodal fade" id="{{$ticketStat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
													</button>
													<i class="fa fa-envelope modal-icon"></i>
												</div>
												<div class="pull-right ">
													<select class="col-lg-4 form-control">
														<option>Change Status...</option>
														<option>Open</option>
														<option>Pending</option>
														<option>Closed</option>
													</select>
												</div>
												<div class="container">
													<h4 class="font-bold">Date Sent: {{$ticketStat->created_at}}</h4>
													<h4 class="font-bold">Sender: {{$ticketStat->sender}}</h4>
													<h4 class="font-bold">Email: {{$ticketStat->sender_email}}</h4>
													<h4 class="font-bold">Topic: {{$ticketStat->description}}</h4>
													<h4 class="font-bold">Subject: {{$ticketStat->subject}}</h4>
												</div>
												<div class="modal-body">

													<h4>Summary:</h4>

													<q>{!! html_entity_decode($ticketStat->summary) !!}</q>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-info">
														Reply
													</button>
													<button type="button" class="btn btn-success">
														Save
													</button>
													<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">
														Close
													</button>

												</div>

											</div>
										</div>
									</div>
									@endif
									@endforeach

								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="pagination pull-right">
								<?php echo $tickets -> render(); ?>
							</div>
						</div>
					</div>
					<div id="menu2" class="tab-pane fade">
						<br>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped ticketReport">
								<thead>
									<tr>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Summary</th>
										<th>Priority</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Closing_Report</th>
										<th>Date Sent</th>
										<th>Date Closed</th>

									</tr>
								</thead>
								<tbody>

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Pending")
									<tr data-toggle="modal" data-target="#{{$ticketStat->id}}">
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>
										<td><small>click row to view</small></td>
										<td>{{$ticketStat->priority}}</td>
										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>
										<td>{{$ticketStat->closing_report}}</td>
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->updated_at}}</td>
									</tr>

									<div class="modal inmodal fade" id="{{$ticketStat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
													</button>
													<i class="fa fa-envelope modal-icon"></i>
												</div>
												<div class="pull-right ">
													<select class="col-lg-4 form-control">
														<option>Change Status...</option>
														<option>Open</option>
														<option>Pending</option>
														<option>Closed</option>
													</select>
												</div>
												<div class="container">
													<h4 class="font-bold">Date Sent: {{$ticketStat->created_at}}</h4>
													<h4 class="font-bold">Sender: {{$ticketStat->sender}}</h4>
													<h4 class="font-bold">Email: {{$ticketStat->sender_email}}</h4>
													<h4 class="font-bold">Topic: {{$ticketStat->description}}</h4>
													<h4 class="font-bold">Subject: {{$ticketStat->subject}}</h4>
												</div>
												<div class="modal-body">

													<h4>Summary:</h4>

													<q>{!! html_entity_decode($ticketStat->summary) !!}</q>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-info">
														Reply
													</button>
													<button type="button" class="btn btn-success">
														Save
													</button>
													<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">
														Close
													</button>

												</div>

											</div>
										</div>
									</div>
									@endif
									@endforeach

								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="pagination pull-right">
								<?php echo $tickets -> render(); ?>
							</div>
						</div>
					</div>
					<div id="menu3" class="tab-pane fade">
						<br>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped ticketReport">
								<thead>
									<tr>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Summary</th>
										<th>Priority</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned_Support</th>
										<th>Closed_By</th>
										<th>Closing_Report</th>
										<th>Date Sent</th>
										<th>Date Closed</th>
									</tr>
								</thead>
								<tbody>

									@foreach($tickets as $ticketStat)
									@if($ticketStat->ticket_status == "Closed")
									<tr data-toggle="modal" data-target="#{{$ticketStat->id}}">
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>
										<td><small>click row to view</small></td>
										<td>{{$ticketStat->priority}}</td>
										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										<td>{{$ticketStat->assigned_support}}</td>
										<td>{{$ticketStat->closed_by}}</td>
										<td>{{$ticketStat->closing_report}}</td>
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->updated_at}}</td>
									</tr>

									<div class="modal inmodal fade" id="{{$ticketStat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
													</button>
													<i class="fa fa-envelope modal-icon"></i>
												</div>
												<div class="pull-right ">
													<select class="col-lg-4 form-control">
														<option>Change Status...</option>
														<option>Open</option>
														<option>Pending</option>
														<option>Closed</option>
													</select>
												</div>
												<div class="container">
													<h4 class="font-bold">Date Sent: {{$ticketStat->created_at}}</h4>
													<h4 class="font-bold">Sender: {{$ticketStat->sender}}</h4>
													<h4 class="font-bold">Email: {{$ticketStat->sender_email}}</h4>
													<h4 class="font-bold">Topic: {{$ticketStat->description}}</h4>
													<h4 class="font-bold">Subject: {{$ticketStat->subject}}</h4>
												</div>
												<div class="modal-body">

													<h4>Summary:</h4>

													<q>{!! html_entity_decode($ticketStat->summary) !!}</q>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-info">
													Reply
												</button>
												<button type="button" class="btn btn-success">
													Save
												</button>
												<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">
													Close
												</button>

											</div>

										</div>
									</div>
						</div>
						@endif
						@endforeach

						</tbody>
						</table>

					</div>
					<div class="row">
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
 <script src="/js/jquery-2.1.1.js"></script>
  <!-- Data picker -->
   <script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <script src="/js/bootstrap.min.js"></script>



<script>

 $('#.input-group.date').datepicker({
 			
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
               
            });

</script>
@endsection
