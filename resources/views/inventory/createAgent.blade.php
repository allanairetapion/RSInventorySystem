@extends('inventory.inventory') 
@section('title', 'RS | Agents') 

@section('header-page')
<div class="col-lg-10">
	<h2>Agents</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li>Agents</li>
		<li class="active"><strong>Create New Agent</strong></li>
	</ol>
</div>
@endsection 

@section('content')
<div class="row">
<div class="col-md-12">
		<div class="ibox animated fadeInDown">
			<div class="ibox-title">
				<h3 class="font-bold">Create New User</h3>
			</div>
			<div class="ibox-content">

				<form class="m-t agentForm">
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
									
									<option value=""> Select user type...</option>
									<option value="admin"> Admin</option>
									<option value="agent"> Agent</option>
								
								</select>		
						</div>
					</div>

					<hr>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-w-m add-account">
							Create
						</button>
						<a href="/admin/agents" class="btn btn-white btn-w-m">Cancel</a>
					</div>
				</form>
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