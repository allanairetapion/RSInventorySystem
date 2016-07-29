@extends('tickets.ticketadminlayout')
@section('body')

<div class="row">

	<div class="ibox float-e-margins">
		

		<div class="ibox-content">
			<ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#home">Home</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu1">Top Issues</a>
				</li>
				
			</ul>

			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
						<div class="row">
				<br>
				

				<div class="col-md-offset-9 col-md-3 text-right">
					@if(Auth::guard('admin')->user()->user_type == 'admin')
					<button class="btn btn-warning btn-sm  ticketDelete">
						<i class="fa fa-trash"></i> Delete
					</button>
					@endif
					<button class="btn btn-primary btn-sm advancedSearch">
						Advanced Search
					</button>
				</div>
			</div>
			<div id="advancedSearch" class=" gray-bg" style="padding:5px;">
				<br>
				<form class="advancedTicket" >
					{!! csrf_field() !!}

					<div class="row">
						<div class="col-md-3">
							<label class="control-label">Date Sent:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateSent" data-mask="9999-99-99" class="form-control dateSent input-sm" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Date Closed:</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="dateClosed" data-mask="9999-99-99" class="form-control dateClosed input-sm" value="">
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Topic:</label>
							<select name="topicSearch"class="form-control topic input-sm">
								<option value="" selected>  </option>
								@foreach ($topics as $topic)
								<option value="{{$topic->description}}"> {{$topic->description}}</option>
								@endforeach
							</select>

						</div>
						<div class="col-md-3">
							<label class="control-label">Status:</label>
							<select name="statusSearch"class="form-control statusSearch input-sm">
								<option value="" selected > </option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
							</select>
						</div>
					</div>
					<div class="row">
						<br>
						<div class="col-md-3">
						<label class="control-label">Date Range:</label>
						<div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="input-sm form-control" data-mask="9999-99-99" name="start" value=""/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="input-sm form-control" data-mask="9999-99-99" name="end" value="" />
                                </div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Assigned to:</label>
							<select name="agentSent" class="form-control agentSent input-sm">
								<option value="" selected> </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Closed by:</label>
							<select name="agentClosed" class="form-control agentClosed input-sm">
								<option value="" selected > </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-3 text-center">
							<br>

							<button type="button" class="btn btn-primary  advancedTicketSearch">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="reset" class="btn btn-warning  advancedTicketReset">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
					</div>

				</form>
				<br>
			</div>
					<br>

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										<th>Sender Id </th>
										<th>Topic </th>
										<th>Subject</th>
										<th>Status</th>
										<th>Department</th>
										<th>Assigned Support</th>
										<th>Closed By</th>
										<th>Date Sent</th>
										<th>Date Updated</th>
									</tr>
								</thead>
								<tbody class="ticketReport">

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Pending")
									<tr style="background-color: #F2F256;" id="{{$ticketStat->id}}">
										@elseif ($ticketStat->ticket_status == "Open")
									<tr class="bg-primary" id="{{$ticketStat->id}}">
										@else
									<tr class="navy-bg" id="{{$ticketStat->id}}">
										@endif
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										<td>{{$ticketStat->sender}}</td>
										<td>{{$ticketStat->sender_id}}</td>
										<td>{{$ticketStat->description}}</td>
										<td>{{$ticketStat->subject}}</td>

										<td>{{$ticketStat->ticket_status}}</td>
										<td>{{$ticketStat->department}}</td>
										@foreach($assigned_to as $assigned)
										@if($assigned->id == $ticketStat->id)
										<td>{{$assigned->first_name.' '.$assigned->last_name}}</td>
										@endif
										@endforeach
										@foreach($closed_by as $closed)
										@if($closed->id == $ticketStat->id)
										<td>{{$closed->first_name.' '.$closed->last_name}}</td>
										@endif
										@endforeach
										<td>{{$ticketStat->created_at}}</td>
										<td>{{$ticketStat->updated_at}}</td>
									</tr>

									@endforeach

								</tbody>
							</table>
						</form>
						
						<div class="pagination pull-right">
							<?php echo $tickets -> render(); ?>
						</div>
					</div>
					
					
				</div>
				
				<div id="menu1" class="tab-pane fade in">
				<br>
					<div class="row">
						<div class="col-md-12 b-r">
							<div class="panel panel-default">
							<div class="panel-heading">Top Issues
								<div class="pull-right">
									<div class="btn-group">
										<button type="button" class="btn btn-xs btn-default topIssueWeek ">
											This Week
										</button>
										<button type="button" class="btn btn-xs btn-default topIssueMonth">
											This Month
										</button>
										<button type="button" class="btn btn-xs btn-default topIssueYear">
											This Year
										</button>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6">
										<h4 class="text-center">Top Issues</h4> 
										<div id="pie"></div>
                                     </div>
                                     <div class="col-md-6">
										<h4 class="text-center">Ticket Source</h4> 
										<div id="pie"></div>
                                     </div>
                                 </div>
                                         
							</div>
							
						</div>
						
					</div>
						
					
				</div>
				
			</div>

		</div>

	</div>
</div>

@endsection
