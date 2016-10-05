@extends('inventory.inventory')

@section('title', 'Remote Staff Inventory System')

@section('header-page')

<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

	<div class="col-lg-10">
		<h2>Manage Accounts</h2>
		<ol class="breadcrumb">
			<li>
				<a href="/inventory/index">Home</a>
			</li>

			<li class="active">
				<strong>Authorizations</strong>
			</li>
		</ol>
	</div>


@endsection

@section('content')
<div class="row animated fadeInRight">

	<div class="col-md-3">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h4>
					<strong>{{Auth::guard('inventory')->user()->adminProfile->first_name.' '.Auth::guard('inventory')->user()->adminProfile->last_name }}</strong>
					
				</h4>
			</div>
			<div>
				<div
					class="ibox-content no-padding border-left-right profilePicture">
					<img id="profilePicture" alt="image" class="img-responsive"
						src="{{Auth::guard('inventory')->user()->adminProfile->photo}}" onerror="this.src = '/img/default-profile.jpg'">
				</div>
				<div class="ibox-content profile-content">
					<div class="user-button">
						<div class="row">
							
								<button class="btn btn-primary btn-block" data-toggle="modal"
									data-target="#uploadPicture">Update Profile Picture</button>
							
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h3>
					<i class="fa fa-edit fa-1x"></i> Edit Account
				</h3>

			</div>
			<div class="ibox-content">
				<ul class="nav nav-tabs">

					<li class="active"><a href="#personalInfo" active data-toggle="tab"
						class="list-group-item"><i class="fa fa-user fa-fw"></i> Personal
							Information</a></li>
					<li><a href="#password" data-toggle="tab" class="list-group-item"><i
							class="fa fa-key fa-fw"></i> Change Password</a></li>
				</ul>
				<div class="row">

					<div class="col-md-12">
						<div class="tab-content">
							<div id="personalInfo" class="tab-pane fade in active">
								<br>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4>Personal Information</h4>
									</div>
									<div class="panel-body">
										<form role="form" class="form-horizontal personalInformation">
											{!! csrf_field() !!} <input type="hidden" name="id"
												value="{{Auth::guard('inventory')->user()->id}}">
											<div class="form-group">
												<label class="control-label  col-md-3">First Name:</label>
												<div class="col-md-8">
													<input type="text" name="fname" class="form-control"
														value="{{Auth::guard('inventory')->user()->adminProfile->first_name}}">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label  col-md-3">Last Name:</label>
												<div class="col-md-8">
													<input type="text" name="lname" class="form-control"
														value="{{Auth::guard('inventory')->user()->adminProfile->last_name}}">
												</div>
											</div>

											<div class="form-group">
												<label class="control-label  col-md-3">Email:</label>
												<div class="col-md-8">
													<input type="email" name="email" class="form-control"
														value="{{Auth::guard('inventory')->user()->email}}">
												</div>
											</div>

										</form>
									</div>
									<div class="panel-footer text-right">
										<button type="button "
											class="ladda-button btn btn-primary  btn-w-m editPersonalInfo"
											data-style="zoom-in">Save</button>
										<a href="/admin" class="btn btn-default btn-w-m">Cancel</a>
									</div>
								</div>

							</div>
							<div id="password" class="tab-pane fade in">
								<br>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4>Change Password</h4>
									</div>
									<div class="panel-body">
										<form role="form" class="form-horizontal adminChangePassword">
											{!! csrf_field() !!} <input type="hidden" name="id"
												value="{{Auth::guard('inventory')->user()->id}}">
											<div class="form-group oldPassword">
												<label class="control-label col-md-3">Old Password:</label>
												<div class="col-md-8">
													<input type="password" name="oldPassword"
														class="form-control" value=""> <label
														class="col-md-12 text-danger oldPassword"></label>
												</div>

											</div>
											<div class="form-group newPassword">
												<label class="control-label col-md-3">New Password:</label>
												<div class="col-md-8">
													<input type="password" name="password" class="form-control"
														value=""> <label class="col-md-12 text-danger newPassword"></label>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label  col-md-3">Confirm Password:</label>
												<div class="col-md-8">
													<input type="password" name="password_confirmation"
														class="form-control" value="">
												</div>
											</div>



										</form>
									</div>
									<div class="panel-footer text-right">
										<button type="button"
											class="ladda-button btn btn-primary btn-w-m editPassword"
											data-style="zoom-in">Save</button>
										<a href="/admin" class="btn btn-default btn-w-m">Cancel</a>
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


