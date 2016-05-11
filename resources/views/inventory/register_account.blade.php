<!doctype html>
<html lang = "en">
	<head>
		<title>Create Account</title>
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
				<div class="col-lg-6 col-md-4 col-lg-offset-3">

					<div align="center">
						<img align="center" class="logo-imgimg-responsive left-block" src="/img/remote-staff-logo.jpg" alt="">
					</div>
					<div class="account-wall">
						<div class="container-fluid">
							<h1 align="center" class="login-title">Create new account</h1>
							<br>

							<form class="form-reg" role="form" method="Post" action="register_account" >

								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">

									<div class="col-lg-6">
										<input type="text" class="form-control"  style="text-transform: capitalize;" name="firstname" placeholder="Firstname" autocomplete="off" required autofocus>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control"  style="text-transform: capitalize;" name="lastname"  placeholder="Lastname" autocomplete="off" required>
									</div>
								</div>
								<br>
								<input type="email" class="form-control" name="email" placeholder="E-mail" autocomplete="off" required>
								<br>
								<input type="password" class="form-control" name="password" placeholder="Password" required>
								<br>
								<input type="password" class="form-control" name="password_confirmation" placeholder="Re-type password" required>
								<br>

								<div class="row">
									<div class="col-lg-4">

										<select class="select form-control" name="usertype"  style="width: 180px; height: 35px" >

											<option value=""   disabled="disabled"></option>
											<option value="Admin"  selected="selected">Admin</option>
											<option value="Technical Support">Technical Support</option>
											<option value="Staff">Staff</option>
										</select>

									</div>

									<div class="col-lg-7 col-lg-offset-1">
										<input type="number" class="form-control" maxlength="11" name="phone_number" autocomplete="off" placeholder="Phone Number"  required>
									</div>
								</div>

								<br>
								@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
										<li>
											{{ $error }}
										</li>
										@endforeach
									</ul>
								</div>
								@endif


	<div class="well" align="center">

									<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
										{!! captcha_img() !!}
									</div>

									<p>
										<input type="text" name="captcha">
									</p>

								</div>


								<button type="submit" class="btn btn-lg btn-primary btn-block">
									Register
								</button>
						</div>
						</form>

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
