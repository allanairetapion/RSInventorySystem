@extends('tickets.ticketadminlayout')
@section('body')

<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-3 col-md-6">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="font-bold text-info">Create New Agent/Admin</h3>
			</div>
			<div class="ibox-content">

				<form class="m-t form-horizontal agentForm" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					<div class="row">
						<div class="form-group email {{ $errors->has('fname') ? ' has-error' : '' }}">
							<label class="col-md-3 control-label">First Name:&nbsp;</label>
							<div class="col-md-8">
								<input type="text" class="form-control" placeholder="First Name" name="firstname" value="{{old('fname')}}" required="">

							</div>
						</div>
						<div class="form-group fname {{ $errors->has('lname') ? ' has-error' : '' }}">
							<label class="col-md-3 control-label">Last Name:&nbsp;</label>
							<div class="col-md-8">
								<input type="text" class="form-control" placeholder="Last Name" name="lastname" value="{{old('lname')}}" required="">

							</div>
						</div>
						<div class="form-group lname {{ $errors->has('email') ? ' has-error' : '' }}">
							<label class="col-md-3 control-label">Email:&nbsp;</label>
							<div class="col-md-8">
								<input type="email" class="form-control email" placeholder="Email Address" name="email" value="{{old('email')}}" required="">

							</div>
						</div>
						<div class="form-group usertype">
							<label class="col-md-3 control-label">User type: &nbsp; </label>
							<div class="col-md-8">
								<select name="user_type"class="form-control">
									<option value=""> Select user type...</option>
									<option value="admin"> Admin</option>
									<option value="agent"> Agent</option>
								</select>

							</div>
						</div>
					</div>

					<center>
						<button type="button" class="btn btn-info btn-lg add-account">
							Create
						</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

@endsection
