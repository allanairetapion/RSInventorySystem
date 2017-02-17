/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function(){
		$('table').footable();
		$('label.text-danger').hide();
		$('div.spiner').hide();
	});
	$(document).on('click', 'a[href="#editTopic"]', function() {
		var editTopic = $(this).attr('id');

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

	$(document).on(
			'click',
			'button.saveEditTopic',
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

	
	$('button#addTopic').click(
			function(e) {
				$('span.addTopic').hide();
				$('div.form-group').removeClass(
						'has-error');
				
				$('label.text-danger').hide();

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
																	+ data.response['priority_level']
																	+ "</td>"
																	+ "<td><button type='button' class='btn btn-warning btn-xs editTopic' value="
																	+ data.response['topic_id']
																	+ ">Edit</button> </td></tr>";
														
										$('tbody.topics').append(html);
										$('table').trigger('footable_redraw');
										$('div#myModal').modal('hide');
						
										toastr.success('New Topic has been added.');

									}
								});

			});
	$(document).on('click', 'a[href="#deleteTopic"]', function() {
		var editTopic = $(this).attr('id');
		var row = $(this);
		$.ajax({
			headers : {
				'X-CSRF-Token' : $('input[name="_token"]').val()
			},
			type : 'delete',
			url : '/admin/topicDelete',
			data : {
				id : editTopic
			},
			success: function(){
				$(row).parents('tr').remove();
				toastr.success('Topic has been deleted.');
			},
			error: function(){
				swal('Ooops','Somethine went wrong','error');
			}
		});
	});
});