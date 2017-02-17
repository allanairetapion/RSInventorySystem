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
            ],
            "createdRow": function( row, data, dataIndex ) {        		
            	$('td',row).eq(0).wrapInner("<a href='items/"+ data[0] +"' id="+ data[0] +"></a>");											
		    }

        });
		$('.chosen-select', this).chosen();

	});
	
	$('button.borrowTicketSearch').click(function(){
		$('div#borrowSearchResult').addClass('hide');
		$('div#borrowResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/borrow/advancedSearch",
			data : $('form.borrowTicketSearch').serialize(),
			success: function(data){
				var table = $('table#borrowSearchResult').DataTable();
				table.clear();

				
					$.each(data.response,function(i, v) {
						table.row.add([
						               v.itemNo,
						               v.itemType,
						               v.brand + " - " + v.model,
						               v.agent_FN + " " + v.agent_LN,
						               v.first_name + " " + v.last_name,
						               v.borrowerStationNo,
						               v.created_at]);
						
						});
					
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#borrowSearchResult').removeClass('hide');
			}
		});
		});
	});
	
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
					table.row.add([
					               data.response['itemNo'],
					               data.response['itemType'],
					               data.response['brand'] + "- " + data.response['model'],
					               data.response['agent_FN'] + " " +data.response['agent_LN'],
					               data.response['first_name'] + " " +data.response['last_name'],
					               data.response['borrowerStationNo'],
					               data.response['created_at']
					               ]);
					table.draw();
			
					$('#myModal').modal('hide');
					swal('','Item Borrowed','success');
					
					$('form.itemInfo').hide();
				}
			});
		});
		
		$('button#borrowSearch').click(function(){
			$('div#borrowSearchResult').addClass('hide');
			$('div#borrowResult').hide(function(){
				$('div#spinner').removeClass('hide');
				$.ajax({
				type : "get",
				url : "/inventory/borrow/search",
				data : {borrowSearch : $("input#borrowSearch").val()},
				success: function(data){
					var table = $('table#borrowSearchResult').DataTable();
					table.clear();

					
						$.each(data.response,function(i, v) {
							table.row.add([
							               v.itemNo,
							               v.itemType,
							               v.brand + " - " + v.model,
							               v.agent_FN + " " + v.agent_LN,
							               v.first_name + " " + v.last_name,
							               v.borrowerStationNo,
							               v.created_at]);
							
							});
						
					table.draw();
					$('div#spinner').addClass('hide');
					$('div#borrowSearchResult').removeClass('hide');
				}
			});
			});
			
			
		});
		
});