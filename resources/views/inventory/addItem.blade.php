@extends('inventory.inventory') @section('title', 'RS | Add Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Add Items</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><strong>Add Items</strong></li>
	</ol>
</div>
<div class="col-lg-2"></div>

@endsection @section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Add new item</h5>
			</div>
			<div class="ibox-content">
				<form class="form-horizontal addItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-lg-7">
							<div class="col-lg-6">
								<div class="unique_id">
									<label class="control-label">Unique Identifier:</label> <input
										type="text" class="form-control"
										placeholder="Unique Identifier" id="unique_id" name="unique_id" required> <span
										class="help-block text-danger unique_id">192.168.100.200</span>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="itemNo">
									<label class="control-label">Item No:</label> <input type="text"
										class="form-control" placeholder="Item No." name="itemNo" id="itemNo"
										required> <span class="help-block text-danger itemNo">192.168.100.200</span>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="brand">
									<label class="control-label"> Brand:</label> <input type="text"
										class="form-control" placeholder="Brand" name="brand" id="brand" required>
									<span class="help-block text-danger brand">192.168.100.200</span>

								</div>
							</div>
							<div class="col-lg-6">
								<div class="model">
									<label class="control-label"> Model:</label> <input type="text"
										class="form-control" placeholder="Model" name="model" id="model" required>
									<span class="help-block text-danger model">192.168.100.200</span>

								</div>
							</div>
							<div class="col-lg-6">
								<div class="company">
									<label class="control-label"> Company:</label> <input
										type="text" class="form-control" placeholder="Company"
										name="company" id="company" required> <span
										class="help-block text-danger company">192.168.100.200</span>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="stationNo">
									<label class="control-label"> Station No:</label> <input
										type="text" class="form-control" placeholder="Station No."
										name="stationNo" id="stationNo" required> <span
										class="help-block text-danger stationNo">192.168.100.200</span>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="specification">
									<label class="control-label"> Specification:</label>
									<input type="hidden" id="specification" name="specification">
									<div id="itemDescription"></div>
									<span
										class="help-block text-danger specification">192.168.100.200</span>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="itemType">
									<label class="control-label"> Item Type:</label> <input
										type="text" class="form-control" placeholder="Item Type"
										name="itemType" id="itemType" required> <span
										class="help-block text-danger itemType">192.168.100.200</span>

								</div>
							</div>
							<div class="col-lg-6">
								<div class="dateArrived">
									<label class="control-label "> Date Arrived:</label>

									<div class="input-group date dateArrived">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
											type="text" class="form-control dateArrived"
											name="dateArrived" id="dateArrived">

									</div>

									<span class="help-block text-danger dateArrived">192.168.100.200</span>

								</div>
							</div>

						</div>
						<div class="col-lg-5">
							<div class="col-lg-12">
								<div id="itemPhoto" class="dropzone">
									<div class="dropzone-previews"></div>
									<div class="dz-message" data-dz-message>
										<span>Click to upload or Drop Item's photo here.</span><br> <span>Image
											files only. </span><br> <span>2 MB per image limit. 10 MB
											total limit.</span>
									</div>
								</div>
							</div>


						</div>
						<div class="col-lg-12 text-right">
							<hr>
							<button class="btn btn-w-m btn-primary addItem" type="button">
								<strong>Submit</strong>
							</button>
							<a href="/inventory/index" class="btn btn-danger btn-w-m">Cancel</a>

						</div>
					</div>
				</form>
			</div>
		</div>

	</div>

</div>

<script>
	$(document).ready(function(){
			$('span.text-danger').hide();
			$('div#itemDescription').summernote();

			Dropzone.options.itemPhoto = {
					autoDiscover : false,
					paramName: "photo",
					url: "/inventory/addItem",
					acceptedFiles: 'image/*',
					autoProcessQueue: false,
					addRemoveLinks: true,
					uploadMultiple: true,
					parallelUploads: 100,
					maxFilesize: 2,
					maxFiles: 4,
					init: function() 
				    {
					    
				    thisDropzone = this;
				    var totalFileSize = 0;
				    var createTicket = $('button.create-ticket').ladda();
				    
				    this.on("addedfile", function (file) {
	                    var _this = this;
	                    
	                    if ($.inArray(file.type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']) == -1) {
	                        swal('Ooops','You can only upload image files','info');
	                        _this.removeFile(file);
	                        return false;
	                    }
	                    totalFileSize += file.size;
	                    if(totalFileSize > 10485760){
	                    	_this.removeFile(file);
	                    	swal('Ooops','You exceeded the total file upload size limit (10 MB)','info');
	                        }
	                    
	                });
				    this.on("removedfile", function (file) {
				    	totalFileSize -= file.size;
					    });
	                this.on("completemultiple", function(file) {
	                	file.status = Dropzone.QUEUED;
	                });
				    $('button.addItem').click(function(e) {
				    	$('div.form-group').removeClass('has-error');
						$('span.text-danger').hide();
				    	e.preventDefault();
						e.stopPropagation();
						$('input#specification').val($('div#itemDescription').summernote('code'));
						
						if (thisDropzone.getQueuedFiles().length > 0) {
							thisDropzone.processQueue();
							createTicket.ladda('start');
						  }
						  else {
							 
							createTicket.ladda('start');
							  console.log('here');
							  $('input[type="hidden"].topic').val($('div.ticketsummernote').summernote('code'));
								
								$('div.topic').removeClass('has-error');
								$('div.subject').removeClass('has-error');
								$('div.summary').removeClass('has-error');
								e.preventDefault();

								$.ajax({
									type : "POST",
									url : "/inventory/addItem",
									data :  $("form.addItem").serialize(),
									error: function(data){
										var errors = data.responseJSON;
										if(errors.errors["unique_id"]){
											$('span.unique_id').text(errors.errors["unique_id"]).show();
											$('div.unique_id').addClass('has-error');
										}
										if(errors.errors["itemNo"]){
											$('span.itemNo').text(errors.errors["itemNo"]).show();
											$('div.itemNo').addClass('has-error');
										}
										if(errors.errors["company"]){
											$('span.company').text(errors.errors["company"]).show();
											$('div.company').addClass('has-error');
										}
										if(errors.errors["stationNo"]){
											$('span.stationNo').text(errors.errors["stationNo"]).show();
											$('div.stationNo').addClass('has-error');
										}
										if(errors.errors["brand"]){
											$('span.brand').text(errors.errors["brand"]).show();
											$('div.brand').addClass('has-error');
										}
										if(errors.errors["specification"]){
											$('span.specification').text(errors.errors["specification"]).show();
											$('div.specification').addClass('has-error');
										}
										if(errors.errors["model"]){
											$('span.model').text(errors.errors["model"]).show();
											$('div.model').addClass('has-error');
										}
										if(errors.errors["itemType"]){
											$('span.itemType').text(errors.errors["itemType"]).show();
											$('div.itemType').addClass('has-error');
										}
										if(errors.errors["dateArrived"]){
											$('span.dateArrived').text(errors.errors["dateArrived"]).show();
											$('div.dateArrived').addClass('has-error');
										}
										},
										success: function(){
											swal('','New Item Added','success');
											$('form.addItem').trigger('reset');
											}
								});
						  }
				    });
				    

					this.on("sendingmultiple", function(data, xhr, formData) {
						formData.append("_token", $('[name=_token').val());
			            formData.append("unique_id", $("input#unique_id").val());
			            formData.append("itemNo", $("input#itemNo").val());
			            formData.append("brand", $("input#brand").val());
			            formData.append("model", $("input#model").val());
			            formData.append("company", $("input#company").val());
			            formData.append("stationNo", $("input#stationNo").val());
			            formData.append("specification", $("input#specification").val());
			            formData.append("itemType", $("input#itemType").val());
			            formData.append("dateArrived", $("input#dateArrived").val());
			           
			        });
			        
					this.on('error', function (file,response) {
						if(file.size > 2097152){
							this.RemoveFile(file);
							return false;
							}
						file.status = Dropzone.QUEUED;

						$(file.previewElement).find('.dz-error-message').text("An error occurred");
						
						});
					this.on("errormultiple", function(file, response, xhr) {
						if(file.size > 2097152){
							this.RemoveFile(file);
							return false;
							}
						file.status = Dropzone.QUEUED;
						$(file.previewElement).find('.dz-error-message').text("An error occurred");
						if(response.errors["unique_id"]){
							$('span.unique_id').text(response.errors["unique_id"]).show();
							$('div.unique_id').addClass('has-error');
						}
						if(response.errors["itemNo"]){
							$('span.itemNo').text(response.errors["itemNo"]).show();
							$('div.itemNo').addClass('has-error');
						}
						if(response.errors["company"]){
							$('span.company').text(response.errors["company"]).show();
							$('div.company').addClass('has-error');
						}
						if(response.errors["stationNo"]){
							$('span.stationNo').text(response.errors["stationNo"]).show();
							$('div.stationNo').addClass('has-error');
						}
						if(response.errors["brand"]){
							$('span.brand').text(response.errors["brand"]).show();
							$('div.brand').addClass('has-error');
						}
						if(response.errors["specification"]){
							$('span.specification').text(response.errors["specification"]).show();
							$('div.specification').addClass('has-error');
						}
						if(response.errors["model"]){
							$('span.model').text(response.errors["model"]).show();
							$('div.model').addClass('has-error');
						}
						if(response.errors["itemType"]){
							$('span.itemType').text(response.errors["itemType"]).show();
							$('div.itemType').addClass('has-error');
						}
						if(response.errors["dateArrived"]){
							$('span.dateArrived').text(response.errors["dateArrived"]).show();
							$('div.dateArrived').addClass('has-error');
						}
						createTicket.ladda('stop');
						
						});
					this.on("successmultiple", function () {
							
							
							$('div.topic').removeClass('has-error');
							$('div.subject').removeClass('has-error');
							$('div.summary').removeClass('has-error');
							createTicket.ladda('stop');
							swal({
								title : 'Success!',
								text : "New Item Added.",
								type : "success",
							}, function() {
								location.reload();
							});
							
							$('form.addItem').trigger('reset');

								
						
						
				      });

				    }
			};
		});
</script>
@endsection

