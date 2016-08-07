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
					<a data-toggle="tab" href="#menu1">Open</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu2">Pending</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu3">Closed</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu4">Unresolved</a>
				</li>
				
			</ul>

			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
						<div class="row">
				<br>
				
			</div>
			<div id="advancedSearch" class=" gray-bg" style="padding:5px;">
				<br>
				<form class="advancedTicket" class="form-horizontal" >
					{!! csrf_field() !!}

					<div class="row">
					
						<div class="col-md-3">
							<label class="control-label">Topic:</label>
							<select name="topicSearch"class="form-control topic">
								<option value="" selected>  </option>
								@foreach ($topics as $topic)
								<option value="{{$topic->description}}"> {{$topic->description}}</option>
								@endforeach
							</select>

						</div>
						<div class="col-md-3">
							<label class="control-label">Status:</label>
							<select name="statusSearch"class="form-control statusSearch ">
								<option value="" selected > </option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
							</select>
						</div>
					
						
						<div class="col-md-3">
							<label class="control-label">Assigned to:</label>
							<select name="agentSent" class="form-control agentSent ">
								<option value="" selected> </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Closed by:</label>
							<select name="agentClosed" class="form-control agentClosed">
								<option value="" selected > </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>
<div class="col-md-3">
							<label class="control-label">Sort By:</label>
							<select name="dateSort"class="form-control topic">
								<option value="1">Date Sent  </option>
								<option value="2">Date Updated  </option>
							</select>
							
						</div>
						<div class="col-md-3">
						<label class="control-label">Date Range:</label>
						<div class="input-daterange input-group" id="datepicker">
									<span class="input-group-addon">From</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateStart" value=""/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateEnd" value="" />
                                </div>
						</div>
						<div class="col-md-offset-3 col-md-3 text-center">
							<br>

							<button type="button" class="btn btn-primary  advancedTicketSearch">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="button" class="btn btn-warning  advancedTicketReset">
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
										@elseif ($ticketStat->ticket_status == "Closed")
									<tr class="navy-bg" id="{{$ticketStat->id}}">
										@else
									<tr class="red-bg" id="{{$ticketStat->id}}">
										@endif
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										
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

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport2"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										
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
								<tbody >

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Open")
									
									<tr class="bg-primary" id="{{$ticketStat->id}}">
										
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										
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
									@endif
									@endforeach

								</tbody>
							</table>
						</form>
						
					</div>
				
				</div>
				
				<div id="menu2" class="tab-pane fade in">
				<br>

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport2"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										
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
								<tbody>

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Pending")
									<tr style="background-color: #F2F256;" id="{{$ticketStat->id}}">
										
										
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										
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
@endif
									@endforeach

								</tbody>
							</table>
						</form>
						
						
					</div>
					
				
				</div>
				
				<div id="menu3" class="tab-pane fade in">
				<br>

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport2"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										
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
								<tbody>

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Closed")
									
									<tr class="navy-bg" id="{{$ticketStat->id}}">
										
										
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										
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
@endif
									@endforeach

								</tbody>
							</table>
						</form>
						
					</div>
					
				
				</div>
				
				<div id="menu4" class="tab-pane fade in">
				<br>

					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport2"
							style="font-size: small;">
								<thead>
									<tr>
										<th>
										<input type="checkbox"  class=" ticketReportCB">
										</th>
										<th>Ticket No. </th>
										<th>Sender </th>
										
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
								<tbody>

									@foreach($tickets as $ticketStat)
									@if ($ticketStat->ticket_status == "Unresolved")
									
									<tr class="red-bg" id="{{$ticketStat->id}}">
										
										<td>
										<input type="checkbox" id="reportTicket" name="id" value="{{$ticketStat->id}}">
										</td>
										<td>{{$ticketStat->id}}</td>
										
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
@endif
									@endforeach

								</tbody>
							</table>
						</form>
						
						
					</div>
					
				
				</div>

		</div>

	</div>
