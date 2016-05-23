@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

<div class="passwordBox animated fadeInDown">
	<div class='ibox'>
		<div class="ibox-title">
			<center>
				<h3 class="text-success font-bold">Password successfully changed</h3>
			</center>
		</div>
		<div class="ibox-content text-center">

			You may now login to your account using your new password
			<br>
			<br>
			<a href="/admin/logout" class="btn btn-primary btn-block">Sign In</a>
		</div>
	</div>
	<hr/>
</div>

@endsection
