/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function() {
		$('table.agents').dataTable({
			"bSort" : false,
			dom : '<"html5buttons">lTfgtip',
		});
	});

	$('a[href="#agentPasswordResetLink"]').on('click', function(e) {

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
				type : "POST",
				url : "/admin/forgotPassword",
				data : $('form.agentPassword').serialize(),
			}).done(function() {
				swal('', 'Password Reset Link has been sent!', 'success');
			});

		});

	});

	$('a[href="#agentChangeUserType"]').on('click', function() {
		var agentId = $(this).attr('id');
		var agentUserType = $(this).attr('value');

		if (agentUserType == 'agent') {
			agentUserType = 'admin';
		} else {
			agentUserType = 'agent';
		}
		$(this).attr('value', agentUserType);
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
				type : 'PUT',
				url : '/admin/changeAgentUserType',
				data : {
					id : agentId,
					userType : agentUserType
				}
			}).done(function(data) {
				if (data.success != true) {
					swal('', data.errors['id'], 'warning');
					return false;
				} else {
					swal({
						title : 'Success!',
						text : 'User type has been changed',
						type : 'success'
					}, function() {
						$('td#' + agentId).text(agentUserType);
					});
				}
			});

		});

	});
	$('a[href="#agentDelete"').on('click',function(e) {
		var agentId = $(this).attr('id');
		var row = $(this);
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
				url : "/admin/agentDelete",
				data : {
					id : agentId
				},
				success : function(){
					var table = $('table').dataTable();
					table.row($(row).parents('tr')).remove().draw();
					swal('Success','Agent has been Deleted','success');
				},
				error : function(){
					swal('Ooops','Something went wrong','error');
				}
			});
		});
	});
});