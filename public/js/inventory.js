/**
 * 
 */

$(function() {
	$(document).ready(function(){
		$('.input-group.date.dateBroken').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		

	$('.i-checks').iCheck({
		checkboxClass : 'icheckbox_square-green',
		radioClass : 'iradio_square-green',
	});
	
	$('a#exportExcel').click(function(){
		$('table').table2excel({
		
		    exclude: ".noExl",
		    name: "Worksheet Name",
		    filename: "SomeFile" //do not include extension
		  }); 
		});
	
	$('a#exportCSV').click(function(){
		$("table").tableToCSV();
		});
	
	});

	
	$('button.advancedSearch').click(function() {
		$('div#advancedSearch').slideToggle();
	});
	$('.input-group.date.dateArrived').datepicker({
	    
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	
	//Add Item
	
	
	//Borrow Form
	
	
	
	
	// create agent
	$('button.add-account').click(
			function(e) {
				$('div.form-groups').removeClass('has-error');
				$('span.text-danger').hide();
				$('label.text-danger').hide();
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "/checkEmail",
					data : $('.agentForm').serialize(),

				}).done(
						function(data) {
							if (data.response != "") {
								console.log(data.errors);
								if (data.errors['email']) {
									$('div.email').addClass('has-error');
									$('span.email').text(
											'*' + data.errors['email']).show();
								}
								if (data.errors['firstname']) {
									$('div.fname').addClass('has-error');
									$('label.fname').text(
											'*' + data.errors['firstname'][0])
											.show();
								}
								if (data.errors['lastname']) {
									$('div.lname').addClass('has-error');
									$('label.lname').text(
											'*' + data.errors['lastname'][0])
											.show();
								}
								if (data.errors['user_type']) {
									$('div.usertype').addClass('has-error');
									$('span.usertype').text(
											'*' + data.errors['user_type'])
											.show();
								}

							} else {
								// Validation Success Tell user to input his/her
								// password to continue/confirm adding
								console.log($('.agentForm').serialize());
								validateSuccess();
							}

						});
			});

	function validateSuccess() {
		swal(
				{
					title : "Are you sure you want to create new agent?",
					text : "If you are sure, Please enter your password.",
					type : "input",
					inputType : "password",
					showCancelButton : true,
					closeOnConfirm : false,
					showLoaderOnConfirm : true,
					disableButtonsOnConfirm : true,
				},
				function(inputValue) {
					if (inputValue != "") {
						$.ajax(
								{
									headers : {
										'X-CSRF-Token' : $(
												'input[name="_token"]').val()
									},
									type : 'post',
									url : '/inventory/verifyPassword',
									data : {
										password : inputValue
									},
								}).done(function(data) {
							if (data.success == true) {

								addNew();
							} else {
								swal.showInputError("Wrong Password");
								return false;
							}
						});
					} else {
						swal
								.showInputError("You need to type in your password in order to do this!");
						return false;
					}
				});
	}
	;
	function addNew() {
		$.ajax({
			type : "POST",
			url : "/inventory/register",
			data : $('form.agentForm').serialize(),
		}).done(function() {
			sendActivation();
		});

	}
	;
	function sendActivation() {
		$.ajax({
			type : "POST",
			url : "/admin/sendActivate",
			data : $('form.agentForm').serialize(),
			success: function(){
				swal({
					title : 'Success',
					text : 'New user has been added',
					type : 'success'
				}, function() {
					window.location.href = "/inventory/agents";
				});
			},
			error: function () {
				sendActivation();
			},
		});
	}
	;
	var editPersonalInfo = $('button.editPersonalInfo').ladda();
	var editPassword = $('button.editPassword').ladda();
	var editProfilePicture = $('button.editProfilePicture').ladda();
	editPersonalInfo.click(function(e) {
		e.preventDefault();
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
				url : '/inventory/changePersonalInfo',
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
			url : '/inventory/changePassword',
			data : $('form.adminChangePassword').serialize(),
		}).done(
				function(data) {
					if (data.success == true) {
						swal('Password successfully changed', '', 'success');
						editPassword.ladda('stop');
						$('form.adminChangePassword').trigger('reset');
					} else {
						editPassword.ladda('stop');

						if (data.errors['oldPassword']) {
							$('div.oldPassword').addClass('has-error');
							$('label.oldPassword').text(
									data.errors['oldPassword']).show();
						}

						if (data.errors['password']) {
							$('div.newPassword').addClass('has-error');
							$('label.newPassword')
									.text(data.errors['password']).show();
						}
					}
				});
	});
	
	editProfilePicture.click(function(){
		var $image = $(".image-crop > img");
		$('input.editProfilePicture').val($image.cropper("getDataURL"));
		console.log($('form.editProfilePicture').serialize());
		$.ajax({
			type : 'PUT',
			url : '/inventory/changeProfilePicture',
			data : $('form.editProfilePicture').serialize(),
			success: function(data){
				if(data.success == true){
					swal({
						title: '',
						text: 'Success',
						type: 'success',
					},function(){
						$('div#uploadPicture').modal('hide');
						$('img#profilePicture').attr('src',$image.cropper("getDataURL"));
					});
					
					
				}
			}
		});
	});
});