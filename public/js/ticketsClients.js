/**
 * @author ITojt01 Luis Philip M. CastaÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â±eda
 */

$(function() {
	$(document).ready(function() {
		$('div.summernote').summernote({
			height: 125,
			toolbar : [['style', ['bold', 'italic', 'underline', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]]
		});
		$('[data-toggle="tooltip"]').tooltip();

	});

	/// Create Ticket
	
	var registerClient = $('button.registerClient').ladda();

	registerClient.click(function() {
		$('div.form-group').removeClass('has-error');
		$('span.text-danger').hide();
		$('label.text-danger').hide();

		registerClient.ladda('start');

		$.ajax({
			type : "POST",
			url : "/tickets/signUp",
			data : $('form.clientForm').serialize(),
		}).done(function(data) {
			if (data.success == false) {
				registerClient.ladda('stop');
				if (data.errors['first_name']) {
					$('div.firstname').addClass('has-error');
					$('label.firstname').show().text('*' + data.errors['first_name'][0]);;
				}
				if (data.errors['last_name']) {
					$('div.lastname').addClass('has-error');
					$('label.lastname').show().text('*' + data.errors['last_name'][0]);;
				}
				if (data.errors['email']) {
					$('span.email').show().text('*' + data.errors['email']);
					$('div.email').addClass('has-error');
				}
				if (data.errors['dept']) {
					$('span.department').show().text('*' + data.errors['dept']);
					$('div.department').addClass('has-error');
				}
				if (data.errors['password']) {
					$('span.password').show().text('*' + data.errors['password']);
					$('div.password').addClass('has-error');
				}
				if (data.errors['captcha']) {
					$('div.captcha').addClass('has-error');
					$('label.captcha').show();
					$.ajax({
						type : "GET",
						url : "/captcha-test",
					}).done(function(data) {
						$('img.captcha_img').attr('src', data);
					});
				}
			} else {
				window.location.href = "/tickets/signUpSuccess";
			}

		});
	});

	$('button.refreshCaptcha').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/captcha-test",
		}).done(function(data) {
			$('img.captcha_img').attr('src', data);
		});
	});

	//Client Edit Account Page
	var editPersonalInfo = $('button.editPersonalInfo').ladda();
	var editPassword = $('button.editPassword').ladda();

	editPersonalInfo.click(function() {
		editPersonalInfo.ladda('start');
		swal({
			title : 'Are you sure?',
			type : 'warning',
			showCancelButton : true,
			confirmButtonText : "Yes",
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function() {
			$.ajax({
				type : 'PUT',
				url : '/tickets/changePersonalInfo',
				data : $('form.personalInformation').serialize(),
			}).done(function(data) {
				if (data.success == true) {
					swal('Personal Info is updated', '', 'success');
					editPersonalInfo.ladda('stop');
				}
			});
		});
	});

	editPassword.click(function() {
		$('div.form-group').removeClass('has-error');
		$('label.text-danger').hide();
		editPassword.ladda('start');
		$.ajax({
			type : 'PUT',
			url : '/tickets/changePassword',
			data : $('form.clientChangePassword').serialize(),
		}).done(function(data) {
			if (data.success == true) {
				swal('Password successfully changed','', 'success');
				editPassword.ladda('stop');
				$('form.clientChangePassword').trigger('reset');
			} else {
				editPassword.ladda('stop');

				if (data.errors['oldPassword']) {
					$('div.oldPassword').addClass('has-error');
					$('label.oldPassword').text(data.errors['oldPassword']).show();
				}

				if (data.errors['password']) {
					$('div.newPassword').addClass('has-error');
					$('label.newPassword').text(data.errors['password']).show();
				}
			}
		});
	});
	
	$(document).on('click', 'tr.read', function() {
		window.document.location = $(this).data("href");
	});
	// Suggest Topic
	$('button.suggestTopic').click(function(){
		$.ajax({
			type: 'POST',
			url: '/tickets/suggestTopic',
			data: $('form.suggestTopic').serialize()
		}).done(function(data){
			if(data.success == false){
				if(data.errors['topic']){
					$('div.suggestTopic').addClass('has-error');
					$('span.suggestTopic').text(data.errors['topic']).show();
				}
			}else{
				
				swal({
					title : "Success",
					text : 'New topic has been added. Do you want to use this?',
					type : 'info',
					showCancelButton : true,
					confirmButtonText : "Yes",
					closeOnConfirm : true,
					
				}, function(isConfirm) {
					if(isConfirm){
						$('select.topic').append($('<option>', {
						    value: data.response['topic_id'],
						    text: data.response['description']
						}));
						
						$('select.topic').val(data.response['topic_id']).change();
						$('form.suggestTopic').trigger('reset');
						$('div#myModal').modal('hide');
					}else{
						$('div#myModal').modal('hide');
					}
					
				});
			}
			
		});
	});
	// Reply
	
	var ticketReply = $('button.ticketReply').ladda();

	ticketReply.click(function(e) {

		$('label.email').hide();
		$('div.email').removeClass('has-error');

		$('input[type="hidden"].ticketReply').val(
				$('div.summernote').code());
		console.log($('form.ticketReply').serialize());
		ticketReply.ladda('start');

		$.ajax({
			type : "POST",
			url : "/tickets/ticketReply",
			data : $('form.ticketReply').serialize(),
		}).done(
				function(data) {
					ticketReply.ladda('stop');
					if (data.success != false) {
						swal({
							title : 'Success',
							text : 'A reply has been added successfully',
							type : 'success'
						}, function() {
							window.location.href = "/tickets/"
									+ $('input.ticket_id').val();
						});

					} else {
						if (data.errors['email']) {
							$('label.email').text('*' + data.errors['email'])
									.show();
							$('div.email').addClass('has-error');
						}
						if (data.errors['message']) {
							swal('Oops...', data.errors['message'], 'warning');
						}
					}
				});

	});
});
