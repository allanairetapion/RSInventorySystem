@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Forgot Password')
@section('body')

<div class="passwordBox animated fadeInDown">
	
	<div class="ibox-content gray-bg">
	<h2 class="font-bold">Forgot password</h2>
		<p>
			Enter your email address below and we will send a reset link to reset your password.
		</p>

		@if (session('status'))
		<div class="alert alert-success">
			<center>
				{{ session('status') }}
			</center>
		</div>
		@endif

		<div class="row">

			<div class="col-lg-12">
				<form class="m-t" role="form" action="{{ url('/tickets/forgotPassword') }}" method="post">
					{!! csrf_field() !!}
					<div class="form-group">
						@if($errors->has('email'))

						<center>
							<label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label>
						</center>

						@endif
						<input type="email" class="form-control" name="email" placeholder="Email address" required="" value="{{ old('email') }}">

					</div>

					<button type="submit" class="btn btn-primary block full-width m-b">
						Send Verification Code
					</button>

				</form>
			</div>

		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc
		</div>
		<div class="col-md-6 text-right">
			<small>&copy;2008-<?php echo date("Y"); ?></small>
		</div>
	</div>
</div>
@endsection
