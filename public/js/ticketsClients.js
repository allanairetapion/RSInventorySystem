/**
 * @author ITojt01 Luis Philip M. CastaÃƒÂ±eda
 */

$(function() {
	$(document).ready(function() {
		$('div.summernote').summernote({
			toolbar : [['style', ['bold', 'italic', 'underline', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]]
		});
		$('[data-toggle="tooltip"]').tooltip();

	});

	/// Create Ticket
	$('button.create-ticket').click(function(e) {
		$('input[type="hidden"].topic').val($('div.summernote').code());
		console.log($('div.summernote').code());
		$('div.topic').removeClass('has-error');
		$('div.subject').removeClass('has-error');
		$('div.summary').removeClass('has-error');
		e.preventDefault();
		$.ajax({
			type : "POST",
			url : "/tickets/createTicket",
			data : $('.createTicket').serialize(),
		}).done(function(data) {
			var msg = "";
			if (data.response != "") {
				$.each(data.errors, function(k, v) {
					msg = v + "\n" + msg;
				});
				if (data.errors['topic']) {
					$('div.topic').addClass('has-error');
				}
				if (data.errors['subject']) {
					$('div.subject').addClass('has-error');
				}
				if (data.errors['summary']) {
					$('div.summary').addClass('has-error');
				}

				swal("Oops...", msg, "warning");
			} else {
				swal({
					title : "Success!",
					text : "Your ticket has been created.",
					type : "success",
				}, function() {
					window.location.href = "/tickets/landingPage";
				});

			}
		});
	});

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
});
