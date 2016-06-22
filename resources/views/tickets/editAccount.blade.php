@extends('tickets.ticketclientlayout')
@section('body')
<div class="row">
<div class="col-md-offset-2 col-md-8">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h3><i class="fa fa-edit fa-1x"></i> Edit Account</h3>

		</div>
		<div class="ibox-content">
			<ul class="nav nav-tabs">

				<li class="active">
					<a href="#personalInfo" active data-toggle="tab" class="list-group-item"><i class="fa fa-user fa-fw"></i> Personal Information</a>
				</li>
				<li>
					<a href="#password" data-toggle="tab" class="list-group-item"><i class="fa fa-key fa-fw"></i> Change Password</a>
				</li>
			</ul>
			<div class="row">

				<div class="col-md-12">
					<div class="tab-content">
						<div id="personalInfo" class="tab-pane fade in active">
							<br>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4> Personal Information </h4>
								</div>
								<div class="panel-body">
									<form role="form" class="form-horizontal personalInformation">
											{!! csrf_field() !!}
										<input type="hidden" name="id" value="{{Auth::guard('user')->user()->id}}">
										<div class="form-group">
											<label class="control-label  col-md-3">First Name:</label>
											<div class="col-md-8">
												<input type="text" name="fname" class="form-control" value="{{Auth::guard('user')->user()->clientProfile->first_name}}">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label  col-md-3">Last Name:</label>
											<div class="col-md-8">
												<input type="text" name="lname" class="form-control" value="{{Auth::guard('user')->user()->clientProfile->last_name}}">
											</div>
										</div>

										<div class="form-group">
											<label class="control-label  col-md-3">Email:</label>
											<div class="col-md-8">
												<input type="email" name="email" class="form-control" value="{{Auth::guard('user')->user()->email}}">
											</div>
										</div>
										<hr>
										<button type="button "class="ladda-button btn btn-primary pull-right editPersonalInfo" data-style="zoom-in">
											Save
										</button>
									</form>
								</div>
							</div>

						</div>
						<div id="password" class="tab-pane fade in">
							<br>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4> Change Password </h4>
								</div>
								<div class="panel-body">
									<form role="form" class="form-horizontal clientChangePassword">
										{!! csrf_field() !!}
										<input type="hidden" name="id" value="{{Auth::guard('user')->user()->id}}">
										<div class="form-group oldPassword">
											<label class="control-label col-md-3">Old Password:</label>
											<div class="col-md-8">
												<input type="password" name="oldPassword" class="form-control" value="">
												<label class="col-md-12 text-danger oldPassword"></label>
											</div>
											
										</div>
										<div class="form-group newPassword">
											<label class="control-label col-md-3">New Password:</label>
											<div class="col-md-8">
												<input type="password" name="password" class="form-control" value="">
												<label class="col-md-12 text-danger newPassword"></label>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label  col-md-3">Confirm Password:</label>
											<div class="col-md-8">
												<input type="password" name="password_confirmation" class="form-control" value="">
											</div>
										</div>

										<hr>
										<button type="button" class="ladda-button btn btn-primary pull-right editPassword" data-style="zoom-in">
											Save
										</button>
									</form>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
</div>

<script>
	$(document).ready(function(){
		$('label.text-danger').hide();
	});
</script>
@endsection