@extends('tickets.ticketlayout1') @section('title', 'Remote Staff -
Change Password') @section('body')

<div class="passwordBox animated fadeInDown">
	<div class='ibox'>

		<div class="ibox-content text-center gray-bg">
			<h2 class="text-success font-bold">Password successfully changed!</h2>
			You may now login to your account using your new password <br> <br> <a
				href="/admin/logout" class="btn btn-primary btn-block">Sign In</a>
		</div>
	</div>

	<hr />
	<div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc.
		</div>
		<div class="col-md-6 text-right">
			<small>© 2015-2016</small>
		</div>
	</div>
</div>

@endsection
