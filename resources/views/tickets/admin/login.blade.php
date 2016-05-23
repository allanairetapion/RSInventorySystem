@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Log In')
@section('body')
<div class="col-md-offset-4 col-md-4 text-center animated fadeInDown ">
	<div class="ibox-title">
		<h2 class="font-bold">Sign in</h2>
	</div>
	<div class="ibox-content">

		<form class="m-t" role="form" method="post" action="login">
			{!! csrf_field() !!}
			<center>
				<label class="text-warning">{{ Session::get('message') }}</label>
			</center>
			@if ($errors->has('email'))
			<center>
				<label class="text-warning">{{$errors->first()}}</label>
			</center>

			@endif

			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}"name="email" required="">

			</div>
			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<input type="password" class="form-control" placeholder="Password" name="password" required="">

			</div>
			<button type="submit" action="" class="btn btn-primary block full-width m-b">
				<i class="fa fa-btn fa-sign-in"></i>&nbsp;Login
			</button>

			<a href="/admin/forgotPassword"><small>Forgot password?</small></a>
			<p class="text-muted text-center">
				<small>Do not have an account?</small>
			</p>
			<a class="btn btn-sm btn-white btn-block" href="/admin/register">Create an account</a>
		</form>
	</div>

</div>
<div class="footer">
	<p class="pull-right">
		&copy;2014-2015
	</p>
	<p>
		<strong>Copyright</strong> Remote Staff Inc.
	</p>
</div>
@endsection