</div>
<script>
$('table.ticketReport')
.dataTable(
		{
			"bSort" : false,
			dom : '<"html5buttons"B>',
			buttons : [
					{
						text : '<i class="fa fa-trash"></i> Delete',
						action : function() {
							var tickets = [ 'x' ];
							var usrtype = <?php echo json_encode(Auth::guard('admin')->user()->user_type); ?>;
							if(usrtype == "agent"){
								swal('Oops...','Action not allowed','info');
								return false;
								}
							$(
									'input:checkbox:checked')
									.each(
											function() {
												tickets
														.push($(
																this)
																.val());
											});
							if (tickets[1] == ''
									|| tickets[1] == null) {
								swal(
										'Ooops...',
										"You haven't selected any ticket",
										'info');
								return false;
							}

							console
									.log($(
											'form.selectedTickets')
											.serializeArray());
							swal(
									{
										title : "Are you sure?",
										text : "This action can't be undone",
										type : "warning",
										showCancelButton : true,
										closeOnConfirm : false,
										confirmButtonText : "Yes",
									},
									function() {

										swal(
												{
													title : "Password Required!",
													text : "If you are sure, Please enter your password.",
													type : "input",
													inputType : "password",
													showCancelButton : true,
													closeOnConfirm : false,
													showLoaderOnConfirm : true,
													disableButtonsOnConfirm : true,
												},
												function(
														inputValue) {
													if (inputValue != "") {
														$
																.ajax(
																		{
																			headers : {
																				'X-CSRF-Token' : $(
																						'input[name="_token"]')
																						.val()
																			},
																			type : 'post',
																			url : '/admin/verifyPassword',
																			data : {
																				password : inputValue
																			},
																		})
																.done(
																		function(
																				data) {
																			if (data == "true") {
																				$
																						.ajax(
																								{
																									headers : {
																										'X-CSRF-Token' : $(
																												'input[name="_token"]')
																												.val()
																									},
																									type : "DELETE",
																									url : "/admin/deleteTicket",
																									data : {
																										tickets : tickets
																									},
																								})
																						.done(
																								function(
																										data) {

																									swal(
																											{
																												title : "Deleted",
																												text : "Tickets has been deleted",
																												type : "success",
																											},
																											function() {
																												$(
																														'input:checkbox:checked')
																														.each(
																																function() {
																																	$(
																																			this)
																																			.parents(
																																					'tr')
																																			.remove();
																																});
																											});

																								});
																			} else {
																				swal
																						.showInputError("Wrong Password");
																				return false;
																			}
																		});
													} else {
														swal
																.showInputError("You need to type in your password in order to do this!");
														return false;
													}
												});

									});

						}
					},
					{
						text : 'Advanced Search',
						action : function() {
							$(
									'div#advancedSearch')
									.slideToggle();
						}
					},
					{
						extend : 'csv'
					},
					{
						extend : 'excel',
						title : 'Ticket Report'
					},
					{
						extend : 'print',
						customize : function(
								win) {
							$(win.document.body)
									.addClass(
											'white-bg');
							$(win.document.body)
									.css(
											'font-size',
											'10px');
							$(win.document.body)
									.find(
											'table')
									.addClass(
											'compact')
									.css(
											'font-size',
											'inherit');
						}
					} ]

		});

$('table.ticketReport2')
.dataTable(
		{
			"bSort" : false,
			dom : '<"html5buttons"B>lTfgtip',
			buttons : [
					{
						text : '<i class="fa fa-trash"></i> Delete',
						action : function() {
							var tickets = [ 'x' ];
							var usrtype = <?php echo json_encode(Auth::guard('admin')->user()->user_type); ?>;
							if(usrtype == "agent"){
								swal('Oops...','Action not allowed','info');
								return false;
								}
							$(
									'input:checkbox:checked')
									.each(
											function() {
												tickets
														.push($(
																this)
																.val());
											});
							if (tickets[1] == ''
									|| tickets[1] == null) {
								swal(
										'Ooops...',
										"You haven't selected any ticket",
										'info');
								return false;
							}

							console
									.log($(
											'form.selectedTickets')
											.serializeArray());
							swal(
									{
										title : "Are you sure?",
										text : "This action can't be undone",
										type : "warning",
										showCancelButton : true,
										closeOnConfirm : false,
										confirmButtonText : "Yes",
									},
									function() {
										swal(
												{
													title : "Password Required!",
													text : "If you are sure, Please enter your password.",
													type : "input",
													inputType : "password",
													showCancelButton : true,
													closeOnConfirm : false,
													showLoaderOnConfirm : true,
													disableButtonsOnConfirm : true,
												},
												function(
														inputValue) {
													if (inputValue != "") {
														$
																.ajax(
																		{
																			headers : {
																				'X-CSRF-Token' : $(
																						'input[name="_token"]')
																						.val()
																			},
																			type : 'post',
																			url : '/admin/verifyPassword',
																			data : {
																				password : inputValue
																			},
																		})
																.done(
																		function(
																				data) {
																			if (data == "true") {
																				$
																						.ajax(
																								{
																									headers : {
																										'X-CSRF-Token' : $(
																												'input[name="_token"]')
																												.val()
																									},
																									type : "DELETE",
																									url : "/admin/deleteTicket",
																									data : {
																										tickets : tickets
																									},
																								})
																						.done(
																								function(
																										data) {

																									swal(
																											{
																												title : "Deleted",
																												text : "Tickets has been deleted",
																												type : "success",
																											},
																											function() {
																												$(
																														'input:checkbox:checked')
																														.each(
																																function() {
																																	$(
																																			this)
																																			.parents(
																																					'tr')
																																			.remove();
																																});
																											});

																								});
																			} else {
																				swal
																						.showInputError("Wrong Password");
																				return false;
																			}
																		});
													} else {
														swal
																.showInputError("You need to type in your password in order to do this!");
														return false;
													}
												});

									});

						}
					},
					{
						extend : 'csv'
					},
					{
						extend : 'excel',
						title : 'Ticket Report'
					},

					{
						extend : 'print',
						customize : function(
								win) {
							$(win.document.body)
									.addClass(
											'white-bg');
							$(win.document.body)
									.css(
											'font-size',
											'10px');
							$(win.document.body)
									.find(
											'table')
									.addClass(
											'compact')
									.css(
											'font-size',
											'inherit');
						}
					} ]

		});

</script>
@endsection
