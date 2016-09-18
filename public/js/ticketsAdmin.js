/**
 * @author ITojt01 Luis Philip M.
 *         Castaneda*/

$(function() {

	$(document)
			.ready(
					function() {
						$('div#advancedSearch').toggle();

						$('.i-checks').iCheck({
							checkboxClass : 'icheckbox_square-green',
							radioClass : 'iradio_square-green',
						});
						$('[data-toggle="tooltip"]').tooltip({
							selector : "[data-toggle=tooltip]",
							container : "body"
						});
						$('table.agentPassword').dataTable({
							"bSort" : false,
							dom : '<"html5buttons">lTfgtip',
						});
						$('table.clientTable').dataTable({
							"bSort" : false,
							dom : '<"html5buttons">lTfgtip',
						});
						

						$('div.ticketsummernote')
								.summernote(
										{
											toolbar : [
													[
															'style',
															[
																	'bold',
																	'italic',
																	'underline',
																	'clear' ] ],
													[ 'fontname',
															[ 'fontname' ] ],
													[ 'fontsize',
															[ 'fontsize' ] ],
													[ 'color', [ 'color' ] ],
													[
															'para',
															[ 'ul', 'ol',
																	'paragraph' ] ],
													[ 'height', [ 'height' ] ] ]
										});
						$('div.ticketReplySummernote')
								.summernote(
										{
											toolbar : [
													[
															'style',
															[
																	'bold',
																	'italic',
																	'underline',
																	'clear' ] ],
													[ 'fontname',
															[ 'fontname' ] ],
													[ 'fontsize',
															[ 'fontsize' ] ],
													[ 'color', [ 'color' ] ],
													[
															'para',
															[ 'ul', 'ol',
																	'paragraph' ] ],
													[ 'height', [ 'height' ] ] ]
										});
						$('table.noSupport').dataTable({
							"bSort" : false,
						});
						
					

					});

	$("input.ticketReportCB").change(function() {
		$("input.ticketReport").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketOpenCB").change(function() {
		$("input.Open").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketPendingCB").change(function() {
		$("input.Pending").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketClosedCB").change(function() {
		$("input.Closed").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketUnresolvedCB").change(function() {
		$("input.Unresolved").prop('checked', $(this).prop("checked"));
	});

	$('.input-daterange').datepicker({

		format : 'yyyy-mm-dd',
	});
	$('input.dateSent').datepicker({
		format : 'yyyy-mm-dd',
	});

	$('input.dateClosed').datepicker({
		format : 'yyyy-mm-dd',
	});

	// ticket report
	$('button.advancedTicketSearch').click(
					function() {
						console.log($('form.advancedTicket').serialize());
						$
								.ajax(
										{
											type : "POST",
											url : "/admin/advancedSearch",
											data : $('form.advancedTicket')
													.serialize(),
										})
								.done(
										function(data) {

											$('tbody.ticketReport').empty();
											var html;
											var table = $('table.ticketReport').DataTable();
											table.clear();
											$.each(data.response,function(i, v) {

												if ((data.closed[i]['first_name'] == null)
														|| (data.closed[i]['closed_by'] == 0)) {
													data.closed[i]['first_name'] = '';
												}

												if ((data.closed[i]['last_name'] == null)
														|| (data.closed[i]['closed_by'] == 0)) {
													data.closed[i]['last_name'] = '';
												}

												if ((data.assigned[i]['first_name'] == null)
														|| (data.assigned[i]['assigned_support'] == 0)) {
													data.assigned[i]['first_name'] = '';
												}

												if ((data.assigned[i]['last_name'] == null)
														|| (data.assigned[i]['assigned_support'] == 0)) {
													data.assigned[i]['last_name'] = '';
												}
												
												var rowNode = table.row.add(['', v.id, v.description,
																               v.description, v.subject,v.ticket_status,
																               v.department, data.assigned[i]['first_name']+ " "+ data.assigned[i]['last_name'],
																               data.closed[i]['first_name'] +" "+ data.closed[i]['last_name'],
																               v.created_at, v.updated_at] ).draw();															
															});
										});
					});
	
	
	
	
	// end ticket report
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
					

	$("input.ticketSearch").keyup(
			function() {
				// split the current value of searchInput
				_this = this;
				// Show only matching TR, hide rest of them
				$.each($("tbody tr"), function() {
					if ($(this).text().toLowerCase().indexOf(
							$(_this).val().toLowerCase()) === -1)
						$(this).hide();
					else
						$(this).show();
				});
			});
	
	$('button.advancedSearch').click(function() {
		$('div#advancedSearch').slideToggle();
	});
	// create ticket


	// Add Topic
	$('button.addTopic').click(
					function(e) {
						$('span.addTopic').hide();
						$('div.form-group').removeClass(
								'has-error');
						
						$('label.text-danger').hide();
						e.preventDefault();

						$.ajax({
									type : "POST",
									url : "/admin/addTopic",
									data : $('form.addTopic').serialize(),
								})
								.done(
										function(data) {

											var msg = "";
											if (data.success == false) {
												if (data.errors['description']) {
													$('div.addTopic').addClass(
															'has-error');
													$('label.addTopic')
															.text(
																	'*'
																			+ data.errors['description'])
															.show();
												}
												if (data.errors['priority']) {
													$('div.priority').addClass(
															'has-error');
													$('label.priority')
															.text(
																	'*'
																			+ data.errors['priority'])
															.show();
												}

											} else {
												$('form.addTopic').trigger(
														'reset');
												var html;
												var table = $('table.ticket_topics').data('footable');
												

												html += "<tr id=" + data.response['topic_id'] + "><td class='text-center'><input class='topic' type='checkbox' name ="
																			+ data.response['topic_id']
																			+ " value="
																			+ data.response['topic_id']
																			+ " checked></td>"
																			+ "<td>"
																			+ data.response['description']
																			+ "</td>"
																			+ "<td class='text-center'>"
																			+ data.response['default_priority']
																			+ "</td>"
																			+ "<td><button type='button' class='btn btn-warning btn-xs editTopic' value="
																			+ data.response['topic_id']
																			+ ">Edit</button>"
																			+ "<button type='button' class='btn btn-danger btn-xs deleteTopic'  value="
																			+ data.response['topic_id']
																			+ ">Delete</button> </td></tr>";
																
												$('tbody.topics').append(html);
												$('table').trigger('footable_redraw');
												$('div#myModal').modal('hide');
								
												toastr.success('New Topic has been added.');

											}
										});

					});

	// Delete Topic
	$(document).on(
			'click',
			'button.deleteTopic',
			function() {
				var deleteTopic = $(this).val();
				console.log($('form.topic').serializeArray());

				swal({
					title : "Are you sure?",
					text : "Selected topic will be deleted. ",
					type : "warning",
					showCancelButton : true,
					confirmButtonColor : "#DD6B55",
					confirmButtonText : "Yes",
					closeOnConfirm : false,
					showLoaderOnConfirm : true,
					disableButtonsOnConfirm : true,
				}, function() {

					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : "DELETE",
						url : "/admin/deleteTopic",
						data : {
							deleteTopic : deleteTopic,
						},
					}).done(
							function(data) {
								$('button.deleteTopic[value=' + deleteTopic + ']').parents('tr').remove();
								$('table').trigger('footable_redraw');
								swal('Topics has been deleted', '', 'success');
							});
				});
			});
	// edit topic
	$(document).on('click', 'button.editTopic', function() {
		var editTopic = $(this).val();

		$('#editTopic').modal('show');
		$('div.spiner').show();
		$('form.editTopic').hide();
		$.ajax({
			type : 'GET',
			url : '/admin/topicInfo',
			data : {
				editTopic : editTopic
			},
		}).done(function(data) {
			$('input.editTopic_id').val(data.editTopic['topic_id']);
			$('input.editTopic').val(data.editTopic['description']);
			$('select.editPriority').val(data.editTopic['priority_level']);
			$('div.spiner').hide();
			$('form.editTopic').show();
		});
	});

	$(document).on('click','button.saveEditTopic',
			function() {
				$.ajax({
					type : 'PUT',
					url : '/admin/editTopic',
					data : $('form.editTopic').serialize(),
				}).done(
						function(data) {
							if (data.success = true) {
								toastr.options = {
									positionClass : "toast-top-center",
								};
								toastr.success('Data successfully updated.');
								$('#editTopic').modal('hide');

								$(
										'td.topicDescription'
												+ data.topic['topic_id'] + '')
										.text(data.topic['description']);
								$(
										'td.topicPriority'
												+ data.topic['topic_id'] + '')
										.text(data.topic['priority_level']);
							}

						});
			});

	// update topic selection
	var updateTopic = $('button.updateTopic').ladda();

	updateTopic.click(function(e) {
		var updateTopics = [ 'x' ];
		$('input.topic:checkbox:checked').each(function() {
			updateTopics.push($(this).val());
		});
		e.preventDefault();

		// Start loading
		updateTopic.ladda('start');

		$.ajax({
			headers : {
				'X-CSRF-Token' : $('input[name="_token"]').val()
			},
			type : "PUT",
			url : "/admin/updateSelection",
			data : {
				topics : updateTopics
			},
		}).done(function() {
			updateTopic.ladda('stop');
			swal('Success!', 'Ticket topics selections is updated', 'success');

		});
		// Timeout example
		// Do something in backend and then stop ladda
	});
	$("input.topicCB").change(function() {
		$("input.topic:checkbox").prop('checked', $(this).prop("checked"));
	});

	// Save Restriction
	var saveRestriction = $('button.saveRestriction').ladda();
	saveRestriction.click(function() {
		saveRestriction.ladda('start');
		$.ajax({
			type : "PUT",
			url : "/admin/updateRestriction",
			data : $('form.restriction').serialize(),
		}).done(function() {
			saveRestriction.ladda('stop');
			swal({
				title : "Updated!",
				text : "Restrictions has been updated.",
				type : "success",
				timer : 5000,
			});
		});
	});

	// Admin/CLient tab controls/actions
	$('a#clientPasswordResetLink').on(
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

	$('a#clientChangePassword').on('click',function(e) {
						var clientId = $(this).attr('value');
						
						swal(
								{
									title : "Client change password",
									text : "Enter Client's new Password",
									type : "input",
									inputType : "password",
									showCancelButton : true,
									closeOnConfirm : false,
									showLoaderOnConfirm : true,
									disableButtonsOnConfirm : true,
								},
								function(inputValue){
									$.ajax({
										headers : {'X-CSRF-Token' : $(
													'input[name="_token"]').val()},
										type : 'PUT',
										url : '/admin/changeClientPassword',
										data : {
												id : clientId,
												password : inputValue
												}
										}).done(function(
														data) {
													if (data.success != true) {
														console
																.log(data.errors);
														swal
																.showInputError(data.errors['password']);
														return false;
													} else {
														swal(
																'Success!',
																'Password has been changed',
																'success');
													}
												});
								
						});
						
					});

	$('a#changeClientStatus').on('click',function() {
						var clientId = $(this).attr('value');
						var clientStatus = $(this).attr('name');

						if (clientStatus == "Activated") {
							clientStatus = "Not Activated";
							$(this).attr('name', 'Not Activated');
						} else {
							clientStatus = "Activated";
							$(this).attr('name', 'Activated');
						}

					
									swal(
											{
												title : "Password Required!",
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
													$
															.ajax(
																	{
																		headers : {
																			'X-CSRF-Token' : $(
																					'input[name="_token"]')
																					.val()
																		},
																		type : 'post',
																		url : '/admin/verifyPassword',
																		data : {
																			password : inputValue
																		},
																	})
															.done(
																	function(
																			data) {
																		if (data == "true") {
																			$
																					.ajax(
																							{
																								headers : {
																									'X-CSRF-Token' : $(
																											'input[name="_token"]')
																											.val()
																								},
																								type : 'PUT',
																								url : '/admin/changeClientStatus',
																								data : {
																									id : clientId,
																									status : clientStatus
																								}
																							})
																					.done(
																							function(
																									data) {
																								if (data.success != true) {
																									swal
																											.showInputError(data.errors['id']);
																									return false;
																								} else {
																									swal(
																											{
																												title : 'Success!',
																												text : 'Status has been changed',
																												type : 'success'
																											},
																											function() {
																												$(
																														'td#'
																																+ clientId)
																														.text(
																																clientStatus);
																											});
																								}
																							});

																		} else {
																			swal
																					.showInputError("There's something wrong try again later");
																			return false;
																		}
																	});
												} else {
													swal
															.showInputError("You need to type in your password in order to do this!");
													return false;
												}
											});
								
					});
	
	$('a#clientDelete').click(function(){
		var clientId = $(this).attr('value');
		swal(
				{
					title : "Password Required!",
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
						$
								.ajax(
										{
											headers : {
												'X-CSRF-Token' : $(
														'input[name="_token"]')
														.val()
											},
											type : 'post',
											url : '/admin/verifyPassword',
											data : {
												password : inputValue
											},
										})
								.done(
										function(
												data) {
											if (data == "true") {
												$
														.ajax(
																{
																	headers : {
																		'X-CSRF-Token' : $(
																				'input[name="_token"]')
																				.val()
																	},
																	type : 'DELETE',
																	url : '/admin/clientDelete',
																	data : {
																		id : clientId,
																	}
																})
														.done(
																function(
																		data) {
																	if (data.success != true) {
																		swal('Oops...','Something went wrong','error');
																		return false;
																	} else {
																		swal(
																				{
																					title : 'Success!',
																					text : 'Status has been changed',
																					type : 'success'
																				},
																				function() {
																					$('button.clientDelete[value=' + clientId + ']').parents('tr').remove();
																				});
																	}
																});

											} else {
												swal.showInputError("There's something wrong try again later");
												return false;
											}
										});
					} else {
						swal
								.showInputError("You need to type in your password in order to do this!");
						return false;
					}
				});
		
	});
	// Admin Agent tab controls/actions
	$('#agentPasswordResetLink').on('click', function(e) {
		
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
				swal('','Password Reset Link has been sent!', 'success');
			});

		});

	});

	$('a#agentChangeUserType').on('click',
					function() {
						var agentId = $(this).attr('value');
						var agentUserType = $(this).attr('name');

						if (agentUserType == 'agent') {
							agentUserType = 'admin';
						} else {
							agentUserType = 'agent';
						}
						$(this).attr('name',agentUserType);
						$.ajax({
							headers : {
								'X-CSRF-Token' : $(
										'input[name="_token"]')
										.val()
							},
							type : 'PUT',
							url : '/admin/changeAgentUserType',
							data : {
								id : agentId,
								userType : agentUserType
							}
						})
				.done(
						function(
								data) {
							if (data.success != true) {
								swal('',data.errors['id'],'warning');
								return false;
							} else {
								swal(
										{
											title : 'Success!',
											text : 'User type has been changed',
											type : 'success'
										},
										function() {
											$('td#'+ agentId).text(agentUserType);
										});
							}
						});
					
					});

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
									url : '/admin/verifyPassword',
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
			url : "/admin/register",
			data : $('.agentForm').serialize(),
		}).done(function() {
			sendActivation();
		});

	}
	;
	function sendActivation() {
		$.ajax({
			type : "POST",
			url : "/admin/sendActivate",
			data : $('.agentForm').serialize(),
		}).done(function() {
			swal({
				title : 'Success',
				text : 'New user has been added',
				type : 'success'
			}, function() {
				window.location.href = "/admin/agents";
			});

		});
	}
	;

	var ticketReply = $('button.ticketReply').ladda();

	ticketReply.click(function(e) {

		$('label.email').hide();
		$('div.email').removeClass('has-error');

		$('input[type="hidden"].ticketReply').val(
				$('div.ticketReplySummernote').code());
		console.log($('form.ticketReply').serialize());
		ticketReply.ladda('start');

		$.ajax({
			type : "POST",
			url : "/admin/ticketReply",
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
							window.location.href = "/admin/tickets/"
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

	$('button.refreshBtn').on('click', function() {
		location.reload();
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
						$('div.ticketsummernote').code());

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

	// Dashboard Buttons

	$('button.topSupportWeek')
			.on(
					'click',
					function() {
						$
								.ajax({
									type : "GET",
									url : "/admin/topSupport",
									data : {
										topSupport : "Week"
									},
								})
								.done(
										function(data) {
											var html;
											console.log(data);
											$
													.each(
															data,
															function(index, v) {
																html += "<tr><td><span class='label label-info'>"
																		+ v.total
																		+ "</span></td><td>"
																		+ v.name
																		+ "</td></tr>";

															});

											$('tbody.topSupport').html(html);
										});
					});

	$('button.topSupportMonth')
			.on(
					'click',
					function() {
						$
								.ajax({
									type : "GET",
									url : "/admin/topSupport",
									data : {
										topSupport : "Month"
									},
								})
								.done(
										function(data) {
											var html;
											console.log(data);
											$
													.each(
															data,
															function(index, v) {
																html += "<tr><td><span class='label label-info'>"
																		+ v.total
																		+ "</span></td><td>"
																		+ v.name
																		+ "</td></tr>";

															});

											$('tbody.topSupport').html(html);
										});
					});

	$('button.topSupportYear')
			.on(
					'click',
					function() {
						$
								.ajax({
									type : "GET",
									url : "/admin/topSupport",
									data : {
										topSupport : "Year"
									},
								})
								.done(
										function(data) {
											var html;
											console.log(data);
											$.each(
															data,
															function(index, v) {
																html += "<tr><td><span class='label label-info'>"
																		+ v.total
																		+ "</span></td><td>"
																		+ v.name
																		+ "</td></tr>";

															});

											$('tbody.topSupport').html(html);
										});
					});

	// Assign Support section @ admin dashboard
	
	$('select.noSupport').change(function(){
		
		$.ajax({
			headers : {
				'X-CSRF-Token' : $('input[name="_token"]').val()
			},
			type : 'PUT',
			url : '/admin/assignSupport',
			data : {
				id : $(this).attr('name'),
				support : $(this).val()
			},
		}).done(function(data) {
			if(data.success == true){
				toastr.success('Ticket succesfully assigned to support');
			}else{
				toastr.error(data.errors['id']);
			}
			console.log(data);
		});
		
	});
	

	// Admin/Agent create client acount page
	$('button.refreshCaptcha').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/captcha-test",
		}).done(function(data) {
			$('img.captcha_img').attr('src', data);
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
			url : "/admin/createClient",
			data : $('form.clientForm').serialize(),
		}).done(
				function(data) {
					if (data.success == false) {

						registerClient.ladda('stop');
						if (data.errors['first_name']) {
							$('div.firstname').addClass('has-error');
							$('label.firstname').show().text(
									'*' + data.errors['first_name'][0]);
							;
						}
						if (data.errors['last_name']) {
							$('div.lastname').addClass('has-error');
							$('label.lastname').show().text(
									'*' + data.errors['last_name'][0]);
							;
						}
						if (data.errors['email']) {
							$('span.email').show().text(
									'*' + data.errors['email']);
							$('div.email').addClass('has-error');
						}
						if (data.errors['dept']) {
							$('span.department').show().text(
									'*' + data.errors['dept']);
							$('div.department').addClass('has-error');
						}
						if (data.errors['password']) {
							$('span.password').show().text(
									'*' + data.errors['password']);
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
						toastr.options = {
							positionClass : "toast-top-center",
						};
						toastr.success('Account succesfully created');
						registerClient.ladda('stop');
						$('form.clientForm').trigger("reset");
					}

				});
	});
	// Admin Edit Account Page
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
				url : '/admin/changePersonalInfo',
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
			url : '/admin/changePassword',
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
			url : '/admin/changeProfilePicture',
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
	
	// All tickets Advanced Search
	
	var allTicketSearch = $('button.allTicketSearch').ladda();
	
	allTicketSearch.click(function(){
		allTicketSearch.ladda('start');
		$.ajax({
			type : 'GET',
			url : '/admin/ticketSearch',
			data : $('form.advancedTicket').serialize(),
			success:function(data){
				var html;
				$.each(data.response,function(i,v) {
					html += "<tr><td>"+
								"<div class='input-group'>" +
									"<input type='checkbox' class='i-checks' name='id'" +
										"value=" + v.id +"> <span class='input-group-btn'>" +
										"<button data-toggle='dropdown'"+
											"class='btn btn-primary btn-xs dropdown-toggle'>" +
											"<span class='caret'></span>" +
										"</button>"+
										"<ul class='dropdown-menu'>" +
											"<li><a href=/admin/tickets/" + v.id +">View</a></li>" +
											"<li><a href='#' id='closeTicket' data-toggle='modal'" +
												"data-target='#closedBy' name=" + v.id +">Close</a></li>" +
										"</ul></span></div></td>";
					
					if(v.ticket_status == "Open"){
						html += "<td class='text-center'><span class='label label-success'>" 
							+ v.ticket_status + "</span></td>";
						
					}else if(v.ticket_status == "Pending"){
						html += "<td class='text-center'><span class='label label-warning'>"
							+ v.ticket_status +"</span></td>";
					}else if (v.ticket_status == "Closed"){
						html += "<td class='text-center'><span class='label label-primary'>"
							+ v.ticket_status + "</span></td>";
					}else{
						html += "<td class='text-center'><span class='label label-danger'>"
							+ v.ticket_status + "</span></td>";
					}
					
					html += "<td class='issue-info'><a href='/admin/tickets/'" + v.id +">" +
							"<span class='font-bold'>" + v.description + " - " + v.id + "</span>" +
							"<small>" + v.subject + "</small></a></td>"+
							"<td>" + v.sender_id + "</td>" +
							"<td class='text-center'><span class='label label-default'>" + 
							v.priority_level + "</span></td>" +
							"<td>" + v.updated_at + "</td></tr>";
					
					});
				$('tbody.allTickets').empty();
				allTicketSearch.ladda('stop');
				$('tbody.allTickets').append(html);
				
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				$('table.showTickets').trigger('footable_initialize');	
			},
			error : function(xhr, statusText, error) { 
				allTicketSearch.ladda('stop');
		        swal('No Results found','','info');
		    }
		});
		
	});
	// Department
	$('button.addDeparment').click(function(){
		$.ajax({
			type : 'POST',
			url : '/admin/addDepartment',
			data : $('form.addDepartment').serialize(),
		}).done(function(data){
			if(data.success == false){
				if(data.errors['department']){
					$('div.addDepartment').addClass('has-error');
					$('label.addDepartment').text(data.errors['department']).show();
				}
				if(data.errors['head']){
					$('div.addDepartmentHead').addClass('has-error');
					$('label.addDepartmentHead').text(data.errors['head']).show();
				}
				if(data.errors['description']){
					$('div.addDepartmentDescription').addClass('has-error');
					$('label.addDepartmentDescription').text(data.errors['description']).show();
				}
			}else if(data.success == true){
				swal('Added!','New Department is added','success');
				
				var html = "<tr><td>" + data.response['department']+ "</td>"+
							"<td>" + data.response['head'] + "</td>"+
							"<td>" + data.response['department_description'] + "</td></tr>";
				$('tbody.department').prepend(html);
			}
		});
	});
	
	$(document).on('click', 'button.editDepartment', function() {
		var department = $(this).val();

		$('div.spiner').show();
		$('form.editDepartment').hide();
		$.ajax({
			type : 'GET',
			url : '/admin/departmentInfo',
			data : {
				department : department
			},
		}).done(function(data) {
			$('input.editDepartmentId').val(data.department['id']);
			$('input.editDepartment').val(data.department['department']);
			$('input.editDepartmentHead').val(data.department['head']);
			$('textarea.editDepartmentDescription').val(data.department['department_description']);
			$('div.spiner').hide();
			$('form.editDepartment').show();
		});
	});
	
	$(document).on('click','button.saveEditDeparment',function() {
				$.ajax({
					type : 'PUT',
					url : '/admin/editDepartment',
					data : $('form.editDepartment').serialize(),
				}).done(
						function(data) {
							if (data.success = true) {
								$('form.editDepartment').trigger('reset');
								$('#editDepartment').modal('hide');
								toastr.success('Data successfully updated.');
								$('td.department'+ data.department['id']).text(data.department['department']);
								$('td.departmentHead'+ data.department['id']).text(data.department['head']);
								$('td.departmentDescription'+ data.department['id']).text(data.department['department_description']);
							}

						});
			});
	
});
