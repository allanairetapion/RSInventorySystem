<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>@yield('title')</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery-2.1.1.js"></script>

	</head>

	<body class="gray-bg">
		<div class="white-bg">
			<center><img src="/img/remote-staff-logo.jpg" class="img-responsive">
			</center>

		</div>
		<div class="wrapper wrapper-content">
			<div class="container">
				<div class="row">
					@section('body')
					@show
				</div>
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
	</body>

</html>
