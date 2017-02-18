$(function() {
	
	$(document).ready(function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('.chosen-select', this).chosen();
		$('div#brokenAdvancedSearch').hide();
		$('table').DataTable({
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
            ],
            "createdRow": function( row, data, dataIndex ) {        		
            	$('td',row).eq(0).wrapInner("<a href='#' class='itemBroken'></a>");	
		    }

        });
		$('.i-checks').iCheck({
			checkboxClass : 'icheckbox_square-green',
			radioClass : 'iradio_square-green',
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
			       items.push($(this).attr('id'));
			   		$(this).parents('tr').find("td:nth-child(6)").text(mark);
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
					var table = $('table#broken').DataTable();
					
					swal({
						title:"Success",
						text: "Successfully Marked as "+ mark,
						type: "success",
						},function(){
							$('input:checkbox.brokenItem:checked').each(function () {
								if(mark != "Repaired"){
									$(this).parents('tr').find("td:nth-child(6)").text(mark);
								}else{									
									table.row( $(this).parents('tr') ).remove().draw();
								}
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
				table.clear();
				
				$.each(data.response,function(i, v) {
					var node = table.row.add([
								               v.itemNo,
								               v.itemType,
								               v.brand + " - " + v.model,
								               v.damage,
								               v.first_name + " " + v.last_name,
								               v.brokenStatus,
								               v.agent_FN + " " + v.agent_LN,
								               v.created_at]).node();
					$(node).attr('value', v.brokenSummary);
				});
					
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#brokenSearchResult').removeClass('hide');
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
				table.row.add([
				               data.response['itemNo'],
				               data.response['itemType'],
				               data.response['brand'] + " - " + data.response['model'],
				               data.response['damage'],
				               data.response['first_name'] + " " + data.response['last_name'],
				               data.response['brokenStatus'],
				               data.response['created_at']
				               ]);
				table.draw();
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});				
				$('div#brokenReport').modal('hide');
				
				swal('','Item Reported','success');
				
				
			}
			
		});
	});
	$('button#brokenSearch').click(function(){
		$('div#brokenSearchResult').addClass('hide');
		$('div#brokenResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/broken/search",
			data : {brokenSearch : $("input#brokenSearch").val()},
			success: function(data){
				var table = $('table#brokenSearchResult').DataTable();
				table.clear();
				
				$.each(data.response,function(i, v) {
					var node = table.row.add([
								               v.itemNo,
								               v.itemType,
								               v.brand + " - " + v.model,
								               v.damage,
								               v.first_name + " " + v.last_name,
								               v.brokenStatus,
								               v.agent_FN + " " + v.agent_LN,
								               v.created_at]).node();
					$(node).attr('value', v.brokenSummary);
				});
					
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#brokenSearchResult').removeClass('hide');
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
			}
		});
		});
		
		
	});
	$(document).on('click', 'a.itemBroken', function() {
		var item = $(this);
		$('h4.itemBroken').text($(item).text());
		$('a.itemDetails').attr('href','/inventory/items/' + $(item).text());
		$('span.brokenDamage').text($(item).parents('tr').find("td:nth-child(4)").text());
		$('span.brokenSummary').html($(item).parents('tr').attr('value'));
		$('div#itemBroken').modal('toggle');
	});
});