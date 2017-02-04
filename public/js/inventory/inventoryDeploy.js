$(function() {
	
	$(document).ready(function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div#deployAdvancedSearch').hide();
		$('table#deploy').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Deployed Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Deployed Items', orientation: 'landscape',},

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
		
		});
	
	$("button#deployAdvancedSearch").click(function(){
		$("div#deployAdvancedSearch").slideToggle();
		});
	$('#myModal').on('shown.bs.modal', function () {
		  $('.chosen-select', this).chosen();
		});
	$('select#deployItemNo').change(function(){
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#deployItemNo').val() } ,
		}).done(function(data){
			if(data.success == true){
				$('i.fa-pulse').hide();
				$('div.itemNotfound').hide();
				$('form.itemInfo').show();
				$('input.infoId').val(data.info['unique_id']);
				$('input.infoItemNo').val(data.info['itemNo']);
				$('input.infoCompany').val(data.info['company']);
				$('input.infoStationNo').val(data.info['stationNo']);
				$('input.infoItemType').val(data.info['itemType']);
				$('input.infoBrand').val(data.info['brand']);
				$('input.infoModel').val(data.info['model']);
				if(data.info['itemStatus'] != "In-stock"){
					$('div.itemNo').addClass('has-error');
					$('span.itemNo').text("This Item is "+ data.info['itemStatus']).show();
					$('span.borrower').text("*").show();
				}
				
				
				
			}else if(data.success == false){
				
				$('i.fa-pulse').hide();
				
				if($('input.uniqueId').val() == ""){
					$('div.itemNotfound').hide();
					$('input.infoItemNo').val("");
				}else{
					$('div.itemNotfound').show();
				}
				
				
			}
		});
	});
	
	var deployItem = $('button.deployItem').ladda();
	
	deployItem.click(function(){
		deployItem.ladda('start');
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		console.log($('form#deployItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/deployItem",
			data : $('form#deployItem').serialize() ,
			success: function(data){
				deployItem.ladda('stop');
				$('form.deployItem').trigger('reset');
				var table = $('table#deploy').DataTable();
				
				var newRow = "<tr><td><a href='/inventory/items/"+ data.response['itemNo'] +"'>" + data.response['itemNo'] + "</a></td>"+
				"<td>" + data.response['unique_id'] + "</td><td>" + data.response['itemType']+" </td>" +
				"<td>" + data.response['brand'] +"</td><td>"+ data.response['model']+"</td>" +
				"<td>" + data.response['stationNo'] +"</td>"+
				"<td>" + data.response['first_name']+" "+data.response['last_name']+"</td>"+
				"<td>"+ data.response['created_at'] +"</td></tr>";
				
				
				$('tbody#deploy').prepend(newRow);
				table.draw();
				$('#myModal').modal('hide');
				swal('','Item Deployed','success');
				$('form.borrowInfo').hide();
			},
			error: function(data){
				data = data.responseJSON;
				deployItem.ladda('stop');
				if(data.errors["itemNo"]){
					
					$('span.itemNo').text(data.errors["unique_id"]).show();
					
					
					if(data.errors["itemNo"] == "The selected unique id is invalid."){
						$('span.itemNo').text("This Item is already Deployed").show();
						$('div.itemNo').addClass('has-error');
					}
					$('div.itemNo').addClass('has-error');
				}
				
				if(data.errors["stationNo"]){
					$('span.stationNo').text(data.errors["stationNo"]).show();
					$('div.stationNo').addClass('has-error');
				}
			}
		});
	});
	
	$('button#deploySearch').click(function(){
		$('table#deploySearchResult').addClass('hide');
		$('div#deployResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/deploy/search",
			data : {deploySearch : $("input#deploySearch").val()},
			success: function(data){
				var table = $('table#deploySearchResult').DataTable();
				table.destroy();
				

				$('tbody#deploySearchResult').html('');
				
				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +" '>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
									"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
									"<td>" + v.first_name + " " + v.last_name + "</td>"+
									"</td><td>" + v.created_at + "</td></tr>";
						$('tbody#deploySearchResult').append(newRow);
						});
					}else{
						$('tbody#deploySearchResult').append("");
				}
				dataTable();
				$('div#spinner').addClass('hide');
				$('table#deploySearchResult').removeClass('hide');
			}
		});
		});
		
		
	});
	
	$('button.deployTicketSearch').click(function(){
		$('table#deploySearchResult').addClass('hide');
		$('div#deployResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/deploy/advancedSearch",
			data : $('form.deployTicketSearch').serialize(),
			success: function(data){
				var table = $('table#deploySearchResult').DataTable();
				table.destroy();
				

				$('tbody#deploySearchResult').html('');
				
				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +" '>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
									"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
									"<td>" + v.first_name + " " + v.last_name + "</td>"+
									"</td><td>" + v.created_at + "</td></tr>";
						$('tbody#deploySearchResult').append(newRow);
						});
					}else{
						$('tbody#deploySearchResult').append("");
				}
				dataTable();
				$('div#spinner').addClass('hide');
				$('table#deploySearchResult').removeClass('hide');
			}
		});
		});
	});
function dataTable(){
		
		$('table#deploySearchResult').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Deployed Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Deployed Items', orientation: 'landscape',},

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
	};
});