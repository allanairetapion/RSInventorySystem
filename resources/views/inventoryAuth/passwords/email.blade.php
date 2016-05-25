<!doctype html>
<html lang = "en">
	<head>
		<title>Forgot Password</title>
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
				<div class="col-sm-6 col-md-4 col-md-offset-4">
					<?php
					// the message
					$msg = "First line of text\nSecond line of text";

					// use wordwrap() if lines are longer than 70 characters
					$msg = wordwrap($msg, 70);

					// send email
					mail("someone@example.com", "My subject", $msg);
					?>

					<br>

					<img class="logo-img" src="/img/remote-staff-logo.jpg" alt="" >
					<div class="account-wall">
						<h2 align="center" class="login-title">Forgot password?</h2>
						<p align="center" style="margin: 5px">
							Enter your Email address below. We'll send you an email and follow the given instruction.
						</p>
						@if (session('status'))
						<div class="alert alert-success">
							<center>
								{{ session('status') }}
							</center>
						</div>
						@endif
						<form class="form-signin" method="post" action="{{ url('/inventory/forgotPassword') }}">

							{!! csrf_field() !!}

							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="email" id="myText" class="form-control" name="email" placeholder="Email"  value="{{ old('email') }}" required autofocus>
								@if($errors->has('email'))
								<br>
								<div class="alert alert-danger">
									<span class="glyphicon glyphicon-close"> </span> Email doesn't exist.

								</div>
								@endif
							</div>

							<button type="submit" href="" class="btn btn-lg btn-primary btn-block">
								Send
							</button>
							<br>
							<br>
						</form>
					</div>

				</div>
			</div>

		</div>
		<br>
		<footer>
			<hr width="800">
			<p align="center">
				Copyright 2016 Remote Staff Inc. All rights reserved.
			</p>
			<br>
		</footer>

	</body>

</html>
