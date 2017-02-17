$(function() {
	$(document).ready(
			function() {
				$('table.ticketReport').dataTable(
						{
							"createdRow" : function(row, data, dataIndex) {
								if (data[5] == "Open") {

									$(row).addClass('bg-primary');
								} else if (data[5] == "Pending") {
									$(row).css('background-color', '#F2F256');
								} else if (data[5] == "Closed") {
									$(row).addClass('navy-bg');
								} else if (data[5] == "Unresolved") {
									$(row).addClass('red-bg');
								}
							},
							"createdRow" : function(row, data, dataIndex) {
								$('td', row).eq(0).wrapInner(
										"<a href='/admin/tickets/" + data[0]
												+ "' id=" + data[0] + "></a>");
							},
							"bSort" : false,
							dom : '<"html5buttons"B>lTgtip',
							buttons : [
									{
										extend : 'csv'
									},
									{
										extend : 'excel',
										title : 'Ticket Report'
									},
									{
										extend : 'print',
										customize : function(win) {
											$(win.document.body).addClass(
													'white-bg');
											$(win.document.body).css(
													'font-size', '10px');
											$(win.document.body).find('table')
													.addClass('compact').css(
															'font-size',
															'inherit');
										}
									} ]

						});
				$('table.ticketReport2').dataTable(
						{							
							"createdRow" : function(row, data, dataIndex) {
								$('td', row).eq(0).wrapInner(
										"<a href='/admin/tickets/" + data[0]
												+ "' id=" + data[0] + "></a>");
							},
							"bSort" : false,
							dom : '<"html5buttons"B>lTgtip',
							buttons : [
									{
										extend : 'csv'
									},
									{
										extend : 'excel',
										title : 'Ticket Report'
									},

									{
										extend : 'print',
										customize : function(win) {
											$(win.document.body).addClass(
													'white-bg');
											$(win.document.body).css(
													'font-size', '10px');
											$(win.document.body).find('table')
													.addClass('compact').css(
															'font-size',
															'inherit');
										}
									} ]

						});
			});

	$('button.advancedTicketSearch').click(
			function() {
				console.log($('form.advancedTicket').serialize());
				$.ajax({
					type : "GET",
					url : "/admin/advancedSearch",
					data : $('form.advancedTicket').serialize(),
				}).done(
						function(data) {

							var table = $('table.ticketReport').DataTable();
							table.clear();
							$.each(data.response, function(i, v) {

								var rowNode = table.row
										.add(
												[
														'',
														v.id,
														v.first_name + ' '
																+ v.last_name,
														v.description,
														v.subject,
														v.ticket_status,
														v.department,
														v.assign_FN + ' '
																+ v.assign_LN,
														v.close_FN + ' '
																+ v.close_LN,
														v.created_at,
														v.updated_at ]).draw();
							});
						});
			});
	$('button#ticketSearch').click(
			function() {
				$.ajax({
					type : "GET",
					url : "/admin/ticketSearch",
					data : {
						search : $('input#ticketSearch').val()
					},
					success : function(data) {
						var table = $('table.ticketReport').DataTable();
						table.clear();
						$.each(data.response, function(i, v) {

							var rowNode = table.row.add(
									[ '', v.id,
											v.first_name + ' ' + v.last_name,
											v.description, v.subject,
											v.ticket_status, v.department,
											v.assign_FN + ' ' + v.assign_LN,
											v.close_FN + ' ' + v.close_LN,
											v.created_at, v.updated_at ])
									.draw();
						});
					},
					error : function(xhr, statusText, error) {
						allTicketSearch.ladda('stop');
						swal('No Results found', '', 'info');
					}
				});
			});
});