/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function(){
		$('label.email').hide()
		
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
		$('div.ticketReplySummernote')
		.summernote(
				{
					height: 200,
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
	});
	
	var ticketReply = $('button.ticketReply').ladda();

	ticketReply.click(function(e) {

		$('label.email').hide();
		$('div.email').removeClass('has-error');

		$('input[type="hidden"].ticketReply').val(
				$('div.ticketReplySummernote').summernote('code'));
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
});