$(function() {
	$(document).ready(function(){
		$('.footable').footable();
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
		if($('tbody#stock'+ iType +' tr td:eq(0)').attr('colspan') == 9 ){
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
						var table = $('table#stock'+ iType).data('footable');
						if(data == ""){
							html = "<tr><td class='text-center' colspan='9'>No Result Found</td><tr>"
						}else{
							$.each(data,function(index, v) {
								html += "<tr><td>" + v.itemNo +"</td><td>" + v.unique_id+ "</td>" +
										"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
										"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
										"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
										"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
										"<td>" + v.created_at+ "</td></tr>";

							});
						}
						

						$('tbody#stock'+ iType).html(html);
						table.redraw();
						 $('.footable').trigger('footable_filter', {filter: $('select#stockType').val()});
						});
			}
		
	});
	$('select#stockType').change(function(){
		
		      $('.footable').trigger('footable_filter', {filter: $(this).val()});
		  
		
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
				var table = $('table#detailSearch').data('footable');
				if(data == ""){
					html = "<tr><td class='text-center' colspan='9'>No Result Found</td><tr>"
				}else{
					$.each(data,function(index, v) {
						html += "<tr><td>" + v.itemNo +"</td><td>" + v.unique_id+ "</td>" +
								"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
								"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
								"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
								"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
								"<td>" + v.created_at+ "</td></tr>";

					});
				}
				

				$('tbody#detailSearch').html(html);
				table.redraw();
				 $('.footable').trigger('footable_filter', {filter: $('select#stockType').val()});
			}
		})
		
	});
	$('button#itemAdvancedSearch').click(function(){
		$('div#itemAdvancedSearch').removeClass('hidden');
		$('div#itemAdvancedSearch').slideToggle();
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
				var table = $('table#detailSearch').data('footable');
				if(data == ""){
					html = "<tr><td class='text-center' colspan='9'>No Result Found</td><tr>"
				}else{
					$.each(data,function(index, v) {
						html += "<tr><td>" + v.itemNo +"</td><td>" + v.unique_id+ "</td>" +
								"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
								"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
								"<td>" + v.morning_FN +" "+ v.morning_LN + "</td>"+
								"<td>"+ v.night_FN + " "+ v.night_LN + "</td>" +
								"<td>" + v.created_at+ "</td></tr>";

					});
				}
				

				$('tbody#detailSearch').html(html);
				table.redraw();
				 $('.footable').trigger('footable_filter', {filter: $('select#stockType').val()});
			}
		})
		
	});
	$('button.btn-warning').click(function(){
		$(".chosen-select").val('').trigger("chosen:updated");
	});
});