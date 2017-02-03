@extends('tickets.ticketclientlayout') 

@section('body')

<div class="col-md-offset-1 col-md-10">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h3 class="font-bold">Create Ticket</h3>
		</div>
		<div class="ibox-content ">

			<form class="m-t form-horizontal createTicket"
						action="/tickets/createTicket" role="form" method="POST"
						files="true" enctype="multipart/form-data">
				{!! csrf_field() !!}


				<div class="form-group topic">
					<label class="col-md-2 control-label">Topic: &nbsp; </label>
					<div class="col-md-10">
						<select name="topic" id="topic"class="form-control topic">
							<option value="" selected hidden>Choose a topic...</option>
							@foreach ($topics as $topic)
							<option value="{{$topic->topic_id}}">{{$topic->description}}</option>
							@endforeach
						</select>
						<span class="help-block m-b-none"><a href="#"data-toggle="modal"
						data-target="#myModal" >Click here to suggest a new topic</a></span>
					</div>
				</div>
				<div class="form-group priority">
							<label class="col-md-2 control-label"> Priority: &nbsp; </label>
							<div class="col-md-10">
								<select name="priority" class="form-control" id="priority">
									<option value=""></option>
									<option value="High">High</option>
									<option value="Normal">Normal</option>
									<option value="Low">Low</option>
								</select>

							</div>
						</div>
				
				<div class="form-group subject">
					<label class="col-md-2 control-label">Subject: &nbsp; </label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="subject"
							placeholder="Enter subject..." name="subject" required>

					</div>
				</div>
				

				<div class="form-group summary">



					<label class="col-md-2 control-label">Summary:</label>

					<div class="col-md-10">
						<input type="hidden" class="form-control ticketSummary" name="summary">

						<div class="ibox-content no-padding">
							<div class="summernote"></div>
						</div>


					</div>
				</div>
				<div class="form-group">
						<label class="col-md-2 control-label">Attachments:</label>

						<div class="col-md-10">

							<div id="attachment" class="dropzone">
								<div class="dropzone-previews"></div>
								<div class="dz-message" data-dz-message>
								<span>Click or Drop your attachments here.</span><br>
								<span>Image files only. </span><br>
								<span>2 MB per image limit. 10 MB total limit.</span></div>
							</div>
						</div>
					</div>
				
				<hr>
				
					<div class="text-right">
						<button type="button"
							class="btn btn-w-m btn-primary  create-ticket" id="create-ticket">Create</button>
						<a href="/tickets/" class="btn btn-w-m btn-danger btn-outline ">Cancel</a>

					</div>
				
			</form>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Suggest a topic</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal suggestTopic">
				{!! csrf_field() !!}
				
				<div class="form-group suggestTopic">
					<label class="col-md-2 control-label">Topic:</label>
					<div class="col-md-10">
					<input type="text" class="form-control" name="topic">
					<span class="help-block m-b-none text-danger suggestTopic"></span>
					</div>
					
				</div>

				</form>
				
					
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary suggestTopic" type="button">
					<strong>Save</strong>
				</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>
<<script type="text/javascript">
$(document).ready(function(){
			
	Dropzone.options.attachment = {
			autoDiscover : false,
			paramName: "attachment",
			url: "/tickets/createTicket",
			acceptedFiles: 'image/*',
			autoProcessQueue: false,
			addRemoveLinks: true,
			uploadMultiple: true,
			parallelUploads: 100,
			maxFilesize: 2,
			maxFiles: 100,
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
		    document.getElementById("create-ticket").addEventListener("click", function(e) {
			   
		    	e.preventDefault();
				e.stopPropagation();
				
				if (thisDropzone.getQueuedFiles().length > 0) {
					thisDropzone.processQueue();
					createTicket.ladda('start');
				  }
				  else {
					 
					createTicket.ladda('start');
					  
					  $('input.ticketSummary').val($('div.summernote').summernote('code'));
						
						$('div.topic').removeClass('has-error');
						$('div.subject').removeClass('has-error');
						$('div.summary').removeClass('has-error');
						e.preventDefault();

						$.ajax({
							type : "POST",
							url : "/tickets/createTicket",
							data : $('.createTicket').serialize(),
							error: function(data){
								var errors = data.responseJSON;					    
								var msg = "";
								$.each(errors.errors, function(k, v) {
									msg = v + "\n" + msg;
								});

								if (errors.errors['topic']) {
									$('div.topic').addClass('has-error');
								}
								if (errors.errors['subject']) {
									$('div.subject').addClass('has-error');
								}
								if (errors.errors['summary']) {
									$('div.summary').addClass('has-error');
								}
								createTicket.ladda('stop');
								swal("Oops...", msg, "warning");
								},
							success: function() {
								$('div.topic').removeClass('has-error');
								$('div.subject').removeClass('has-error');
								$('div.summary').removeClass('has-error');
								createTicket.ladda('stop');

								swal({
									title : "Success!",
									text : "Your ticket has been created.",
									type : "success",
								}, function() {
									window.location.href = "/tickets";
								});
						},
				  });
				
	        }
		        });
		 
			this.on("sendingmultiple", function(data, xhr, formData) {
				formData.append("_token", $('[name=_token').val());
	            formData.append("topic", jQuery("select#topic").val());
	            formData.append("priority", jQuery("select#priority").val());
	            formData.append("subject", jQuery("input#subject").val());
	            formData.append("summary", $('div.summernote').summernote('code'));
	            
	           
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
				var msg = "";
				
				$.each(response.errors, function(k, v) {
					msg = v + "\n" + msg;
				});
				if (response.errors['topic']) {
					$('div.topic').addClass('has-error');
				}
				if (response.errors['priority']) {
					$('div.priority').addClass('has-error');
				}
				if (response.errors['assigned_support']) {
					$('div.assigned_support').addClass('has-error');
				}
				if (response.errors['subject']) {
					$('div.subject').addClass('has-error');
				}
				if (response.errors['summary']) {
					$('div.summary').addClass('has-error');
				}
				createTicket.ladda('stop');
				swal("Oops...", msg, "warning");
				});
			this.on("successmultiple", function () {
					
					
					$('div.assigned_support').removeClass('has-error');
					$('div.topic').removeClass('has-error');
					$('div.subject').removeClass('has-error');
					$('div.summary').removeClass('has-error');
					createTicket.ladda('stop');

					swal({
						title : 'Success!',
						text : "Your ticket has been created. Do You want to create another ticket?",
						type : "success",
						showCancelButton: true,
						confirmButtonText: "Yes",
						closeOnConfirm: true
					}, function(isConfirm) {
						if(isConfirm){
							window.location.href = '/tickets/createTicket';
						}else{
							window.location.href = '/tickets';
						}
					});

						
				
				
		      });

		    }
			};
	
});
</script>
@endsection
