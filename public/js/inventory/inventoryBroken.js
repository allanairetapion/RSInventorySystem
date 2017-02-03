$(function() {
	
	$(document).ready(function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('.chosen-select', this).chosen();
		$('div#brokenAdvancedSearch').hide();
		$('table#broken').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Broken Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Broken Items', orientation: 'landscape',},

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
		$('div#brokenSummary').summernote({
			height: 150,
			minHeight: 150,             // set minimum height of editor
			maxHeight: 150,
			toolbar : [
						[
								'style',
								[
										'bold',
										'italic',
										'underline',
										'clear' ] ],
						[ 'fontname',
								[ 'fontname' ] ],
						[ 'fontsize',
								[ 'fontsize' ] ],
						[ 'color', [ 'color' ] ],
						[
								'para',
								[ 'ul', 'ol',
										'paragraph' ] ],
						 ] 
		 });
		});

		

	$('button#brokenAdvancedSearch').click(function(){
		$('div#brokenAdvancedSearch').slideToggle();
	});
		
	$('li a#brokenMark').click(function(){
		var items = [];
		var mark = $(this).text();

		$('input:checkbox.brokenItem:checked').each(function () {
		       if(this.checked){
			       items.push($(this).val());
			   		$('tr#'+$(this).val()+' td').eq(3).text(mark);
		       }
		  });
		console.log(items);
		  if(items.length == 0){
			  toastr.warning('No Input');
				return false;
			  }
		
		swal({
			title : 'Are you sure?',
			text : 'Item Status will be changed to "' + mark +'"',
			type : 'warning',
			showCancelButton : true,
			showCancelButton : true,
			closeOnConfirm : false,
			showLoaderOnConfirm : true,
			disableButtonsOnConfirm : true,
		}, function() {
			
			$.ajax({
				headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
				type : "PUT",
				url : "/inventory/brokenMark",
				data : {items : items, mark : mark},
				success: function(data){
					var table = $('table#broken').data('footable');
					swal({
						title:"Success",
						text: "Successfully Marked as "+ mark,
						type: "success",
						},function(){
							$('input:checkbox.brokenItem:checked').each(function () {
								if(mark != "Repaired"){
									
								   		$('tr#'+$(this).val()+' td').eq(3).text(mark);	   		
							       		
								}else{								 
										table.removeRow($('tr#'+$(this).val()));
									}
								table.redraw();
							});
							});
					
						}
				});
			});
		
		  
	});

	$('button.brokenTicketSearch').click(function(){
		$('table#brokenSearchResult').addClass('hide');
		$('div#brokenResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/broken/advancedSearch",
			data : $('form.brokenTicketSearch').serialize(),
			success: function(data){
				var table = $('table#brokenSearchResult').DataTable();
				table.destroy();
				
				$('tbody#brokenSearchResult').html('');

				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr id='" + v.itemNo + "'><td><input type='checkbox' class='i-checks brokenItem' value='" + v.itemNo +"' />" + 
						"<a href='/inventory/items/"+ v.itemNo +"'> " +v.itemNo + "</td><td>" + v.unique_id + " </td>"+
						"<td>" + v.damage + "</td><td>" + v.first_name + " " + v.last_name + "</td><td>" + v.brokenStatus + "</td>" +
						"<td>" + v.agent_FN + " " + v.agent_LN + "</td>" + 
						"<td>" + v.created_at + "</td><td>" + v.itemType + "</td>"+
						"<td>" + v.brand + "</td><td>" + v.model + "</td>" + 
						"<td>" + decodeURI(v.brokenSummary) + "</td></tr>";
						$('tbody#brokenSearchResult').append(newRow);
						});
					}else{
						$('tbody#brokenSearchResult').append("<tr><td colspan='9' class='text-center'> No Data Found.</td></tr>");
				}
				dataTable();
				$('div#spinner').addClass('hide');
				$('table#brokenSearchResult').removeClass('hide');
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				
			}
		});
		});
	});
