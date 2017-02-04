$(function() {
	
	$(document).ready(function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('.input-group.date.dateBorrowed').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		$('div#borrowAdvancedSearch').hide();
		$('table#borrow').DataTable({
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
		$('.chosen-select', this).chosen();

	});
	
	$('button.borrowTicketSearch').click(function(){
		$('table#borrowSearchResult').addClass('hide');
		$('div#borrowResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/borrow/advancedSearch",
			data : $('form.borrowTicketSearch').serialize(),
			success: function(data){
				var table = $('table#borrowSearchResult').DataTable();
				table.destroy();
				
				$('tbody#borrowSearchResult').html('');

				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +" '>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
						"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
						"<td>" + v.agent_FN + " " + v.agent_LN + "</td><td>" + v.first_name + " " + v.last_name + "</td>"+
						"<td>" + v.borrowerStationNo + "</td><td>" + v.created_at + "</td></tr>";
						$('tbody#borrowSearchResult').append(newRow);
						});
					}else{
						$('tbody#borrowSearchResult').append("");
				}
				dataTable();
				$('div#spinner').addClass('hide');
				$('table#borrowSearchResult').removeClass('hide');
			}
		});
		});
	});
	
	function dataTable(){
		
		$('table#borrowSearchResult').DataTable({
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
	};

	$('#myModal').on('shown.bs.modal', function () {
		  $('.chosen-select', this).chosen();
		});

	$(function() {
		$("input.uniqueId").keyup(function() {
			$("input.uniqueId").autocomplete({
				source : "{{URL('/uniqueId')}}",
				minLength : 1,
				appendTo: "#borrowItem"
			});
			
		});
	}); 
	
	$('select#borrowItemNo').change(function(){				
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#borrowItemNo').val() } ,
		}).done(function(data){
			if(data.success == true){
				$('i.fa-pulse').hide();
				$('div.itemNotfound').hide();
				$('form.itemInfo').show();
				$('input.infoId').val(data.info['unique_id']);
				$('input.infoItemNo').val(data.info['itemNo']);
				$('input.infoItemType').val(data.info['itemType']);
				$('input.infoBrand').val(data.info['brand']+ ' - '+ data.info['model']);
				if(data.info['itemStatus'] != "In-stock"){
					$('div.itemNo').addClass('has-error');
					$('span.itemNo').text("This Item is "+ data.info['itemStatus']).show();
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
	
	
		var borrowItem = $('button.borrowItem').ladda();
		
		borrowItem.click(function(){
			borrowItem.ladda('start');
			$('div.dateBorrowed').removeClass('has-error');
			$('span.text-danger').hide();
			$('div.form-group').removeClass('has-error');
			$('div.input-group').removeClass('has-error');
			
			console.log($('form.borrowItem').serialize());
			
			$.ajax({
				type : "POST",
				url : "/inventory/borrowItem",
				data : $('form.borrowItem').serialize() ,
				error: function(data){
					
					borrowItem.ladda('stop');
					var errors = data.responseJSON;
					
					console.log(errors.errors["itemNo"]);
					
					if(errors.errors["itemNo"]){
						$('span.itemNo').text(errors.errors["itemNo"]).show();
						$('div.itemNo').addClass('has-error');
					}
					if(errors.errors["stationNo"]){
						$('span.stationNo').text(errors.errors["stationNo"]).show();
						$('div.stationNo').addClass('has-error');
					}
					if(errors.errors["borrower"]){
						$('span.borrower').text(errors.errors["borrower"]).show();
						$('div.borrower').addClass('has-error');
					}
					if(errors.errors["dateBorrowed"]){
						$('span.dateBorrowed').text(errors.errors["dateBorrowed"]).show();
						$('div.dateBorrowed').addClass('has-error');
						}
				},
				success : function (data){
					borrowItem.ladda('stop');
					$('form.borrowItem').trigger('reset');
					
					var table = $('table#borrow').DataTable();
					
					var newRow = "<tr><td><a href='/inventory/items/"+ data.response['itemNo'] +"'>" + data.response['itemNo'] + "</a></td><td>" + data.response['unique_id'] + " </td>"+
						"<td>" + data.response.itemType + "</td><td>" + data.response['brand'] + "</td><td>" + data.response['model'] + "</td>" +
						"<td>" + data.response['first_name'] + " " +data.response['last_name'] + "</td><td>" + data.response['borrower'] + "</td>"+
						"<td>" + data.response['borrowerStationNo'] + "</td><td>" + data.response['created_at'] + "</td></tr>";
					
					$('tbody#borrow').prepend(newRow);
					table.draw();
			
					$('#myModal').modal('hide');
					swal('','Item Borrowed','success');
					
					$('form.itemInfo').hide();
				}
			});
		});
		
		$('button#borrowSearch').click(function(){
			$('table#borrowSearchResult').addClass('hide');
			$('div#borrowResult').hide(function(){
				$('div#spinner').removeClass('hide');
				$.ajax({
				type : "get",
				url : "/inventory/borrow/search",
				data : {borrowSearch : $("input#borrowSearch").val()},
				success: function(data){
					var table = $('table#borrowSearchResult').DataTable();
					table.destroy();
					
					$('tbody#borrowSearchResult').html('');

					if(data.response.length >= 1){
						$.each(data.response,function(i, v) {
							var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +" '>" + v.itemNo + "</a></td><td>" + v.unique_id + " </td>"+
							"<td>" + v.itemType + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
							"<td>" + v.agent_FN + " " + v.agent_LN + "</td><td>" + v.first_name + " " + v.last_name + "</td>"+
							"<td>" + v.borrowerStationNo + "</td><td>" + v.created_at + "</td></tr>";
							$('tbody#borrowSearchResult').append(newRow);
							});
						}else{
							$('tbody#borrowSearchResult').append("");
					}
					dataTable();
					$('div#spinner').addClass('hide');
					$('table#borrowSearchResult').removeClass('hide');
				}
			});
			});
			
			
		});
		
});