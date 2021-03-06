$(function() {
	
	$(document).ready(function() {
		$('form.borrowInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('.chosen-select', this).chosen();
		$('.input-group.date.dateReturned').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		$("div#returnAdvancedSearch").hide();
		$('table').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Returned Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Returned Items', orientation: 'landscape',},

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
		
	});

	
		
	$(function() {
		$("input.uniqueId").keyup(function() {
			$("input.uniqueId").autocomplete({
				source : "{{URL('/uniqueId')}}",
				minLength : 1,
				appendTo: "#returnItem"
			});
			
		});
	}); 

	$('button.returnTicketSearch').click(function(){
		$('div#returnSearchResult').addClass('hide');
		$('div#returnResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/return/advancedSearch",
			data : $('form.returnTicketSearch').serialize(),
			success: function(data){
				var table = $('table#returnSearchResult').DataTable();
				table.clear();				
				
				$.each(data.response,function(i, v) {
					table.row.add([
					               v.itemNo,
					               v.itemType,
					               v.brand + " - " + v.model,
					               v.agent_FN + " " + v.agent_LN,
					               v.first_name + " " + v.last_name,
					               v.created_at]);						
						});
					
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#returnSearchResult').removeClass('hide');
			}
		});
		});
	});
	
	$('select#returnItemNo').change(function(){
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('form.borrowInfo').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/borrowInfo",
			data : { item : $('select#returnItemNo').val() } ,
		}).done(function(data){
			if(data.success == true){
				$('i.fa-pulse').hide();
				
				if(data.info['itemStatus'] == "Available"){
					$('div.itemNotfound').show();
					$('div.itemNo').addClass('has-error');
					$('span.itemNo').text("This Item is already returned").show();
				}else{	
					$('div.itemNotfound').hide();
					$('form.borrowInfo').show();
					$('input.infoId').val(data.info['unique_id']);
					$('input.infoItemNo').val(data.info['itemNo']);
					$('input.infoBorrower').val(data.borrow['borrower']);
					$('input.infoBorrowerName').val(data.borrow['borrowerName']);
					$('input.infoStationNo').val(data.borrow['borrowerStationNo']);
					$('input.infoItemType').val(data.info['itemType']);
					$('input.infoBrand').val(data.info['brand']);
					$('input.infoModel').val(data.info['model']);
					$('input.infodateBorrowed').val(data.borrow['created_at']);
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
	
	var returnItem = $('button.returnItem').ladda();
	
	returnItem.click(function(){
		returnItem.ladda('start');
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		console.log($('form.returnItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/returnItem",
			data : $('form.returnItem').serialize() ,
		}).done(function(data){
			if(data.success == false){
				returnItem.ladda('stop');
				if(data.errors["unique_id"]){
					
					$('span.unique_id').text(data.errors["unique_id"]).show();
					
					
					if(data.errors["unique_id"] == "The selected unique id is invalid."){
						$('span.unique_id').text("This Item is already Returned").show();
						$('div.unique_id').addClass('has-error');
					}
					$('div.unique_id').addClass('has-error');
				}
				if(data.errors["itemNo"]){
					$('span.itemNo').text(data.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				if(data.errors["dateReturned"]){
					$('span.dateReturned').text(data.errors["dateReturned"]).show();
					$('div.dateReturned').addClass('has-error');
				}
			}else{
				returnItem.ladda('stop');
				$('form.returnItem').trigger('reset');
				var table = $('table#return').DataTable();
				table.row.add([
				               data.response['itemNo'],
				               data.response['itemType'],
				               data.response['brand'] + " - " +data.response['model'],
				               data.response['first_name'] + " " +data.response['last_name'],
				               data.response['agent_FN'] + " " +data.response['agent_LN'],				              
				               data.response['created_at']
				               ]);
				
				table.draw();
				
				$('#myModal').modal('hide');
				swal('','Item Returned','success');
				$('form.borrowInfo').hide();
				
			}
			
		});
	});
	$('button#returnSearch').click(function(){
		$('div#returnSearchResult').addClass('hide');
		$('div#returnResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/return/search",
			data : {returnSearch : $("input#returnSearch").val()},
			success: function(data){
				var table = $('table#returnSearchResult').DataTable();
				table.clear();				
				
				$.each(data.response,function(i, v) {
					table.row.add([
					               v.itemNo,
					               v.itemType,
					               v.brand + " - " + v.model,
					               v.agent_FN + " " + v.agent_LN,
					               v.first_name + " " + v.last_name,
					               v.created_at]);						
						});
					
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#returnSearchResult').removeClass('hide');
			}
		});
		});
		
		
	});
	$('#myModal').on('shown.bs.modal', function () {
		  $('.chosen-select', this).chosen();
		});
});