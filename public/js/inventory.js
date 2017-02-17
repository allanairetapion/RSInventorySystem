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
	
	
	
	$('.input-group.date.dateArrived').datepicker({
	    
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	
	//Add Item
	
	
	//Borrow Form
	
	
	
	

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