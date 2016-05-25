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
				<div class="col-lg-6 col-sm-6 col-md-4 col-md-offset-3" align="center">
					<?php
					// the message
					$msg = "First line of text\nSecond line of text";

					// use wordwrap() if lines are longer than 70 characters
					$msg = wordwrap($msg, 70);

					// send email
					mail("someone@example.com", "My subject", $msg);
					?>

					<br>

					<img class="logo-img" src="/img/remote-staff-logo.jpg" alt="">
					<div class="account-wall">
						<h2 align="center" class="login-title">Thank you!</h2>
						<div class="row">
							<div class="col-lg-10  col-lg-offset-1">
								<div class="alert alert-success" style="text-align:center; padding-left: 50px; padding-right: 50px">
									<span class="glyphicon glyphicon-ok"> </span> Email has been sent successfully to  Please check your email account now and click the URL inside the email and follow the given instruction.
								</div>

							</div>
							<br>
							<br>
							</form>

						</div>
						<br>
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
