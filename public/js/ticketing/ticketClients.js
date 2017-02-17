/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function() {
		$('table.clientTable').dataTable({
			"bSort" : false,
			dom : '<"html5buttons">lTfgtip',
		});
	});

	$('a[href="#clientPasswordResetLink"]').on(
			'click',
			function(e) {
				$('input.email').val($(this).attr('value'));

				swal({
					title : "Are You Sure?",
					type : 'info',
					showCancelButton : true,
					closeOnConfirm : false,
					confirmButtonText : "Yes",
					showLoaderOnConfirm : true,
					disableButtonsOnConfirm : true,
				}, function() {
					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : "POST",
						url : "/tickets/forgotPassword",
						data : $('form.clientPassword').serialize(),
					}).done(
							function() {
								swal('Success',
										'Password Reset Link has been sent!',
										'success');
							});
				});
			});

	$('a[href="#clientChangePassword"]').on('click', function(e) {
		var clientId = $(this).attr('id');

		swal({
			title : "Client change password",
			text : "Enter Client's new Password",
			type : "input",
			inputType : "password",
			showCancelButton : true,
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function(inputValue) {
			$.ajax({
				headers : {
					'X-CSRF-Token' : $('input[name="_token"]').val()
				},
				type : 'PUT',
				url : '/admin/changeClientPassword',
				data : {
					id : clientId,
					password : inputValue
				}
			}).done(function(data) {
				if (data.success != true) {
					console.log(data.errors);
					swal.showInputError(data.errors['password']);
					return false;
				} else {
					toastr.success("Password has been changed");
					
				}
			});

		});

	});

	$('a[href="#clientChangeStatus"]').on('click', function() {
		var clientId = $(this).attr('id');
		var clientStatus = $(this).attr('value');

		if (clientStatus == "Activated") {
			clientStatus = "Not Activated";
			$(this).attr('value', 'Not Activated');
		} else {
			clientStatus = "Activated";
			$(this).attr('value', 'Activated');
		}

		$.ajax({
			headers : {
				'X-CSRF-Token' : $('input[name="_token"]').val()
			},
			type : 'PUT',
			url : '/admin/changeClientStatus',
			data : {
				id : clientId,
				status : clientStatus
			}
		}).done(function(data) {
			if (data.success != true) {
				swal('Ooops','Something went wrong.','error');
			} else {
				toastr.success("Status has been changed");
					$('td#' + clientId).text(clientStatus);
				
			}
		});
	});
	$('a[href="#clientDelete"').on('click',function(e) {
		const clientId = $(this).attr('id');
		const row = $(this);
		swal({
			title : "Are You Sure?",
			text: "This action can't be undone",
			type : 'warning',
			showCancelButton : true,
			closeOnConfirm : false,
			confirmButtonText : "Yes",
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function() {
			$.ajax({
				headers : {
					'X-CSRF-Token' : $('input[name="_token"]').val()
				},
				type : "DELETE",
				url : "/admin/clientDelete",
				data : {
					id : clientId
				},
				success : function(){
					var table = $('table').dataTable();
					table.row($(row).parents('tr')).remove().draw();
					swal('Success','Password Reset Link has been sent!','success');
				},
				error : function(){
					swal('Ooops','Something went wrong','error');
				}
			});
		});
	});
});