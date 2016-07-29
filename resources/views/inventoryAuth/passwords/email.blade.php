@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Forgot Password')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class="ibox">

		
		<div class="ibox-content gray-bg">
		<h2 class="font-bold">Reset Password</h2>
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

			
					<form class="m-t" role="form" action="{{ url('/inventory/forgotPassword') }}" method="post">
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
							<i class="fa fa-btn fa-send"></i> Send
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
