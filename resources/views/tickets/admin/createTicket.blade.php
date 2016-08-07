@extends('tickets.ticketadminlayout')
@section('body')
<div class="container">
	<div class="  ibox animated fadeInDown">
		<div class="col-md-offset-1 col-md-10">
			<div class="ibox">
				<div class="ibox-title">
					<h3 class="font-bold">Create Ticket</h3>
				</div>
				<div class="ibox-content">

					<form class="m-t form-horizontal createTicket" role="form" method="Post" action="/checkAdmin">
						{!! csrf_field() !!}

						
						@if(Auth::guard('admin')->user()->user_type == 'admin')
						<div class="form-group assigned_support">
							<label class="col-md-2 control-label">Assigned Support:&nbsp;</label>
							<div class="col-md-10">
								<select name="assigned_support" class="form-control support">
									<option value=""  selected > Assign a support... </option>
									
										@foreach ($agent as $agents)
										<option value="{{$agents->id}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
									    @endforeach
								</select>
							</div>
						</div>
						@endif
						<div class="form-group topic">
							<label class="col-md-2 control-label">Topic: &nbsp; </label>
							<div class="col-md-10">
								<select name="topic"class="form-control topic">
									<option value=""> Choose a topic... </option>
									@foreach ($topics as $topic)
									<option value="{{$topic->topic_id}}"> {{$topic->description}}</option>
									@endforeach
								</select>

							</div>
						</div>
						<div class="form-group subject">
							<label class="col-md-2 control-label">Subject: &nbsp; </label>
							<div class="col-md-10">
								<input type="text" class="form-control" placeholder="What's the problem?" name="subject" required>

							</div>
						</div>

						<div class="form-group summary">

							

							<label class="col-md-2 control-label">Summary:</label>

							<div class="col-md-10" style="padding:0px;border: solid grey 1px;">
								<input type="hidden" class="form-control topic"  rows="5" name="summary">
								
									<div class="ticketsummernote">

									</div>


							</div>
						</div>
						</form>
						<hr>
						<center>
							<button type="button" data-style="zoom-in" class="ladda-button btn btn-info btn-lg create-ticket">
								Create
							</button>
						</center>
				</div>
			</div>
		</div>
	</div>
	@endsection
