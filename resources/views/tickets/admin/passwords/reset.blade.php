@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')


<div class="passwordBox animated fadeInDown">
	<div class="ibox">

		<div class="ibox-title text-center gray-bg">
			<center>
				<h2 class="font-bold">Reset Password</h2>
			</center>
		</div>
		<div class="ibox-content gray-bg">
<center>
			<P class="font-bold">
				Enter your new password.
			</P></center>

			<form class="m-t" role="form" method="post" action="{{ url('/admin/changePassword') }}"  >
				{!! csrf_field() !!}
				<input type="hidden" name="token" value="{{ $token }}">

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

					@if ($errors->has('email'))
					<span class="help-block"> <strong>{{ $errors->first() }}</strong> </span>
					@endif

					<input type="hidden" placeholder="Email Address" class="form-control" name="email" value="{{ $email or old('email') }}" required>

				</div>

				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

					<input type="password" placeholder="New Password" class="form-control" name="password" required>

					@if ($errors->has())
					<span class="help-block text-warning"> <strong>{{ $errors->first() }}</strong> </span>
					@endif

				</div>

				<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

					<input type="password" placeholder="Confirm New Password" class="form-control" name="password_confirmation" required>					

				</div>
				<button type="submit" action="" class="btn btn-primary block full-width m-b">
					<i class="fa fa-btn fa-sign-in"></i> Confirm
				</button>

			</form>

		</div>

	</div>
	<hr/>
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc.
		</div>
		<div class="col-md-6 text-right">
			&copy; 2008-<?php echo date("Y");?>
		</div>
	</div>

</div>

@endsection
