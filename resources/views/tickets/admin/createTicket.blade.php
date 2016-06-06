@extends('tickets.ticketadminlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-1 col-md-10">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="font-bold">Ticket Form</h3>
			</div>
			<div class="ibox-content">

				<form class="m-t form-horizontal createTicket" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label class="col-md-2 control-label">Email:&nbsp;</label>
						<div class="col-md-10">
							<input type="email" class="form-control email" placeholder="Email Address"  disabled name="Email" value="{{Auth::guard('admin')->user()->email}}" required="">
						</div>
					</div>
					<div class="form-group ">
						<label class="col-md-2 control-label">Name:&nbsp;</label>
						<div class="col-md-10">
							<input type="text" disabled class="form-control"  name="Fullname"
							value="{{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Department:&nbsp;</label>
						<div class="col-md-10">
							<input type="text" disabled class="form-control" placeholder="Email Address" name="department" value="Support" required="">

						</div>
					</div>
					<div class="form-group assigned_support">
						<label class="col-md-2 control-label">Assigned Support:&nbsp;</label>
						<div class="col-md-10">
							<select name="assigned_support" class="form-control topic">
								<option value="" disabled selected hidden> Assign a support... </option>
								@foreach ($agent as $agents)
								<option value="{{$agents->first_name.' '.$agents->last_name}}"> {{$agents->first_name.' '.$agents->last_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
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

						<div class="col-md-12 ">

							<input type="hidden" class="form-control topic"  rows="5" name="summary">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Summary</h5>
									<div class="ibox-tools">
										<a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>

									</div>

								</div>
								<div class="ibox-content gray-bg">

									<div class="ticketsummernote">

									</div>

								</div>
							</div>
						</div>
					</div>
					<center>
						<button type="button" class="btn btn-info btn-lg create-ticket">
							Create
						</button>
					</center>
			</div>
		</div>
	</div>
	@endsection
