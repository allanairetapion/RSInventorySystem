/**
 * @author ITojt01 Luis Philip M. Castañeda
 */

$(function() {

	$(document).ready(function() {
		$('div#demo').toggle();
		$('span.addTopic').hide();
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

		$('div.ticketsummernote').summernote({
			toolbar : [['style', ['bold', 'italic', 'underline', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]]
		});
		$('div.ticketReplySummernote').summernote({
			toolbar : [['style', ['bold', 'italic', 'underline', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]]
		});
		$('table.noSupport').dataTable({
			"bSort" : false,
		});
		$('table.ticketReport').dataTable({
			"bSort" : false,
			dom : '<"html5buttons"B>',
			buttons : [{
				extend : 'copy'
			}, {
				extend : 'csv'
			}, {
				extend : 'excel',
				title : 'Ticket Report'
			}, {
				extend : 'pdf',
				title : 'Ticket Report'
			}, {
				extend : 'print',
				customize : function(win) {
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
				}
			}]

		});

	});
	$("input.ticketReportCB").change(function() {
		$("input#reportTicket").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketOpenCB").change(function() {
		$("input#openTicket").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketPendingCB").change(function() {
		$("input#pendingTicket").prop('checked', $(this).prop("checked"));
	});
	$("input.ticketClosedCB").change(function() {
		$("input#closedTicket").prop('checked', $(this).prop("checked"));
	});

	$('button.advancedSearch').click(function() {
		$('div#demo').slideToggle();
	});

	$('input.dateSent').datepicker({
		format : 'yyyy-mm-dd',
	});

	$('input.dateClosed').datepicker({
		format : 'yyyy-mm-dd',
	});

	$('button.advancedTicketReset').click(function() {
		$('select').val("");
		$('input.dateSent').val("");
		$('input.dateClosed').val("");
		$('input.agentSent').val("");
		$('input.agentClosed').val("");
		$('input.ticketSearch').val("");
		console.log($('form.advancedTicket').serialize());
		$.ajax({
			type : "POST",
			url : "/admin/advancedSearch",
			data : $('form.advancedTicket').serialize(),
		}).done(function(data) {

			$('div.pagination').show();
			$('tbody.ticketReport').empty();
			var html;

			$.each(data.response, function(i, v) {
				if (i == 15) {
					return false;
				}
				if(data.closed_by[i]['first_name'] == null){
					data.closed_by[i]['first_name'] = '';
				}
				
				if(data.closed_by[i]['last_name'] == null){
					data.closed_by[i]['last_name'] = '';
				}
				
				
				if (v.ticket_status == "Open") {
					html += "<tr class='bg-primary'  id='" + v.id + "'>";
				} else if (v.ticket_status == "Pending") {
					html += "<tr style='background-color: #F2F256;' id='" + v.id + "'>";
				} else if (v.ticket_status == "Closed") {
					html += "<tr class='navy-bg' id='" + v.id + "'>";
				}

				if (v.first_name == null) {
					v.first_name = "";
				}
				if (v.last_name == null) {
					v.last_name = "";
				}
				if (v.closed_at == null) {
					v.closed_at = "";
				}
				html += "<td><input type='checkbox' value=" + v.id + "> </td><td>" + v.id + "</td><td>" + v.sender + "</td>" + 
				"<td>" + v.sender_id + "</td><td>" + v.description + "</td><td>" + v.subject + "</td><td>" + v.ticket_status + 
				"</td>" + "<td>" + v.department + "</td><td>" + v.first_name + " " + v.last_name + "</td><td>" 
				+ data.closed_by[i]['first_name'] + " " + data.closed_by[i]['last_name'] + "</td><td>" + v.created_at + "</td>" + "<td>" + v.closed_at + "</td></tr>";
			});
			$('tbody.ticketReport').html(html);

			$('.i-checks').iCheck({
				checkboxClass : 'icheckbox_square-green',
				radioClass : 'iradio_square-green',
			});

		});
	});

	$('button.advancedTicketSearch').click(function() {
		console.log($('form.advancedTicket').serialize());
		$.ajax({
			type : "POST",
			url : "/admin/advancedSearch",
			data : $('form.advancedTicket').serialize(),
		}).done(function(data) {

			$('div.pagination').hide();
			$('tbody.ticketReport').empty();
			var html;

			$.each(data.response, function(i, v) {
				if(data.closed_by[i]['first_name'] == null){
					data.closed_by[i]['first_name'] = '';
				}
				
				if(data.closed_by[i]['last_name'] == null){
					data.closed_by[i]['last_name'] = '';
				}
				
				if (v.ticket_status == "Open") {
					html += "<tr class='bg-primary'  id='" + v.id + "'>";
				} else if (v.ticket_status == "Pending") {
					html += "<tr style='background-color: #F2F256;' id='" + v.id + "'>";
				} else if (v.ticket_status == "Closed") {
					html += "<tr class='navy-bg' id='" + v.id + "'>";
				}

				if (v.first_name == null) {
					v.first_name = "";
				}
				if (v.last_name == null) {
					v.last_name = "";
				}
				if (v.closed_at == null) {
					v.closed_at = "";
				}
				html += "<td><input type='checkbox' value=" + v.id + "> </td><td>" + v.id + "</td><td>" + v.sender + "</td>" + "<td>" + 
				v.sender_id + "</td><td>" + v.description + "</td><td>" + v.subject + "</td><td>" + v.ticket_status + "</td>" 
				+ "<td>" + v.department + "</td><td>" + v.first_name + " " + v.last_name + "</td><td>" + 
				data.closed_by[i]['first_name'] + " " + data.closed_by[i]['last_name']+"</td><td>" 
				+ v.created_at + "</td>" + "<td>" + v.closed_at + "</td></tr>";
			});
			$('tbody.ticketReport').html(html);
			$('.i-checks').iCheck({
				checkboxClass : 'icheckbox_square-green',
				radioClass : 'iradio_square-green',
			});

		});
	});
	$('button.ticketDelete').on('click', function() {
		var tickets = ['x'];
		$('input:checkbox:checked').each(function() {
			tickets.push($(this).val());

		});
		console.log($('form.selectedTickets').serializeArray());
		swal({
			title : "Are you sure?",
			text : "This action can't be undone",
			type : "warning",
			showCancelButton : true,
			closeOnConfirm : false,
			confirmButtonText : "Yes",
		}, function() {
			swal({
				title : "Password Required!",
				text : "If you are sure, Please enter your password.",
				type : "input",
				inputType : "password",
				showCancelButton : true,
				closeOnConfirm : false,
				showLoaderOnConfirm : true,
				disableButtonsOnConfirm : true,
			}, function(inputValue) {
				if (inputValue != "") {
					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : 'post',
						url : '/checkPassword',
						data : {
							password : inputValue
						},
					}).done(function(data) {
						if (data == "true") {
							$.ajax({
								headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
								type : "DELETE",
								url : "/admin/deleteTicket",
								data : {tickets : tickets},
							}).done(function(data) {
								
								swal({
									title : "Deleted",
									text : "Tickets has been deleted",
									type : "success",
								}, function() {
									location.reload();
								});

							});
						} else {
							swal.showInputError("Wrong Password");
							return false;
						}
					});
				} else {
					swal.showInputError("You need to type in your password in order to do this!");
					return false;
				}
			});

		});
	});

	$("input.ticketSearch").keyup(function() {
		//split the current value of searchInput
		_this = this;
		// Show only matching TR, hide rest of them
		$.each($("tbody tr"), function() {
			if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
				$(this).hide();
			else
				$(this).show();
		});
	});
//create ticket
	var createTicket = $('button.create-ticket').ladda();

	createTicket.click(function(e) {
		
		e.preventDefault();

		createTicket.ladda('start');

		$('input[type="hidden"].topic').val($('div.ticketsummernote').code());
		console.log($('div.ticketsummernote').code());
		$('div.topic').removeClass('has-error');
		$('div.subject').removeClass('has-error');
		$('div.summary').removeClass('has-error');
		e.preventDefault();

		
		$.ajax({
			type : "POST",
			url : "/admin/createTicket",
			data : $('.createTicket').serialize(),
		}).done(function(data) {
			console.log($('.createTicket').serialize());
			var msg = "";
			if (data.response != "") {
				$.each(data.errors, function(k, v) {
					msg = v + "\n" + msg;
				});

				if (data.errors['topic']) {
					$('div.topic').addClass('has-error');
				}
				if (data.errors['assigned_support']) {
					$('div.assigned_support').addClass('has-error');
				}
				if (data.errors['subject']) {
					$('div.subject').addClass('has-error');
				}
				if (data.errors['summary']) {
					$('div.summary').addClass('has-error');
				}
				createTicket.ladda('stop');
				swal("Oops...", msg, "warning");
			} else {
				createTicket.ladda('stop');
				$('.createTicket').trigger("reset");
				$('div.ticketsummernote').code('');
				swal("Succes!", "Your ticket has been created.", "success");
				$('div.assigned_support').removeClass('has-error');
				$('div.topic').removeClass('has-error');
				$('div.subject').removeClass('has-error');
				$('div.summary').removeClass('has-error');
			}
		});
	});

	//Add Topic
	$('button.addTopic').click(function(e) {
		$('div.addTopic').removeClass('has-error');
		$('label.addTopic').hide();
		e.preventDefault();

		$.ajax({
			type : "POST",
			url : "/admin/addTopic",
			data : $('form.addTopic').serialize(),
		}).done(function(data) {

			var msg = "";
			if (data.success == false) {
				
				$('div.addTopic').addClass('has-error');
				$('label.addTopic').text('*'+data.errors['description']).show();
				
				
			} else {
				$('form.addTopic').trigger('reset');
				var html;

				$.each(data.response, function(i, v) {
					if(i == 2){
						return false;
					}
					if (v.status == 1) {
						html += "<tr><td class='text-center'><input class='topic' type='checkbox' name =" + v.id + " value=" + v.id + " checked></td>";
					} else {
						html += "<tr><td class='text-center'><input class='topic' type='checkbox' name =" + v.id + " value=" + v.id + "></td>";
					}

					html += "<td class='text-center'>" + v.description + "</td></tr>";
				});
				$('tbody.topics').append(html);
				$('span.addTopic').hide();
				$('div.addTopic').removeClass('has-error');
				$('div.addTopic').removeClass('has-feedback');

				toastr.options = {
					positionClass : "toast-top-center",
				};
				toastr.success('New Topic has been added.');

			}
		});

	});

	//Delete Topic
	$('button.deleteTopic').click(function(e) {
		var deleteTopic = ['x'];
		$('input.topic:checkbox:checked').each(function() {
			deleteTopic.push($(this).val());

		});

		console.log($('form.topic').serializeArray());
		e.preventDefault();
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
					topics : deleteTopic
				},
			}).done(function(data) {

				$('input.topic:checkbox:checked').each(function() {
					$(this).parents('tr').hide();
				});

				swal('Topics has been deleted', '', 'success');
			});
		});
	});

	//update topic selection
	var updateTopic = $('button.updateTopic').ladda();

	updateTopic.click(function(e) {
		var updateTopics = ['x'];
		$('input.topic:checkbox:checked').each(function() {
			updateTopics.push($(this).val());
		});
		e.preventDefault();
		$('span.addTopic').hide();
		$('div.addTopic').removeClass('has-error');
		$('div.addTopic').removeClass('has-feedback');
		// Start loading
		updateTopic.ladda('start');

		$.ajax({
			headers : {
					'X-CSRF-Token' : $('input[name="_token"]').val()
				},
			type : "PUT",
			url : "/admin/updateSelection",
			data : {topics : updateTopics},
		}).done(function() {
			updateTopic.ladda('stop');
			toastr.options = {
				positionClass : "toast-top-center",
			};
			toastr.success('Topic selection has been updated.');

		});
		// Timeout example
		// Do something in backend and then stop ladda
	});
	$("input.topicCB").change(function() {
		$("input.topic:checkbox").prop('checked', $(this).prop("checked"));
	});

	//Save Restriction
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

	//Admin/CLient tab controls/actions
	$('button.clientPasswordResetLink').on('click', function(e) {
		$('input.email').val($(this).val());

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
			}).done(function() {
				swal('Success', 'Password Reset Link has been sent!', 'success');
			});
		});
	});

	$('button.clientChangePassword').on('click', function(e) {
		var clientId = $(this).val();
		swal({
			title : "Password Required!",
			text : "Please enter your password to continue",
			type : "input",
			inputType : "password",
			showCancelButton : true,
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function(inputValue) {
			if (inputValue != "") {
				$.ajax({
					headers : {
						'X-CSRF-Token' : $('input[name="_token"]').val()
					},
					type : 'post',
					url : '/checkPassword',
					data : {
						password : inputValue
					},
				}).done(function(data) {
					if (data == "true") {
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
							if (inputValue != "") {
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
										swal('Success!', 'Password has been changed', 'success');
									}
								});

							} else {
								swal.showInputError("Please enter a password");
								return false;
							}
						});

					} else {
						swal.showInputError("Wrong Password");
						return false;
					}
				});
			} else {
				swal.showInputError("You need to type in your password in order to do this!");
				return false;
			}
		});
	});

	$('button.changeClientStatus').on('click', function() {
		var clientId = $(this).val();
		var clientStatus = $(this).attr('name');
		if (clientStatus == "Activated") {
			clientStatus = "Not Activated";
		} else {
			clientStatus = "Activated";
		}

		swal({
			title : 'Are you sure?',
			type : 'warning',
			showCancelButton : true,
			closeOnConfirm : false,
			confirmButtonText : "Yes",

		}, function() {
			swal({
				title : "Password Required!",
				text : "If you are sure, Please enter your password.",
				type : "input",
				inputType : "password",
				showCancelButton : true,
				closeOnConfirm : false,
				showLoaderOnConfirm : true,
				disableButtonsOnConfirm : true,
			}, function(inputValue) {
				if (inputValue != "") {
					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : 'post',
						url : '/checkPassword',
						data : {
							password : inputValue
						},
					}).done(function(data) {
						if (data == "true") {
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
									swal.showInputError(data.errors['id']);
									return false;
								} else {
									swal({
										title : 'Success!',
										text : 'Status has been changed',
										type : 'success'
									}, function() {
										$('td#' + clientId).text(clientStatus);
									});
								}
							});

						} else {
							swal.showInputError("There's something wrong try again later");
							return false;
						}
					});
				} else {
					swal.showInputError("You need to type in your password in order to do this!");
					return false;
				}
			});
		});
	});
	//Admin Agent tab controls/actions
	$('button.agentPasswordResetLink').on('click', function(e) {
		$('input.email').val($(this).val());
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
				url : "/admin/forgotPassword",
				data : $('form.agentPassword').serialize(),
			}).done(function() {
				swal('Password Reset Link has been sent!', '', 'success');
			});

		});

	});

	$('button.agentChangeUserType').on('click', function() {
		var agentId = $(this).val();
		var agentUserType = $(this).attr('name');

		if (agentUserType == 'agent') {
			agentUserType = 'admin';
		} else {
			agentUserType = 'agent';
		}

		swal({
			title : "Password Required!",
			text : "Please enter your password to continue",
			type : "input",
			inputType : "password",
			showCancelButton : true,
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function(inputValue) {
			if (inputValue != "") {
				$.ajax({
					headers : {
						'X-CSRF-Token' : $('input[name="_token"]').val()
					},
					type : 'post',
					url : '/checkPassword',
					data : {
						password : inputValue
					},
				}).done(function(data) {
					if (data == "true") {
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
								swal.showInputError(data.errors['id']);
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
					} else {
						swal.showInputError("There's something wrong try again later");
						return false;
					}

				});
			} else {
				swal.showInputError("You need to type in your password in order to do this!");
				return false;
			}
		});
	});

	// create agent
	$('button.add-account').click(function(e) {
		$('div.form-groups').removeClass('has-error');
		$('span.text-danger').hide();
		$('label.text-danger').hide();
		e.preventDefault();
		$.ajax({
			type : "POST",
			url : "/checkEmail",
			data : $('.agentForm').serialize(),

		}).done(function(data) {
			if (data.response != "") {
				console.log(data.errors);
				if (data.errors['email']) {
					$('div.email').addClass('has-error');
					$('span.email').text('*' + data.errors['email']).show();
				}
				if (data.errors['firstname']) {
					$('div.fname').addClass('has-error');
					$('label.fname').text('*' + data.errors['firstname'][0]).show();
				}
				if (data.errors['lastname']) {
					$('div.lname').addClass('has-error');
					$('label.lname').text('*' + data.errors['lastname'][0]).show();
				}
				if (data.errors['user_type']) {
					$('div.usertype').addClass('has-error');
					$('span.usertype').text('*' + data.errors['user_type']).show();
				}

			} else {
				//Validation Success Tell user to input his/her password to continue/confirm adding
				console.log($('.agentForm').serialize());
				validateSuccess();
			}

		});
	});

	function validateSuccess() {
		swal({
			title : "Are you sure you want to create new agent?",
			text : "If you are sure, Please enter your password.",
			type : "input",
			inputType : "password",
			showCancelButton : true,
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function(inputValue) {
			if (inputValue != "") {
				$.ajax({
					headers : {
						'X-CSRF-Token' : $('input[name="_token"]').val()
					},
					type : 'post',
					url : '/checkPassword',
					data : {
						password : inputValue
					},
				}).done(function(data) {
					if (data == "true") {

						addNew();
					} else {
						swal.showInputError("Wrong Password");
						return false;
					}
				});
			} else {
				swal.showInputError("You need to type in your password in order to do this!");
				return false;
			}
		});
	};
	function addNew() {
		$.ajax({
			type : "POST",
			url : "/admin/register",
			data : $('.agentForm').serialize(),
		}).done(function() {
			sendActivation();
		});

	};
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
				location.reload();
			});

		});
	};

	var ticketReply = $('button.ticketReply').ladda();
	ticketReply.click(function(e) {
		console.log($('form.ticketReply').serialize());
		$('input[type="hidden"].ticketReply').val($('div.ticketReplySummernote').code());

		ticketReply.ladda('start');

		$.ajax({
			type : "POST",
			url : "/admin/ticketReply",
			data : $('form.ticketReply').serialize(),
		}).done(function() {
			ticketReply.ladda('stop');
			swal('Success', 'An email has been sent to ' + $('input.email').val(), 'success');
		});

	});

	$('button.refreshBtn').on('click', function() {
		location.reload();
	});

	//View Ticket Details
	$('select.ticketStatus').change(function() {
		if ($(this).val() == "Open" && $('input.assignedTo').val() == "") {
			$('#assign').modal('show');
		} else if ($(this).val() == "Closed" && $('input#closing_report').val() == "") {
			$('#closedBy').modal('show');
		}
	});

	$('button.cancelOpen').on('click', function() {
		$('select.ticketStatus').prop('selectedIndex', 0);
	});

	$('#assign').on('hidden.bs.modal', function() {
		if ($('select#assignTo').val() == "") {
			$('select.ticketStatus').prop('selectedIndex', 0);
		}
	});

	$('#closedBy').on('hidden.bs.modal', function() {

		$('select.ticketStatus').prop('selectedIndex', 0);

	});

	$('button.saveOpen').click(function() {

		if ($('select#assignTo').val() == "") {
			toastr.options = {
				positionClass : "toast-top-center",
			};
			toastr.error('Please choose an agent to continue');
			$('div.assignAgent').addClass('has-error');
		} else {
			$('input.assignedTo').val($('select#assignTo').val());
			$('div.assignAgent').removeClass('has-error');
			$('#assign').modal('toggle');
		}

		console.log($('input.assignedTo').val());
	});

	$('button.ticketSave').on('click', function() {
		console.log($('form.ticketStatus').serialize());
		if ($('select.ticketStatus').val() == "Pending" && $('input.assignedTo').val() != "") {
			swal({
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
	};

	$('#forward').on('hidden.bs.modal', function() {
		$('form.forwardTo').trigger("reset");
	});

	$('button.saveForwardTo').on('click', function() {
		$.ajax({
			type : "PUT",
			url : "/admin/forwardTicket",
			data : $('form.forwardTo').serialize(),
		}).done(function(data) {
			if (data.success == true) {
				swal({
					title : "Success!",
					text : "Ticket is now assigned to a new agent",
					type : "success",
				}, function() {
					location.reload();
				});
			} else {
				swal('Oopss...', 'Something went wrong please try again', 'error');
			}
		});

	});

	$('button.printMe').on('click', function() {
		window.print();
	});

	$('button.closeTicket').on('click', function() {
		$('input[type="hidden"]#closing_report').val($('div.ticketsummernote').code());

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

	$('button.deleteViewedTicket').on('click', function() {
		swal({
			title : "Are you sure?",
			text : "This action can't be undone",
			type : "warning",
			showCancelButton : true,
			confirmButtonText : "Yes",
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function() {
			$.ajax({
				type : "DELETE",
				url : "/admin/deleteViewedTicket",
				data : $('form.ticketStatus').serialize(),
			}).done(function() {

				swal({
					title : 'Deleted!',
					text : 'Ticket has been deleted.',
					type : 'success',
				}, function() {
					window.location.href = "/admin/tickets";
				});
			});

		});
	});
	//Dashboard Buttons
	$('button.topIssueMonth').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/admin/topIssue",
			data : {
				topIssue : "Month"
			},
		}).done(function(data) {
			c3.generate({
				bindto : '#pie',
				size : {
					height : 302
				},

				data : {
					json : data,

					type : 'pie'
				},

				pie : {
					label : {
						format : function(value, ratio, id) {
							return value;
						}
					}
				}

			});
		});
	});
	$('button.topIssueYear').on('click', function() {

		$.ajax({
			type : "GET",
			url : "/admin/topIssue",
			data : {
				topIssue : "Year"
			},
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#pie',
				size : {
					height : 302
				},
				data : {
					json : data,

					type : 'pie'
				},
				pie : {
					label : {
						format : function(value, ratio, id) {
							return value;
						}
					}
				}

			});
		});
	});
	$('button.topIssueWeek').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/admin/topIssue",
			data : {
				topIssue : "Week"
			},
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#pie',
				size : {
					height : 302
				},
				data : {
					json : data,

					type : 'pie'
				},
				pie : {
					label : {
						format : function(value, ratio, id) {
							return value;
						}
					}
				}

			});
		});
	});

	$('button.topSupportWeek').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/admin/topSupport",
			data : {
				topSupport : "Week"
			},
		}).done(function(data) {
			var html;
			console.log(data);
			$.each(data, function(index, v) {
				html += "<tr><td>" + v.total + "</td><td>" + v.name + "</td></tr>";

			});

			$('tbody.topSupport').html(html);
		});
	});

	$('button.topSupportMonth').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/admin/topSupport",
			data : {
				topSupport : "Month"
			},
		}).done(function(data) {
			var html;
			console.log(data);
			$.each(data, function(index, v) {
				html += "<tr><td>" + v.total + "</td><td>" + v.name + "</td></tr>";

			});

			$('tbody.topSupport').html(html);
		});
	});

	$('button.topSupportYear').on('click', function() {
		$.ajax({
			type : "GET",
			url : "/admin/topSupport",
			data : {
				topSupport : "Year"
			},
		}).done(function(data) {
			var html;
			console.log(data);
			$.each(data, function(index, v) {
				html += "<tr><td>" + v.total + "</td><td>" + v.name + "</td></tr>";

			});

			$('tbody.topSupport').html(html);
		});
	});

	//Assign Support section @ admin dashboard
	var assignSupport = $('button.noSupport').ladda();

	assignSupport.click(function(e) {
		toastr.options = {
				positionClass : "toast-top-center",

			};
		e.preventDefault();
		var noSupport = [];
		$('select.noSupport').each(function(index) {

			noSupport[index] = {
				id : $(this).attr('name'),
				assigned_support : $(this).val()
			};
		});
		console.log(noSupport);
		if(noSupport[0] == null){
			toastr.info('No Tickets Found');
			return false;
			
		}
		assignSupport.ladda('start');

		$.ajax({
			headers : {
				'X-CSRF-Token' : $('input[name="_token"]').val()
			},
			type : 'PUT',
			url : '/admin/assignSupport',
			data : {
				support : noSupport
			},
		}).done(function(data) {
			console.log(data);
			assignSupport.ladda('stop');
			
			toastr.success('Tickets has been assigned to their support');

		});
	});

	//Admin/Agent create client acount page
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
			url : "/tickets/signUp",
			data : $('form.clientForm').serialize(),
		}).done(function(data) {
			if (data.success == false) {

				registerClient.ladda('stop');
				if (data.errors['first_name']) {
					$('div.firstname').addClass('has-error');
					$('label.firstname').show().text('*' + data.errors['first_name'][0]);
					;
				}
				if (data.errors['last_name']) {
					$('div.lastname').addClass('has-error');
					$('label.lastname').show().text('*' + data.errors['last_name'][0]);
					;
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
				toastr.options = {
					positionClass : "toast-top-center",
				};
				toastr.success('Account succesfully created');
				registerClient.ladda('stop');
				$('form.clientForm').trigger("reset");
			}

		});
	});
	//Admin Edit Account Page
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
		}).done(function(data) {
			if (data.success == true) {
				swal('Password successfully changed', '', 'success');
				editPassword.ladda('stop');
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
});
