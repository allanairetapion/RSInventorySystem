<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Remote Staff Inventory | Login</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/plugins/jQueryUI/jquery-ui.css" />
		

	</head>

	<body class="white-bg top-navigation" >
		
<div class="loginColumns animated fadeInDown">
	<div class="row">

		<div class="col-md-6 text-center">


			<img class="img-center center-block img-responsive"
				src="/img/remote_logo2.jpg">

			<h2 class="text-success font-bold">Remote Staff</h2>
			<H3 class="text-navy">Relationships You Can Rely On</h3>
		</div>
		<div class="col-md-6">
<br><br>
			<div class="ibox-content text-center gray-bg">
				<form class="m-t" role="form" method="post" action="login">
					{!! csrf_field() !!}
					<h4 class="text-warning ">{{ Session::get('message') }}</h4>
					@if ($errors->has('email'))
					<h4 class="text-warning">{{$errors->first()}}</h4>
					@endif
					<div
						class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<input type="email" class="form-control" placeholder="Enter email"
							value="{{ old('email') }}" name="email" required="">
					</div>
					<div
						class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input type="password" class="form-control" placeholder="Password"
							name="password" required="">
					</div>
					<button type="submit" action=""
						class="btn btn-primary block full-width m-b">
						<i class="fa fa-btn fa-sign-in"></i>&nbsp;Sign in
					</button>
					<a href="/inventory/forgotPassword"><small>Forgot password?</small></a>

				</form>

			</div>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc.
		</div>
		<div class="col-md-6 text-right">
			<small>© 2008-2016</small>
		</div>
	</div>
</div>


	</body>

	
</html>




