<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Change Password</title>
		<meta name="description" content="">
		<meta name="author" content="ITDEVS06">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/css/mystyle.css">
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/css/bootstrap-theme.min.css" rel="stylesheet">

		<script src="/js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery.min.js"></script>
		<script src="/script.js"></script>
		<script src="/js/bootstrap.js"></script>

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	</head>

	<body>
		<div>
			<header>

			</header>
			<nav>

			</nav>

			<div class="container">

				<div class="row">
					<div class="col-sm-6 col-md-4 col-md-offset-4">

						<div align="center"><img align="center" class="logo-img" src="/img/remote-staff-logo.jpg" alt="">
						</div>
						<div class="account-wall">

							<h1 align="center" class="login-title">Change your password</h1>

							<form class="form-signin" method="POST"  action="{{ url('/inventory/changePassword') }}">

								{!! csrf_field() !!}
								<input type="hidden" name="token" value="{{ $token }}">
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<input type="email" placeholder="Email Address" class="form-control" name="email" value="{{ $email or old('email') }}" required="required">
								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

									<input type="password" class="form-control" name="password" placeholder="New password" required>

								</div>

								<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

									<input type="password" class="form-control" name="password_confirmation" placeholder="Re-type password" required>

								</div>

								@if ($errors->has('email'))
								<div class="alert alert-danger">
									<strong>{{ $errors->first('email') }}</strong>
								</div>
								@endif

								@if ($errors->has('password'))
								<div class="alert alert-danger">
									<strong>{{ $errors->first('password') }}</strong>
								</div>
								@endif

								@if ($errors->has('password_confirmation'))
								<div class="alert alert-danger">
									<strong>{{ $errors->first('password_confirmation') }}</strong>
								</div>
								@endif
								<button type="submit" action="" class="btn btn-lg btn-primary btn-block">
									Submit
								</button>
								<br>

							</form>
						</div>
						</
					</div>
				</div>

			</div>

		</div>

		<footer>
			<hr width="800">
			<p align="center">
				Copyright 2016 Remote Staff Inc. All rights reserved.
			</p>
			<br>
		</footer>
		</div>
	</body>
</html>
