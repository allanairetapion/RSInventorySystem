@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Activate ')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class="ibox">

		<div class="ibox-title">
			<center>
				<h2 class="text-success font-bold">Account Successfuly Activated!</h2>
			</center>
		</div>
		<div class="ibox-content text-center">
			<center>
				Your account is now activated.
			</center>
			<br>
			<a href="/admin/login" class="btn btn-primary btn-block"> Click here to Sign in </a>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc.
		</div>
		<div class="col-md-6 text-right">
			&copy; 2008-<?php echo date("Y");?>
		</div>
	</div>
</div>

@endsection
