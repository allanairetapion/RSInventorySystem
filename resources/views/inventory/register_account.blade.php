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

							<form class="form-reg" role="form" method="Post" action="register" >

								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">

									<div class="col-lg-6">
										<input type="text" class="form-control"  style="text-transform: capitalize;" name="first_name" placeholder="Firstname" autocomplete="off" required autofocus>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control"  style="text-transform: capitalize;" name="last_name"  placeholder="Lastname" autocomplete="off" required>
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

									<div class="col-lg-12">
										<input type="number" class="form-control" maxlength="11" name="phone_number" autocomplete="off" placeholder="Phone Number"  required>
									</div>
								</div>

								<br>
								<div class="container-fluid">

									<div class="row" style="background-color: #D9D9D9; border-radius:10px">
										<br>
										<p style="text-align: left; margin-left: 10px; margin-top: -10px">
											<strong>Type the code you see below:</strong>
										</p>

										<div class="col-lg-5">

											<div style="margin-left: 5px" form-group refreshrecapcha{{ $errors->
												has('captcha') ? ' has-error' : '' }}" >
												{!! captcha_img('flat') !!}

											</div>
										</div>

										<div class="col-lg-7">

											<p>
												<input class="form form-control" type="text" name="captcha" placeholder="Enter the code here" style="margin-left: 0px">
											</p>
										</div>
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
