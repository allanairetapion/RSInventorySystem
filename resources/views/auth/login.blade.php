@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Log In')
@section('body')
<div class="loginColumns animated fadeInDown">
	<div class="row">

		<div class="col-md-6 text-center">

			<img class="img-center center-block img-responsive" src="/img/remote_logo2.jpg">

			<h3 class="text-success">Remote Staff</h3><H3 class="text-navy">We make it work, we make it last.</h3>
		</div>
		<div class="col-md-6">
			<div class="ibox-title  gray-bg text-center">
				<h2 class="font-bold ">Sign in</h2>
			</div>
			<div class="ibox-content text-center gray-bg">
				<form class="m-t" role="form" method="post" action="login"  >
					{!! csrf_field() !!}
					<h4 class="text-warning ">{{ Session::get('message') }}</h4>
					@if ($errors->has('email'))
					<h4 class="text-warning">{{$errors->first()}}</h4>
					@endif
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<input type="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}"name="email" required="">
					</div>
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input type="password" class="form-control" placeholder="Password" name="password" required="">
					</div>
					<button type="submit" action="" class="btn btn-primary block full-width m-b">
						<i class="fa fa-btn fa-sign-in"></i>&nbsp;Sign in
					</button>
					<a href="/admin/forgotPassword"><small>Forgot password?</small></a>
					<p class="text-muted text-center">
						<small>Do not have an account?</small>
					</p>
					<a class="btn btn-sm btn-white btn-block" href="/tickets/signUp">Create an account</a>
				</form>

			</div>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc.
		</div>
		<div class="col-md-6 text-right">
			<small>&copy; 2008-<?php echo date("Y"); ?></small>
		</div>
	</div>
</div>

@endsection