<div class="modal inmodal fade" id="uploadPicture" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Upload Picture</h4>

			</div>
			<div class="modal-body">
				 <div class="spiner">
                                <div class="sk-spinner sk-spinner-three-bounce">
                                    <div class="sk-bounce1"></div>
                                    <div class="sk-bounce2"></div>
                                    <div class="sk-bounce3"></div>
                                </div>
                   </div>
				<div class="row editPicture">
					<div class="col-md-6">
						<div class="image-crop">
							<img class="img-responsive" src="/img/agents/{{Auth::guard('inventory')->user()->id}}.jpg" onerror="this.src = '/img/default-profile.jpg'">
						</div>
					</div>
					<div class="col-md-6">

						<form class="editProfilePicture">
						{!! csrf_field() !!}
							<input type='hidden' name="id" value="{{Auth::guard('inventory')->user()->id}}">
							<input type='hidden' class="editProfilePicture" name="photo">
						</form>

						<div class="btn-group">
						<label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" accept="image/*" name="file" id="inputImage" class="hide">
                                        Upload new image
                                    </label>
							 
						<label title="Donload image" id="download"
								class="btn btn-primary">Download Preview Image</label>

						</div>
						<div class="btn-group pictureControls">
							<button class="btn btn-white" id="zoomIn" type="button">Zoom In</button>
							<button class="btn btn-white" id="zoomOut" type="button">Zoom Out</button>
							<button class="btn btn-white" id="rotateLeft" type="button">Rotate
								Left</button>
							<button class="btn btn-white" id="rotateRight" type="button">Rotate
								Right</button>

						</div>
						<h4>Preview image</h4>
						<center>
							<div class="img-preview img-preview-sm"></div>
						</center>

					</div>
				</div>


			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary editProfilePicture">Save changes</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('label.text-danger').hide();
		$('div.spiner').hide();
		$('div.pictureControls').hide();
		
	});
	 var $inputImage = $("#inputImage");
	 var $image = $(".image-crop > img")
	 
     if (window.FileReader) {
         $inputImage.change(function() {
             
        	$('div.spiner').show();
            $('div.editPicture').hide();
            
             var fileReader = new FileReader(),
                     files = this.files,
                     file;

             if (!files.length) {
                 return;
             }

             file = files[0];

             if (/^image\/\w+$/.test(file.type)) {
                 fileReader.readAsDataURL(file);
                 fileReader.onload = function () {
                     
                	 $('div.spiner').hide();
                	 $('div.editPicture').show();
                	 $('div.pictureControls').show();

                	 $($image).cropper({
                         aspectRatio: 1,
                         preview: ".img-preview",
                         done: function(data) {
                             // Output the result data for cropping image.
                         }
                     });
                     
                     $image.cropper("reset", true).cropper("replace", this.result);
                     console.log(this.result);
                 };
             } else {
                 showMessage("Please choose an image file.");
             }
         });
     } else {
         $inputImage.addClass("hide");
     }

     $("#download").click(function() {
         window.open($image.cropper("getDataURL"));
     });

     $("#zoomIn").click(function() {
         $image.cropper("zoom", 0.1);
     });

     $("#zoomOut").click(function() {
         $image.cropper("zoom", -0.1);
     });

     $("#rotateLeft").click(function() {
         $image.cropper("rotate", 45);
     });

     $("#rotateRight").click(function() {
         $image.cropper("rotate", -45);
     });
	
</script>
@endsection

<!--
@section('scripts')

@endsection
-->
