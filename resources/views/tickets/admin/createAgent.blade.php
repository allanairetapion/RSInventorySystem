@extends('tickets.ticketadminlayout')
@section('body')

<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-2 col-md-8">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="font-bold text-info">Create New User</h3>
			</div>
			<div class="ibox-content">

				<form class="m-t  agentForm" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					<div class="row">
						<div class="form-group col-md-6 fname">
								<label>First Name:&nbsp;</label>							
								<input type="text" class="form-control" placeholder="First Name" name="firstname" value="{{old('firstname')}}" required="">
								<label class="text-danger fname">r</label>
							
						</div>
						<div class="form-group col-md-6 lname {{ $errors->has('lname') ? ' has-error' : '' }}">
							<label>Last Name:&nbsp;</label>
							
								<input type="text" class="form-control" placeholder="Last Name" name="lastname" value="{{old('lastname')}}" required="">
								<label class="text-danger lname">r</label>
							
						</div>
						<div class="form-group col-md-12 email {{ $errors->has('email') ? ' has-error' : '' }}">
							<label>Email: <span class="text-danger email"></span></label>
						
								<input type="email" class="form-control email" placeholder="Email Address" name="email" value="{{old('email')}}" required="">
								
						
						</div>
						<div class="form-group col-md-12 usertype">
							<label>User type:  <span class="text-danger usertype"></span> </label>							
								<select name="user_type"class="form-control">									
									@if(Auth::guard('admin')->user()->user_type == 'agent')
									<option value="agent"> Agent</option>
									@else
									<option value=""> Select user type...</option>
									<option value="admin"> Admin</option>
									<option value="agent"> Agent</option>
									@endif
								</select>
								
							
						</div>
					</div>

					<center>
						<button type="button" class="btn btn-info btn-lg add-account">
							Create
						</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	$(document).ready(function(){
		$('span.text-danger').hide();
		$('label.text-danger').hide();
	});
</script>

@endsection
