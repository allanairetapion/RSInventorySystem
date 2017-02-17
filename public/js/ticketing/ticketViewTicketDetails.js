/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function() {
		$.ajax({
			type : 'get',
			url : '/admin/ticketCount',
		}).done(function(data) {
			console.log(data);
			$('span.openTickets').text(data.openTickets);
			$('span.pendingTickets').text(data.pendingTickets);
			$('span.unresolvedTickets').text(data.overdueTickets);
			$('span.assignedTickets').text(data.assignedTickets);
			$('span.closedTickets').text(data.closedTickets);
		});

		
	});
	
	// View Ticket Details
	$('select.ticketStatus').change(
			function() {
				if ($(this).val() == "Closed"
						&& $('input#closing_report').val() == "") {
					$('#closedBy').modal('show');
				}
			});
	$('#assign').on('hidden.bs.modal', function() {
		if ($('select#assignTo').val() == "") {
			$('select.ticketStatus').prop('selectedIndex', 0);
		}
	});

	$('#closedBy').on('hidden.bs.modal', function() {

		$('select.ticketStatus').prop('selectedIndex', 0);

	});


	$('button.ticketSave')
			.on(
					'click',
					function() {
						console.log($('form.ticketStatus').serialize());
						if ($('select.ticketStatus').val() == "Pending"
								&& $('input.assignedTo').val() != "") {
							swal(
									{
										title : "Are you sure?",
										text : 'Ticket status will be change to "Pending" which means it will be removed from the assigned support',
										type : 'warning',
										showCancelButton : true,
										confirmButtonText : "Yes",
										closeOnConfirm : false,
										showLoaderOnConfirm : true,
										disableButtonsOnConfirm : true,
									}, function() {
										changeStatus();
									});

						} else {
							changeStatus();
						}

					});

	function changeStatus() {
		$.ajax({
			type : "PUT",
			url : "/admin/ticketStatus",
			data : $('form.ticketStatus').serialize(),
		}).done(function(data) {
			if (data.success == "true") {
				swal({
					title : 'Status Changed',
					text : 'Ticket details has been changed',
					type : 'success',
				}, function() {
					location.reload();
				});

			}
		});
	}
	;

	$('#forward').on('hidden.bs.modal', function() {
		$('form.forwardTo').trigger("reset");
	});

	$('button.saveForwardTo')
			.on(
					'click',
					function() {
						$
								.ajax({
									type : "PUT",
									url : "/admin/forwardTicket",
									data : $('form.forwardTo').serialize(),
								})
								.done(
										function(data) {
											if (data.success == true) {
												swal(
														{
															title : "Success!",
															text : "Ticket is now assigned to a new agent",
															type : "success",
														}, function() {
															location.reload();
														});
											} else {
												swal(
														'Oopss...',
														'Something went wrong please try again',
														'error');
											}
										});

					});

	$('button.closeTicket').on(
			'click',
			function() {
				$('input[type="hidden"]#closing_report').val(
						$('div.ticketsummernote').summernote('code'));

				$.ajax({
					type : "PUT",
					url : "/admin/closeTicket",
					data : $('form.closeTicket').serialize(),
				}).done(function(data) {
					if (data.success == true) {
						swal({
							title : "Success!",
							text : "Ticket status has been changed",
							type : "success",
						}, function() {
							location.reload();
						});
					} else {
						toastr.options = {
							positionClass : "toast-top-center",
						};
						toastr.error(data.errors['closing_report']);
					}
				});

			});
	
	$('button.ticketDelete').on('click',function() {
						var tickets = [ 'x' ];
						$('input:checkbox:checked').each(function() {
							tickets.push($(this).val());
						});
						if (tickets[1] == '' || tickets[1] == null) {
							swal('Ooops...', "You haven't selected any ticket",
									'info');
							return false;
						}

						console.log($('form.selectedTickets').serializeArray());
						swal({
							title : 'Are you sure?',
							text : "This Action can't be undone",
							type : 'warning',
							showCancelButton : true,
							showCancelButton : true,
							closeOnConfirm : false,
							showLoaderOnConfirm : true,
							disableButtonsOnConfirm : true,
						}, function() {
							

							$.ajax({
									headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
									type : "DELETE",
									url : "/admin/deleteTicket",
									data : {tickets : tickets},
									}).done(function(data) {
											swal({
													title : "Deleted",
													text : "Tickets has been deleted",
													type : "success",
												},function() {
													$('input:checkbox:checked').each(function() {
															$(this).parents('tr').remove();
												});
									});
							
						});

					});
	});

});