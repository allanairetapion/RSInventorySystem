@extends('tickets.ticketadminlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-3 col-md-6">
		<div class="ibox">
			<div class="ibox-title">
				<h2 class="font-bold text-info">Create Ticket</h2>
			</div>
			<div class="ibox-content">

				<form class="m-t form-horizontal createTicket" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label class="col-md-3 control-label">Email:&nbsp;</label>
						<div class="col-md-8">
							<input type="email" class="form-control email" placeholder="Email Address"  disabled name="Email" value="{{Auth::guard('admin')->user()->email}}" required="">
						</div>
					</div>
					<div class="form-group ">
						<label class="col-md-3 control-label">Name:&nbsp;</label>
						<div class="col-md-8">
							<input type="text" disabled class="form-control"  name="Fullname"
							value="{{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Department:&nbsp;</label>
						<div class="col-md-8">
							<input type="text" disabled class="form-control email" placeholder="Email Address" name="department" value="Support" required="">

						</div>
					</div>
					<div class="form-group topic">
						<label class="col-md-3 control-label">Topic: &nbsp; </label>
						<div class="col-md-8">
							<select name="Topic"class="form-control topic">
								<option value=""> Choose topic... </option>
								<option value="admin"> Admin</option>
								<option value="agent"> Agent</option>
							</select>
						</div>
					</div>
					<div class="form-group subject">
						<label class="col-md-3 control-label">Subject: &nbsp; </label>
						<div class="col-md-8">
							<input type="text" class="form-control" placeholder="Enter subject..." name="Subject" required>

						</div>
					</div>

					<div class="form-group summary">
						<label class="control-label col-md-3 " for="Summary">Summary:</label>
						<div class="col-md-8 ">
							<textarea class="form-control topic" rows="5" name="Summary"></textarea>
						</div>
					</div>

					<center>
						<button type="button" class="btn btn-info btn-lg create-ticket">
							Create
						</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
