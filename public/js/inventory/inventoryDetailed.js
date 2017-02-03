$(function() {
	$(document).ready(function(){
		$('table').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Borrowed Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Borrowed Items', orientation: 'landscape',},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]

        });
		$('.input-group.date').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		$('.chosen-select').chosen();
		$('div#itemAdvancedSearch').hide();
		
	});

	$('#itemLevel').on('shown.bs.modal', function() {
		$.ajax({
			type : "GET",
			url : "/inventory/detailed/itemLevel"
		}).done(function(data) {
			console.log(data);
			c3.generate({
				bindto : '#stocked',
				data : {
					x : 'x',
					columns : data,

					type : 'bar',
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




	$('.nav-tabs a').click(function(){
		var iType = $(this).text();
	
			$('tbody#stock'+ iType).html(
					'<tr><td colspan="9"><div class="spiner-example">'+
							'<div class="sk-spinner sk-spinner-three-bounce">'+
								'<div class="sk-bounce1"></div>'+
								'<div class="sk-bounce2"></div>'+
								'<div class="sk-bounce3"></div>'+
							'</div>'+
						'</div></td></tr>');
					$.ajax({
						type : "GET",
						url : "/inventory/detailed/stockItems",
						data : {
							itemType : iType,
						},
					}).done(function(data){
						var html;
						var table = $('table#stock'+ iType).DataTable();
						table.destroy();
						$('tbody#stock'+ iType).html('');
						$.each(data,function(index, v) {
								html += "<tr><td><a href=/inventory/items/"+ v.itemNo +">" + v.itemNo +"</a></td><td>" + v.unique_id+ "</td>" +
										"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
										"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
										"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
										"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
										"<td>" + v.created_at+ "</td></tr>";

							});
						$('tbody#stock'+ iType).html(html);
						dataTable('stock'+ iType);
						
						 
						});
			
		
	});
	$('select#stockType').change(function(){
			var table = $('table').DataTable();
			table.search($('select#stockType').val()).draw();
		  
		
	});

	$('a#itemInfo').click(function(){
		$('div#itemInfo').modal('toggle');
		$('h4.modal-title').text($(this).text());
		$('span#itemInfoStatus').text($(this).parents('tr:first').find('td:eq(4)').text());
	});
	
	$('button#detailedSearch').click(function(){
		if($('a#detailedSearch').length < 1){
			$('ul.nav-tabs').append('<li class=""><a id="detailedSearch"  data-toggle="tab" href="#tab-6">Search Result</a></li>');
			$('a#detailedSearch').click();
		}
		
		$('tbody#detailSearch').html(
				'<tr><td colspan="9"><div class="spiner-example">'+
						'<div class="sk-spinner sk-spinner-three-bounce">'+
							'<div class="sk-bounce1"></div>'+
							'<div class="sk-bounce2"></div>'+
							'<div class="sk-bounce3"></div>'+
						'</div>'+
					'</div></td></tr>');
		$.ajax({
			type : "GET",
			url : "/inventory/detailed/search",
			data : {
				detailSearch : $('input#detailedSearch').val(),
			},
			success: function(data){
				data = data.response
				var html;
				var table = $('table#detailSearch').DataTable();
				table.destroy();
				$('tbody#detailSearch').html('');
				
					$.each(data,function(index, v) {
						html += "<tr><td><a href=/inventory/items/"+ v.itemNo +">" + v.itemNo +"</a></td><td>" + v.unique_id+ "</td>" +
								"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
								"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
								"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
								"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
								"<td>" + v.created_at+ "</td></tr>";

					});
				
				

				$('tbody#detailSearch').html(html);
				dataTable('detailSearch');
			}
		})
		
	});
	$('button#detailAdvanceSearch').click(function(){
		if($('a#detailedSearch').length < 1){
			$('ul.nav-tabs').append('<li class=""><a id="detailedSearch"  data-toggle="tab" href="#tab-6">Search Result</a></li>');
			$('a#detailedSearch').click();
		}
		
		$('tbody#detailSearch').html(
				'<tr><td colspan="9"><div class="spiner-example">'+
						'<div class="sk-spinner sk-spinner-three-bounce">'+
							'<div class="sk-bounce1"></div>'+
							'<div class="sk-bounce2"></div>'+
							'<div class="sk-bounce3"></div>'+
						'</div>'+
					'</div></td></tr>');
		$.ajax({
			type : "GET",
			url : "/inventory/detailed/advancedSearch",
			data : $('form#itemAdvancedSearch').serialize(),
			success: function(data){
				data = data.response
				var html;
				var table = $('table#detailSearch').DataTable();
				table.destroy();
				$('tbody#detailSearch').html('');
				
					$.each(data,function(index, v) {
						html += "<tr><td><a href=/inventory/items/"+ v.itemNo +">" + v.itemNo +"</a></td><td>" + v.unique_id+ "</td>" +
								"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
								"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
								"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
								"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
								"<td>" + v.created_at+ "</td></tr>";

					});
				
				

				$('tbody#detailSearch').html(html);
				dataTable('detailSearch');
			}
		})
		
	});
	$('button.btn-warning').click(function(){
		$(".chosen-select").val('').trigger("chosen:updated");
	});
	
	function dataTable(tableName){	
		$('table#'+tableName).DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Borrowed Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Borrowed Items', orientation: 'landscape',},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]

        });
		
		var table = $('table').DataTable();
		table.search($('select#stockType').val()).draw();
	};
});