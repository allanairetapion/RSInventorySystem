@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class="ibox">
		<div class="ibox-title">
			<center>
				<h2 class="font-bold">Account Activation</h2>
			</center>
		</div>

		<div class="ibox-content text-center">
			<P>
				To finish your account activation, Please setup your password.
			</P>

			<form class="m-t form-horizontal" role="form" method="post" action="{{ url('/admin/changePassword') }}"  >
				{!! csrf_field() !!}

				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

					@if($errors->has())
					<span class="help-block"> <label class="text-warning">{{ $errors->first() }}</label> </span>
					@endif

					<div class="col-md-7">
						<input type="email" class=" form-control email" placeholder="Email Address" name="email" value="{{$email or old('email')}}" required="">
					</div>

				</div>

				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

					<label class="col-md-5 control-label">Password:&nbsp;</label>
					<div class="col-md-7">
						<input type="password" placeholder="Password" class="form-control" name="password" required>
					</div>

				</div>

				<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

					<label class="col-md-5 control-label">Confirm Password:&nbsp;</label>
					<div class="col-md-7">
						<input type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>
					</div>

				</div>
				<button type="submit" action="" class="btn btn-primary block full-width m-b">
					<i class="fa fa-btn fa-sign-in"></i>&nbsp;Confirm
				</button>

			</form>

		</div>

	</div>
	<hr/>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('input[type="email"]').hide();
	}); 
</script>
@endsection
