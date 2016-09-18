@extends('tickets.ticketadminlayout')
@section('body')

<div class="row">
	
</div>
	<div class="ibox float-e-margins">
		<div class="ibox-title">
		<ul class="nav nav-tabs">
				<li class="ticketReport active">
					<a data-toggle="tab" href="#home">Home</a>
				</li>
				<li class="ticketReport">
					<a data-toggle="tab" href="#menu1">Open</a>
				</li>
				<li class="ticketReport">
					<a data-toggle="tab" href="#menu2">Pending</a>
				</li>
				<li class="ticketReport">
					<a data-toggle="tab" href="#menu3">Closed</a>
				</li>
				<li class="ticketReport">
					<a data-toggle="tab" href="#menu4">Unresolved</a>
				</li>
				
			</ul>
		</div>

		<div class="ibox-content">
			

			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
			
			<form class="advancedTicket form-horizontal">
					{!! csrf_field() !!}

					<div class="row">
					
						<div class="form-group col-md-3">
							<label class="control-label col-md-3"> Topic:</label>
							<div class="col-md-9">
							<select name="topicSearch"class="topic form-control ">
								<option value="" selected>  </option>
								@foreach ($topics as $topic)
								<option value="{{$topic->description}}"> {{$topic->description}}</option>
								@endforeach
							</select>
							</div>
						</div>
						
						<div class=" form-group col-md-3">
							<label class="control-label col-md-4">Status:</label>
							<div class="col-md-8">
							<select name="statusSearch"class="statusSearch form-control">
								<option value="" selected > </option>
								<option value="Open">Open</option>
								<option value="Pending">Pending</option>
								<option value="Closed">Closed</option>
								<option value="Unresolved">Unresolved</option>
							</select>
							</div>
						</div>
						
					<div class="form-group col-md-6">

							<label class="control-label col-md-2">Date:</label>
							<div class="col-md-4">
							<select name="dateSort"class="topic form-control">
								<option value="1">Date Sent  </option>
								<option value="2">Date Updated  </option>
							</select>
							</div>
						
						
						<div class="input-daterange input-group col-md-6" id="datepicker">
									<span class="input-group-addon">From</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateStart" value=""/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class=" form-control" data-mask="9999-99-99" name="dateEnd" value="" />
                                </div>
                                
						</div>
						
						<div class="form-group col-md-4">
							<label class="control-label col-md-4">Assigned to:</label>
							<div class="col-md-8">
							<select name="agentSent" class="agentSent form-control">
								<option value="" selected> </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
							</div>
						</div>
						<div class="form-group col-md-4">
							<label class="control-label col-md-4">Closed by:</label>
							<div class="col-md-8">
							<select name="agentClosed" class="agentClosed form-control">
								<option value="" selected > </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
							</div>
						</div>
						

						<div class="col-md-4 text-right" >
						
							<button type="button" class="btn btn-primary btn-w-m advancedTicketSearch">
								<i class="fa fa-search"></i> Search
							</button>
							<button type="button" class="btn btn-warning btn-w-m advancedTicketReset">
								<i class="fa fa-refresh"></i> Reset
							</button>

						</div>
					</div>

				</form>
				
				<br>
			
					<div class="table-responsive">
						<form class="selectedTickets">
							{!! csrf_field() !!}
							<table class="table table-bordered ticketReport">
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
									<tr>
										<td>
										
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
										<input type="checkbox"  class=" ticketOpenCB">
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
										
										<td><input class="Open" type="checkbox"></td>
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
										<input type="checkbox"  class=" ticketPendingCB">
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
										
										
										<td><input class="Pending" type="checkbox"></td>
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
										<input type="checkbox"  class=" ticketClosedCB">
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
										
										
										<td><input class="Closed" type="checkbox"></td>
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
										<input type="checkbox"  class=" ticketUnresolvedCB">
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
										
										<td><input class="Unresolved" type="checkbox"></td>
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
			"createdRow": function( row, data, dataIndex ) {
			    if ( data[5] == "Open" ) {
				    
			      $(row).addClass( 'bg-primary' );
			    }else if (data[5] == "Pending"){
				   $(row).css('background-color','#F2F256');
				}else if (data[5] == "Closed"){
					$(row).addClass('navy-bg');
				}else if (data[5] == "Unresolved"){
					$(row).addClass('red-bg');
				}
			  },
			  'columnDefs': [{
			         'targets': 0,
			         'searchable':false,
			         'orderable':false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '<input type="checkbox" class="ticketReport">';
			         }
			      }],
			"bSort" : false,
			dom : '<"html5buttons"B>lTfgtip',
			buttons : [
					{
						text : '<i class="fa fa-trash"></i> Delete',
						action : function(){
							ticketReportDelete('ticketReport')
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

$('table.ticketReport2').dataTable({
			"bSort" : false,
			dom : '<"html5buttons"B>lTfgtip',
			buttons : [{
						text : '<i class="fa fa-trash"></i> Delete',
						action : function() {
							ticketReportDelete($('li.ticketReport.active a').text());

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
function ticketReportDelete(ticketStatus){
	console.log(ticketStatus);
	var tickets = [ 'x' ];
	var usrtype = <?php echo json_encode(Auth::guard('admin')->user()->user_type); ?>;
	if(usrtype == "agent"){
		swal('Oops...','Action not allowed','info');
		return false;
		}
	$('input.'+ ticketStatus + ':checkbox:checked').each(function() {
						tickets.push($(this).closest('tr').find('td:eq(1)').text());
						
					});
	console.log(tickets);
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

				swal({
					title : 'Are you sure?',
					text : "This Action can't be undone",
					type : 'warning',
					showCancelButton : true,
					showCancelButton : true,
					closeOnConfirm : false,
					showLoaderOnConfirm : true,
					disableButtonsOnConfirm : true,
				}, function() {
					

					$.ajax({
							headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
							type : "DELETE",
							url : "/admin/deleteTicket",
							data : {tickets : tickets},
							}).done(function(data) {
									swal({
											title : "Deleted",
											text : "Tickets has been deleted",
											type : "success",
										},function() {
											var table = $('table.ticketReport').DataTable();
											 
											
											    table.rows( $('input:checkbox:checked').parents('tr') )
											    .remove().draw();
											
											
										
							});
					
				});

			});

			});


}
</script>
@endsection
