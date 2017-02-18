$(function() {
	$(document).ready(function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('.chosen-select', this).chosen();
		$('.input-group.date.dateReported').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});

		$('.input-group.date.dateRepair').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
		$('table').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc. \n' + 'Items With Issue'},
                {extend: 'pdf', title: 'Remote Staff Inc. \n' + 'Items With Issue', orientation: 'landscape',},

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
            	$('td',row).eq(0).wrapInner("<a href='#' class='itemIssue'></a>");											
		    }

        });
		$('div#issueSummary').summernote({
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
						 ]   });
		
		});

	$('div.modal').on('hidden.bs.modal', function() {
		$('form.itemInfo').hide();
		$('i.fa-pulse').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
	});
	$('button.issueTicketSearch').click(function(){
		$('div#issueSearchResult').addClass('hide');
		$('div#issueResult').hide(function(){
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/issue/advancedSearch",
			data : $('form.issueTicketSearch').serialize(),
			success: function(data){
				var table = $('table#issueSearchResult').DataTable();
				table.clear();
				
				$.each(data.response,function(i, v) {
					var node = table.row.add([
								               v.itemNo,
								               v.itemType,
								               v.brand + " - " + v.model,
								               v.damage,
								               v.first_name + " " + v.last_name,
								               v.agent_FN + " " + v.agent_LN,
								               v.created_at]).node();
					$(node).attr('value', v.issue);
					table.draw();
				});
				
				$('div#spinner').addClass('hide');
				$('div#issueSearchResult').removeClass('hide');
			}
		});
		});
	});
	
	// Issue Form
	$('select#issueItemNo').change(function(){				
		
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#issueItemNo').val() } ,
		}).done(function(data){
			if(data.success == true){
				
				if(data.info['itemStatus'] == "With Issue"){
					$('div.issueUnique_id').addClass('has-error');
					$('span.issueUnique_id').text("This Item is already Reported").show();
					
					$('i.fa-pulse').hide();
					
					if($('input.uniqueId').val() == ""){
						$('div.itemNotfound').hide();
						
					}else{
						$('div.itemNotfound').show();
					}
				}else{
					$('i.fa-pulse').hide();
					$('div.itemNotfound').hide();
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
	
	var issueItem = $('button.issueItem').ladda();
	
	issueItem.click(function(){
		$('input#itemIssue').val($('div#issueSummary').summernote('code'));
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		console.log($('form.returnItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/issueItem",
			data : $('form#issueItem').serialize() ,
			error : function (data){
				var errors = data.responseJSON;
				issueItem.ladda('stop');
				
				if(errors.errors["itemNo"]){
					if(errors.errors['itemNo'] == "The selected item no is invalid."){
						$('span.issueItemNo').text("This Item is already Reported").show();
						$('div.issueItemNo').addClass('has-error');
					}
					$('span.issueItemNo').text(errors.errors["itemNo"]).show();
					$('div.issueItemNo').addClass('has-error');
				}
				if(errors.errors["item_user"]){
					$('span.itemUser').text(errors.errors["item_user"]).show();
					$('div.itemUser').addClass('has-error');
				}
				if(errors.errors["damage"]){
					$('span.itemDamage').text(errors.errors["damage"]).show();
					$('div.itemDamage').addClass('has-error');
				}
				if(errors.errors["issue"]){
					$('span.itemIssue').text(errors.errors["issue"]).show();
					$('div.itemIssue').addClass('has-error');
				}
				if(errors.errors["dateReported"]){
					$('span.dateReported').text(errors.errors["dateReported"]).show();
					$('div.dateReported').addClass('has-error');
				}
			},
			success : function(data){
				issueItem.ladda('stop');
				var table = $('table#issue').DataTable();
				table.row.add([
				               data.response['itemNo'],
				               data.response['itemType'],
				               data.response['brand'] + " - " + data.response['model'],
				               data.response['damage'],
				               data.response['first_name'] + " " + data.response['last_name'],
				               data.response['agent_FN'] + " " + data.response['agent_LN'],
				               data.response['created_at']]);
				table.draw();
				$('#issueReport').modal('hide');
				swal('','Item Reported','success');
				$('form#issueItem').trigger('reset');
				$('form.itemInfo').hide();
				
			}
		});
		
	});
	
	$('select#repairItemNo').change(function(){				
		$('div.form-group').removeClass('has-error');
		$('span.help-block').hide();
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#repairItemNo').val() } ,
		}).done(function(data){
			if(data.success == true){
				console.log(data.info['itemStatus']);
				if(data.info['itemStatus'] != "With Issue" && data.info['itemStatus'] != "Broken"){
					$('div.repairItemNo').addClass('has-error');
					$('span.repairItemNo').text("This Item is already Repaired").show();
					
					$('i.fa-pulse').hide();
					
				}
			}
		});
	});
	
	var repairItem = $('button.repairItem').ladda();
	
	repairItem.click(function(){
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		console.log($('form#repairItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/repairItem",
			data : $('form#repairItem').serialize() ,
		}).done(function(data){
			if(data.success == false){
				repairItem.ladda('stop');
				
				if(data.errors["itemNo"]){
					$('span.repairItemNo').text(data.errors["itemNo"]).show();
					$('div.repairItemNo').addClass('has-error');
				}
				
				if(data.errors["dateRepair"]){
					$('span.dateRepair').text(data.errors["dateRepair"]).show();
					$('div.dateRepair').addClass('has-error');
				}
			}else{
				repairItem.ladda('stop');
				var table = $('table#issueResult').DataTable();
				$('tr#'+$('repairItemNo').val()).remove();
				table.draw();
				$('div#repairReport').modal('toggle');
				swal('','Item Repair Reported','success');				
				$('form.returnItem').trigger('reset');			
				$('form.itemInfo').hide();			
			}
			
		});
	});
	$('button#issueSearch').click(function(){
		$('div#issueSearchResult').addClass('hide');
		$('div#issueResult').hide(function(){
			console.log('click');
			$('div#spinner').removeClass('hide');
			$.ajax({
			type : "get",
			url : "/inventory/issue/search",
			data : {issueSearch : $("input#issueSearch").val()},
			success: function(data){
				var table = $('table#issueSearchResult').DataTable();
				table.clear();
				
				$.each(data.response,function(i, v) {
					var node = table.row.add([
								               v.itemNo,
								               v.itemType,
								               v.brand + " - " + v.model,
								               v.damage,
								               v.first_name + " " + v.last_name,
								               v.agent_FN + " " + v.agent_LN,
								               v.created_at]).node();
					$(node).attr('value', v.issue);
				});
				table.draw();
				$('div#spinner').addClass('hide');
				$('div#issueSearchResult').removeClass('hide');
			}
		});
		});
		
		
	});
	
	$(document).on('click', 'a.itemIssue', function() {
		var item = $(this);
		$('h4.itemIssue').text($(item).text());
		$('a.itemDetails').attr('href','/inventory/items/' + $(item).text());
		$('span.issueDamage').text($(item).parents('tr').find("td:nth-child(4)").text());
		$('span.issueSummary').html($(item).parents('tr').attr('value'));
		$('div#itemIssue').modal('toggle');
	});
});