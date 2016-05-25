<!doctype html>
<html lang = "en">
	<head>
		<title>Login</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/css/mystyle.css">
		<link href="/css/bootstrap.min.css" rel="stylesheet">

		<link href="/css/bootstrap-theme.min.css" rel="stylesheet">
		<script src="/js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery.min.js"></script>
		<script src="/script.js"></script>
		<script src="/js/bootstrap.js"></script>

	</head>

	<header>

	</header>
	<body>

		<div class="container">

			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 col-md-offset-4 col-lg-offset-4 col-sm-offset-2 ">

					<div align="center">
						<img align="center" class="logo-imgimg-responsive left-block" src="/img/remote-staff-logo.jpg" alt="">
					</div>
					<div class="account-wall">
						<div class="container-fluid">
							<h1 align="center" class="login-title">Sign in</h1>
							<img id="profile-img" class=" img-responsive center-block" src="/img/1.png" alt="">
							<br>

							<form class="form-signin" role="form" method="post" action="/inventory/login">
								{!! csrf_field() !!}
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

									<input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}"name="email" required="" autofocus="">

								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

									<input type="password" class="form-control" placeholder="Password" name="password" required="">

								</div>

								

									@if (Session::has('message'))
								
								<div class="alert alert-danger">
									{{ Session::get('message') }}
								</div>
									@endif
									
								
								<div align="center">

									<button type="submit" action="" class="btn btn-lg btn-primary btn-block">
										Sign in
									</button>
								</div>
								<br>
								<a href="/inventory/forgotPassword" class="pull-right need-help">Forgot Password? </a>
								<a href="/inventory/register" class="pull-left need-help">Create an account</a>
								<br>
								<br>
							</form>
						</div>
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

	</body>
</html>
