<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Remote Staff Ticketing | Login</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/plugins/jQueryUI/jquery-ui.css" />
		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>

		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		
		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>
		
	</head>

	<body class="white-bg top-navigation" >
		
<div class="loginColumns animated fadeInDown">
	<div class="row">

		<div class="col-md-6 text-center">

			<img class="img-center center-block img-responsive" src="/img/remote_logo2.jpg">

			<h2 class="text-success font-bold">Remote Staff</h2><H3 class="text-navy">Relationships You Can Rely On</h3>
		</div>
		<div class="col-md-6">
			
			<div class="ibox-content text-center gray-bg">
				<form class="m-t"  >
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
					<a href="/tickets/forgotPassword"><small>Forgot password?</small></a>
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


	</body>

	
</html>
