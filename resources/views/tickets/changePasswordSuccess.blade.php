@extends('tickets.ticketlayout1') @section('title', 'Remote Staff - Sign
Up') @section('body')

<div class="passwordBox animated fadeInDown">


	<div class="ibox-content text-center gray-bg">
		<h2 class="text-success font-bold">Password successfully changed!</h2>
		You may now login to your account using your new password <br> <br> <a
			href="/tickets/logout" class="btn btn-primary btn-block">Sign In</a>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc
		</div>
		<div class="col-md-6 text-right">
			<small>&copy;2008-<?php echo date("Y"); ?></small>
		</div>
	</div>
</div>

@endsection
