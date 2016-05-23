@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

<div class=" text-center loginscreen   animated fadeInDown">

	<div class="col-md-offset-3 col-md-6">

		<div class="well">

			<form class="m-t" role="form" method="Post" action="/admin/register">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group">
					<div>
						@if($errors->has('fname'))
						<center>
							<label class="text-danger"><strong>First name must be atleast 2 characters and only contain alphabets.</strong></label>
						</center>
						@endif

						@if($errors->has('lname'))
						<center>
							<label class="text-danger"><strong>Last name must be atleast 2 characters and only contain alphabets.</strong></label>
						</center>
						@endif
						<div class="col-md-6{{ $errors->has('fname') ? ' has-error' : '' }}">
							<input type="text" class="form-control" placeholder="First Name" name="fname"required="" value={{old("fname")}}>
							<br>
						</div>
						<div class="col-md-6{{ $errors->has('lname') ? ' has-error' : '' }}">
							<input type="text" class="form-control" placeholder="Last Name" name="lname"required="" value={{old("lname")}} >
							<br>
						</div>
					</div>
					<div>
						@if($errors->has('email'))
						<center>
							<label class="text-danger"><strong>This</strong> email exists</label>
						</center>
						@endif
						<div class="col-md-6{{ $errors->has('email') ? ' has-error' : '' }}">
							<input type="email" class="form-control" placeholder="Email" name="email" required="" value={{old("email")}}>
							<br>
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control" placeholder="user_type" name="user_type" required="">
							<br>
						</div>
					</div>
					<div>
						@if($errors->has('password'))
						<center>
							<label class="text-danger"><strong>The password must be between 6 and 100 characters or Password didn't match.</label>
						</center>
						@endif
						@if($errors->has('password_confirmation'))
						<center>
							<label class="text-danger"><strong> The password confirmation does not match.</label>
						</center>
						@endif
						<div class="col-md-6 {{ $errors->has('password') ? ' has-error' : '' }}">
							<input type="password" class="form-control" placeholder="Password" name="password" required="" value={{old("password")}}>
							<br>
						</div>
						<div class="col-md-6">
							<input type="password" class="form-control" placeholder="Re-Type Password" name="password_confirmation" required="" value={{old("password_confirmation")}} >
							<br>
						</div>
					</div>
				</div>

				<button type="submit"  class="btn btn-primary block full-width m-b">
					Register
				</button>

				<p class="text-muted text-center">
					<small>Already have an account?</small>
				</p>

				<a class="btn btn-sm btn-white btn-block" href="/tickets/login">Login</a>

			</form>
		</div>

		<div class="row">
			<div class="col-md-7">
				Copyright Remote Staff Inc
			</div>
			<div class="col-md-5 text-right">
				<small>© 2014-2015</small>
			</div>
		</div>

	</div>

	@endsection
