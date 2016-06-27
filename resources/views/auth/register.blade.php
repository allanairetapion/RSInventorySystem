@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')

@section('body')
<br>
<div class="ibox  float-e-margins col-md-offset-3 col-md-6  animated fadeInDown">
	<div class="gray-bg ibox-title ">
		<h3 class=" font-bold">Create an Account</h3>
	</div>
	<div class="gray-bg ibox-content">
		<form class="clientForm" method="Post" action="/tickets/signUp">
			{!! csrf_field() !!}
			<div class="row">
				<div class="form-group col-md-6 firstname">
					<label>First Name:</label>
					<input type="text" class="form-control" placeholder="First Name" name="first_name"required="" value={{old("fname")}}>
					<label class="text-danger firstname "></label>
				</div>
				<div class="form-group col-md-6 lastname">
					<label>Last Name:</label>
					<input type="text" class="form-control" placeholder="Last Name" name="last_name"required="" value={{old("lname")}} >
					<label class="text-danger lastname "></label>
				</div>
				<div class="form-group col-md-12 email">
					<label>Email: <span class="text-danger email " ></span> </label>
					<input type="email" class="form-control" placeholder="Email" name="email" required="" value={{old("email")}}>
				</div>
				<div class="form-group col-md-12 department">
					<label>Department: <span class="text-danger department " ></span> </label>
					<input type="text" class="form-control department" placeholder="Department" name="dept" required="" value={{old("dept")}}>
				</div>
				<div class="form-group col-md-12 password">
					<label>Password: <span class="text-danger password " ></span> </label>
					<input type="password" class="form-control" placeholder="Password" name="password" required="" >
				</div>
				<div class="form-group col-md-12">
					<label>Confirm Password:</label>
					<input type="password" class="form-control" placeholder="Re-Type Password" name="password_confirmation" required=""  >
				</div>

				<div class="form-group col-md-6 captcha">

					<label class="">Enter CAPTCHA code:</label>

					<input type="text" class="form-control" name="captcha">
					<label class="text-danger captcha"> *Captcha code did not match</label>
				</div>

				<div class="col-md-6 text-center captcha_img">
					<br>

					<img class="captcha_img"src="{{captcha_src()}}"> &nbsp;

					<button type="button" class="btn  btn-default refreshCaptcha " data-toggle="tooltip" data-placement="top" title="Refresh CAPTCHA">
						<i class="fa fa-refresh"></i>
					</button>
				</div>

			</div>
			<hr/>
			<div class="text-center">
				<button type="button" class="ladda-button btn btn-primary registerClient btn-lg" data-style="zoom-in">
					Register
				</button>
				<br>
				<span class="text-muted">Already have an account?</span>

				<a href="/tickets/login">Login here</a>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('div.form-group').removeClass('has-error');
		$('span.text-danger').hide();
		$('label.text-danger').hide();
	});

	$(function() {
		$("input.department").keyup(function() {
			$("input.department").autocomplete({
				source : "{{URL('/search')}}",
				minLength : 1
			});
			$("#auto").autocomplete("widget").height(200);
		});
	}); 
</script>

@endsection
