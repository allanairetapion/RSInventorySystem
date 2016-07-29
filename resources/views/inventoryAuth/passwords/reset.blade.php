@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')

<div id="wrapper">
	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom white-bg">
			<nav class="navbar navbar-static-top" role="navigation">
				<div class="navbar-header">
					<button aria-controls="navbar" aria-expanded="false"
						data-target="#navbar" data-toggle="collapse"
						class="navbar-toggle collapsed" type="button">
						<i class="fa fa-reorder"></i>
					</button>
					<a href="#" class="navbar-brand">RSITS</a>
				</div>

				<ul class="nav navbar-top-links navbar-right">
					<li><a href="/inventory/login"> <i class="fa fa-sign-in"></i> Sign
							In
					</a></li>
				</ul>


			</nav>
		</div>

		<div class="wrapper wrapper-content">
			<div class="container">
				<div class="row">

					<div class="col-md-offset-3 col-md-6">
						<div class="ibox float-e-margins">

							<div class="ibox-content">
								<h2 class="font-bold">Change Password</h2>
								<P>
									Enter your new password for <strong>{{Session::get('resetEmail') }}</strong> and confirm your new password.
								</P>


								<form class="m-t" role="form" method="post"
									action="{{ url('/inventory/changePassword') }}">
									{!! csrf_field() !!} <input type="hidden" name="token"
										value="{{ Session::get('token') }}">

									<div
										class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

										@if ($errors->has('email')) 
										<span class="help-block"> <strong>{{$errors->first() }}</strong>
										</span> 
										@endif <input type="hidden"
											placeholder="Email Address" class="form-control" name="email"
											value="{{ Session::get('resetEmail') }}" required>

									</div>

									<div
										class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

										<input type="password" placeholder="New Password"
											class="form-control" name="password" required> 
											@if($errors->has()) 
											<span class="help-block text-warning"> <strong>{{$errors->first() }}</strong>
										</span>
										 @endif

									</div>

									<div
										class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

										<input type="password" placeholder="Confirm New Password"
											class="form-control" name="password_confirmation" required>

									</div>
									<button type="submit" action=""
										class="btn btn-primary block full-width m-b">
										<i class="fa fa-btn fa-sign-in"></i> Confirm
									</button>

								</form>

							</div>

						</div>
					</div>

				</div>
			</div>
		</div>




		<div class="footer">
			<div class="col-md-6">
				<strong>Copyright</strong> Remote Staff Inc.
			</div>
			<div class="col-md-6 text-right"> &copy; 2008-<?php echo date("Y");?>
		</div>
		</div>


	</div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$('input[type="email"]').hide();

	}); 
</script>
@endsection
