@extends('tickets.ticketadminlayout')
@section('body')
<div class="row">
	<div class="ibox col-md-offset-3 col-md-6  animated fadeInDown">
		<div class="ibox-title ">
			<h3 class=" font-bold">Create an Account</h3>
		</div>
		<div class="ibox-content">
			<form class="clientForm">
				{!! csrf_field() !!}

				<div class="row">
					<div class="form-group col-md-6 firstname">
						<label>First Name:</label>
						<input type="text" class="form-control" placeholder="First Name" name="first_name"required="" value={{old("fname")}}>
						<label class="text-danger firstname ">*First name may only contain letters</label>
					</div>
					<div class="form-group col-md-6 lastname">
						<label>Last Name:</label>
						<input type="text" class="form-control" placeholder="Last Name" name="last_name"required="" value={{old("lname")}} >
						<label class="text-danger lastname ">*Last name may only contain letters</label>
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

				</div>
				<hr/>
				<div class="text-center">
					<button type="button" class="ladda-button btn btn-primary registerClient btn-lg" data-style="zoom-in">
						Create
					</button>
					<br>

				</div>
			</form>
		</div>
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