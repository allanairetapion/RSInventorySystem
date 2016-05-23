@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Forgot Password')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class="ibox">

		<div class="ibox-title">
			<center>
				<h2 class="font-bold">Forgot password</h2><center>
		</div>
		<div class="ibox-content text-center">
			<p>
				Enter your email address and we will send a reset link to reset your password.
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
					<form class="m-t" role="form" action="{{ url('/admin/forgotPassword') }}" method="post">
						{!! csrf_field() !!}
						<div class="form-group">
							@if($errors->has('email'))
							<br>
							<center>
								<label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label>
							</center>

							@endif
							<input type="email" class="form-control" name="email" placeholder="Enter your email address" required="" value="{{ old('email') }}">

						</div>

						<button type="submit" class="btn btn-primary block full-width m-b">
							Send
						</button>

					</form>
				</div>
			</div>
		</div>
	</div>
	<hr/>
</div>

@endsection
