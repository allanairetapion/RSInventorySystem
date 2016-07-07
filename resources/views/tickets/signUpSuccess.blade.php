@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class="row">

		<div class="ibox-title ">
			<center>
				<h2 class="text-success font-bold">Account successfuly created</h2>
			</center>
		</div>
		<div class="ibox-content text-center navy-bg">
			We've sent an activation link to your email address.
			<br>
			To activate your account, please check your email.
			<br>
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