// Broken Item
	
	$('select#brokenItemNo').change(function(){				
		$('span.brokenItemNo').text("").hide();
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('div').removeClass('has-error');
		
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#brokenItemNo').val() } ,
		}).done(function(data){
			
			if(data.success == true){
				
				
				if(data.info['itemStatus'] == "Broken"){
					$('div.brokenItemNo').addClass('has-error');
					$('span.brokenItemNo').text("This Item is already Reported").show();
					
					$('i.fa-pulse').hide();
					
				}else{
					
					
					$('i.fa-pulse').hide();
					$('div.itemNotfound').hide();
				}			
			}else{
				$('i.fa-pulse').hide();
			}
		});
	});
	
	var brokenItem = $('button.brokenItem').ladda();
	
	brokenItem.click(function(){
		$('input#brokenSummary').val($('div#brokenSummary').summernote('code'));
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "POST",
			url : "/inventory/brokenItem",
			data : $('form#brokenItem').serialize() ,
		}).done(function(data){
			if(data.success == false){
				brokenItem.ladda('stop');
				if(data.errors["itemNo"]){
					
					$('span.brokenItemNo').text(data.errors["itemNo"]).show();
					
					
					
						$('span.brokeneItemNo').text(data.errors["itemNo"]).show();
						$('div.brokenItemNo').addClass('has-error');
					
					
				}
				if(data.errors["damage"]){
					$('span.brokenDamage').text(data.errors["damage"]).show();
					$('div.brokenDamage').addClass('has-error');
				}
				if(data.errors["summary"]){
					$('span.brokenSummary').text(data.errors["summary"]).show();
					$('div.brokenSummary').addClass('has-error');
				}
				
				if(data.errors["item_user"]){
					$('span.brokenitemUser').text(data.errors["item_user"]).show();
					$('div.brokenitemUser').addClass('has-error');
				}
				if(data.errors["status"]){
					$('span.brokenStatus').text(data.errors["status"]).show();
					$('div.brokenStatus').addClass('has-error');
				}
				if(data.errors["dateBroken"]){
					$('span.dateBroken').text(data.errors["dateBroken"]).show();
					$('div.dateBroken').addClass('has-error');
				}
			}else{
				brokenItem.ladda('stop');
				$('form#brokenItem').trigger('reset');
				var table = $('table#brokenResult').DataTable();
					
					
				$('tbody#broken').prepend(
				"<tr><td> <input type='checkbox' class='i-checks brokenItem' value='"+ data.response['itemNo'] +"'/> &nbsp;"+ 
				"<a href='/inventory/items/" + data.response['itemNo'] +"'>" +data.response['itemNo'] + "</a></td><td>" + data.response['unique_id'] + "</td>" +
				"<td>" + data.response['damage'] + "</td><td>" + data.response['first_name'] + " " + data.response['last_name'] + "</td><td>"+data.response['brokenStatus']+"</td>" +
				"<td>"+ data.response['agent_FN']+" "+ data.response['agent_LN']+"</td>" +
				"<td>" + data.response['created_at']+ "</td><td>" + data.response['itemType'] +"</td>" +
				"<td>" + data.response['brand'] + "</td><td>" + data.response['model'] + "</td>" +
				"<td>"+ decodeURI(data.response['brokenSummary']) +"</tr>");
				
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				table.draw();
				$('div#brokenReport').modal('hide');
				
				swal('','Item Reported','success');
				
				
			}
			
		});
	});
	$('button#brokenSearch').click(function(){
		$('table#brokenSearchResult').addClass('hide');
		$('div#brokenResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/broken/search",
			data : {brokenSearch : $("input#brokenSearch").val()},
			success: function(data){
				var table = $('table#brokenSearchResult').DataTable();
				table.destroy();
				
				$('tbody#brokenSearchResult').html('');

				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr id='" + v.itemNo + "'><td><input type='checkbox' class='i-checks brokenItem' value='" + v.itemNo +"' />" + 
						"<a href='/inventory/items/"+ v.itemNo +"'> " +v.itemNo + "</td><td>" + v.unique_id + " </td>"+
						"<td>" + v.damage + "</td><td>" + v.first_name + " " + v.last_name + "</td><td>" + v.brokenStatus + "</td>" +
						"<td>" + v.agent_FN + " " + v.agent_LN + "</td>" + 
						"<td>" + v.created_at + "</td><td>" + v.itemType + "</td>"+
						"<td>" + v.brand + "</td><td>" + v.model + "</td>" + 
						"<td>" + decodeURI(v.brokenSummary) + "</td></tr>";
						$('tbody#brokenSearchResult').append(newRow);
						});
					}else{
						$('tbody#brokenSearchResult').append("<tr><td colspan='9' class='text-center'> No Data Found.</td></tr>");
				}
				dataTable();
				$('div#spinner').addClass('hide');
				$('table#brokenSearchResult').removeClass('hide');
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				
			}
		});
		});
		
		
	});
	function dataTable(){
		
		$('table#brokenSearchResult').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Broken Items'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Broken Items', orientation: 'landscape',},

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