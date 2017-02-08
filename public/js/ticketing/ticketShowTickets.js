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
		$('.i-checks').iCheck({
			checkboxClass : 'icheckbox_square-green',
			radioClass : 'iradio_square-green',
		});
		$('div.spinner').hide();
		$('table.showTickets').footable();
	});
	
	$(document).on('click', 'a#closeTicket', function() {
		$('input[type="hidden"]#ticketId').val($(this).attr('name'));
		});
	
	var allTicketSearch = $('button.allTicketSearch').ladda();
	
	allTicketSearch.click(function(){
		allTicketSearch.ladda('start');
		$.ajax({
			type : 'GET',
			url : '/admin/advancedSearch',
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
							"<td>" + v.first_name + " "+ v.last_name + "</td>" +
							"<td class='text-center'><span class='label label-default'>" + 
							v.priority_level + "</span></td>" +
							"<td>" + moment(v.updated_at).format('MMM DD') + "</td></tr>";
					
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
	$('button#ticketSearch').click(function(){
		$.ajax(
				{
					type : "GET",
					url : "/admin/ticketSearch",
					data : { search : $('input#ticketSearch').val()},
					success : function(data){
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
									"<td>" + v.first_name + " "+ v.last_name + "</td>" +
									"<td class='text-center'><span class='label label-default'>" + 
									v.priority_level + "</span></td>" +
									"<td>" + moment(v.updated_at).format('MMM DD') + "</td></tr>";
							
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
});