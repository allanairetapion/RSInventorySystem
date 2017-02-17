/**
 * @author ITojt01 Luis Philip M. Castaneda
 */

$(function() {
	$(document).ready(function() {
		$('table#agentTickets').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ],
            "createdRow": function( row, data, dataIndex ) {        		
            	$('td',row).eq(0).wrapInner("<a href='/admin/tickets/"+ data[0] +"' id="+ data[0] +"></a>");											
		    }

        });
		$.ajax({
			type : "GET",
			url : "/agents/ticketStats",
			data : {
				id : $('span#agentId').text()
			}
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#slineChart',
				data : {
					x : 'x',
					columns : data,
					colors : {
						Assigned : '#5cb85c',
						Unresolved : '#d9534f',
						Closed : '#337ab7'
					},
					type : 'spline',
					groups : [ [ 'data1', 'data2' ] ]
				},
				axis : {
					x : {
						type : 'category',

					}
				}
			});

		});
		
	});
});