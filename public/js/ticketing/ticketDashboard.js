$(function() {
	$(document).ready(function() {
		$('table').dataTable( {
			  "createdRow": function( row, data, dataIndex ) {
				  
				    if (data[3] == "Open") {
						$('td',row).addClass('bg-primary');
						
					} else if (data[3] == "Pending") {
						$('td',row).css('background-color','rgb(242, 242, 86)');
						
					} else if (data[3] == "Closed") {
						$('td',row).addClass('navy-bg');
						
					}else if (data[3] == "Unresolved"){
						$('td',row).addClass('red-bg');
						
					}
				    $('td',row).eq(0).wrapInner("<a href='/admin/tickets/"+ data[0] +"' id="+ data[0] +"></a>").removeClass();	
				    }
				  } );
			
		$.ajax({
			type:'get',
			url:'/admin/ticketCount',
		}).done(function(data){
			console.log(data);
			$('h2.newTickets').text(data.openTickets);
			$('h2.pendingTickets').text(data.pendingTickets);
			$('h2.overdueTickets').text(data.overdueTickets);
			$('h2.closedTickets').text(data.closedTicketsToday);
		});
	$.ajax({
		type : "GET",
		url : "/admin/ticketStatus",
		
	}).done(function(data) {
		console.log(data);
		c3.generate({
			bindto : '#pie',
			data : {
				json : data,
				type : 'pie',
				onclick: function (d,e){
					$('table.ticketStatusInfo').hide();
					$('div.spiner').show();
					if(d.id != "Closed"){
						$('th.status').text('Assigned To');
					}else{
						$('th.status').text('Closed By');
					}
					
					var html;
					$.ajax({
						type : 'GET',
						url : '/admin/ticketStatus/info',
						data : d,
					}).done(function(data) {
						console.log(data.topic)
						var table = $('table.ticketStatusInfo').DataTable();
						table.clear();
					
						
						$.each(data.tickets, function( i, v ) {
							var agent;
							if(v.ticket_status != "Closed"){
								agent =  v.assign_FN + ' ' + v.assign_LN;
							}else{
								agent =  v.close_FN + ' ' + v.close_LN;
							}
								table.row.add([
								               v.id,
								               v.first_name + ' '+ v.last_name,
								               v.description,
								               v.ticket_status,
								               agent,
								               v.created_at,
								               v.updated_at
								               ]);
				
								});
						table.draw();
						$('div.spiner').hide();				
						$('table.ticketStatusInfo').show();
						$('div#myModal').modal('toggle');
						});
					
						console.log(d);
						
					}
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
//Issues Pie Graph

	$.ajax({
		type : "GET",
		url : "/admin/topIssue",
		
	}).done(function(data) {
		console.log(data);
		c3.generate({
			bindto : '#pie2',	
			data : {
				json : data,
				type : 'pie',
				onclick: function (d,e){
					$('table.topIssueInfo').hide();
					$('div.spiner').show();
					var html;
					$.ajax({
						type : 'GET',
						url : '/admin/topIssue/info',
						data : d,
					}).done(function(data) {
						console.log(data.topic)
						var table = $('table.topIssueInfo').DataTable();
						table.clear();
						
						$.each(data.tickets, function( i, v ) {
							
							table.row.add([
							               v.id,
							               v.first_name + ' ' + v.last_name,
							               v.topic_id,
							               v.ticket_status,
							               v.assign_FN + ' ' + v.assign_LN,
							               v.close_FN + ' ' + v.close_LN,
							               v.created_at,
							               v.updated_at,]);
							
						});
					
					table.draw();
					$('div.spiner').hide();
					$('table.topIssueInfo').show();
					$('div#myModal2').modal('show');
					});
					
					}
					
					
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
	
	$.ajax({
		type : "GET",
		url : "/admin/ticketSummary"
	}).done(function(data) {
		console.log(data);
		c3.generate({
			bindto : '#stocked',
			data : {
				x : 'x',
				columns : data,

				type : 'spline',
				groups : [['data1', 'data2']]
			},
			axis : {
				x : {
					type : 'category',

				}
			}
		});

	});



});

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
	
});