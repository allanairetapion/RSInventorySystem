/**
 * 
 */

$(function() {
	$(document).ready(function(){
		$('.input-group.date.dateBroken').datepicker({
		    format : 'yyyy-mm-dd',
		    todayBtn: "linked"
			});
		

	$('.i-checks').iCheck({
		checkboxClass : 'icheckbox_square-green',
		radioClass : 'iradio_square-green',
	});
	
	});

	
	$('button.advancedSearch').click(function() {
		$('div#advancedSearch').slideToggle();
	});
	$('.input-group.date.dateArrived').datepicker({
	    
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	
	//Add Item
	
	
	//Borrow Form
	$('select#borrowUniqueId').change(function(){				
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#borrowUniqueId').val() } ,
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
				if(data.info['itemStatus'] != "Available"){
					$('div.unique_id').addClass('has-error');
					$('span.unique_id').text("This Item is not Available").show();
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
			}).done(function(data){
				if(data.success == false){
					borrowItem.ladda('stop');
					if(data.errors["unique_id"]){
						
						$('span.unique_id').text(data.errors["unique_id"]).show();
						
						
					if(data.errors["unique_id"] == "The selected unique id is invalid."){
							$('span.unique_id').text("This Item is not Available").show();
							$('div.unique_id').addClass('has-error');
						}
						$('div.unique_id').addClass('has-error');
					}
					
					if(data.errors["itemNo"]){
						$('span.itemNo').text(data.errors["itemNo"]).show();
						$('div.itemNo').addClass('has-error');
					}
					if(data.errors["stationNo"]){
						$('span.stationNo').text(data.errors["stationNo"]).show();
						$('div.stationNo').addClass('has-error');
					}
					if(data.errors["borrower"]){
						$('span.borrower').text(data.errors["borrower"]).show();
						$('div.borrower').addClass('has-error');
					}
					if(data.errors["dateBorrowed"]){
						$('span.dateBorrowed').text(data.errors["dateBorrowed"]).show();
						$('div.dateBorrowed').addClass('has-error');
						}
					
				}else{
					
					borrowItem.ladda('stop');
					$('form.borrowItem').trigger('reset');
					
					var table = $('table#borrow').data('footable');
					
					var newRow = "<tr><td>" + data.response['unique_id'] + "</td><td>" + data.response['itemNo'] + " </td>"+
						"<td>" + data.response.itemType + "</td><td>" + data.response['brand'] + "</td><td>" + data.response['model'] + "</td>" +
						"<td>" + data.response['first_name'] + " " +data.response['last_name'] + "</td><td>" + data.response['borrower'] + "</td>"+
						"<td>" + data.response['borrowerStationNo'] + "</td><td>" + data.response['created_at'] + "</td></tr>";
					
					$('tbody').prepend(newRow);
					table.redraw();
			
					$('#myModal').modal('hide');
					swal('','Item Borrowed','success');
					
					$('form.itemInfo').hide();
					
				}
				
			});
		});
		
		
	//Return Form
	$('select#returnUniqueId').change(function(){
		
		
		
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
			data : { item : $('select#returnUniqueId').val() } ,
		}).done(function(data){
			if(data.success == true){
				$('i.fa-pulse').hide();
				$('div.itemNotfound').hide();
				$('form.borrowInfo').show();
				
				$('input.infoId').val(data.info['unique_id']);
				$('input.infoItemNo').val(data.info['itemNo']);
				$('input.infoBorrower').val(data.borrow['borrower']);
				$('input.infoStationNo').val(data.borrow['borrowerStationNo']);
				$('input.infoItemType').val(data.info['itemType']);
				$('input.infoBrand').val(data.info['brand']);
				$('input.infoModel').val(data.info['model']);
				$('input.infodateBorrowed').val(data.borrow['created_at']);
				
				if(data.info['itemStatus'] == "Available"){
					$('div.unique_id').addClass('has-error');
					$('span.unique_id').text("This Item is already returned").show();
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
				var table = $('table#return').data('footable');
				
				var newRow = "<tr><td>"+ data.response['unique_id'] + "</td>"+
				"<td>" + data.response['itemNo']+ "</td><td>" + data.response['itemType']+" </td>" +
				"<td>" + data.response['brand'] +"</td><td>"+ data.response['model']+"</td>" +
				"<td>" + data.response['borrower'] +"</td>"+
				"<td>" + data.response['first_name']+" "+data.response['last_name']+"</td>"+
				"<td>"+ data.response['created_at'] +"</td></tr>";
				
				table.redraw();
				$('tbody').prepend(newRow);
				$('#myModal').modal('hide');
				swal('','Item Returned','success');
				
				$('form.itemInfo').hide();
				
			}
			
		});
	});
	
	// Issue Form
	$('select#issueUniqueId').change(function(){				
		
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('span.text-danger').hide();
		$('div.form-group').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('select#issueUniqueId').val() } ,
		}).done(function(data){
			if(data.success == true){
				
				if(data.info['itemStatus'] == "With Issue"){
					$('div.issueUnique_id').addClass('has-error');
					$('span.issueUnique_id').text("This Item is already Reported").show();
					
					$('i.fa-pulse').hide();
					
					if($('input.uniqueId').val() == ""){
						$('div.itemNotfound').hide();
						$('input.infoItemNo').val("");
					}else{
						$('div.itemNotfound').show();
					}
				}else{
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
		}).done(function(data){
			if(data.success == false){
				issueItem.ladda('stop');
				if(data.errors["unique_id"]){
					
					$('span.issueUnique_id').text(data.errors["unique_id"]).show();
					
					
					if(data.errors["unique_id"] == "The selected unique id is invalid."){
						$('span.issueUnique_id').text("This Item is already Reported").show();
						$('div.issueUnique_id').addClass('has-error');
					}
					$('div.issueUnique_id').addClass('has-error');
				}
				if(data.errors["itemNo"]){
					$('span.itemNo').text(data.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				if(data.errors["damage"]){
					$('span.itemDamage').text(data.errors["damage"]).show();
					$('div.itemDamage').addClass('has-error');
				}
				if(data.errors["issue"]){
					$('span.itemIssue').text(data.errors["issue"]).show();
					$('div.itemIssue').addClass('has-error');
				}
				if(data.errors["dateReported"]){
					$('span.dateReported').text(data.errors["dateReported"]).show();
					$('div.dateReported').addClass('has-error');
				}
			}else{
				issueItem.ladda('stop');
				var table = $('table#issue').data('footable');
				$('form.issueItem').trigger('reset');
				$('tbody.issueItem').prepend("<tr><td>"+ data.response['unique_id'] + "</td>"+
						"<td>" + data.response['itemNo']+ "</td><td>" + data.response['damage']+" </td>" +
						"<td>" + decodeURI(data.response['issue']) +"</td><td>"+ data.response['first_name']+" "+ data.response['last_name']+"</td>" +
						"<td>" + data.response['created_at']+ "</td></tr>");
				table.redraw();
				$('#issueReport').modal('hide');
				swal('','Item Reported','success');
				
				$('form.itemInfo').hide();
				
			}
			
		});
		
	});
	
	$('select#repairUniqueId').change(function(){				
		$('span.unique_id').text("").hide();
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('div.unique_id').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/issueInfo",
			data : { item : $('select#repairUniqueId').val() } ,
		}).done(function(data){
			if(data.success == true){
				
				if(data.info['itemStatus'] != "With Issue"){
					$('div.repairUnique_id').addClass('has-error');
					$('span.repairUnique_id').text("This Item is already Repaired").show();
					
					$('i.fa-pulse').hide();
					if($('input.uniqueId').val() == ""){
						$('div.itemNotfound').hide();
						$('input.infoItemNo').val("");
					}else{
						$('div.itemNotfound').show();
					}
				}else{
					$('i.fa-pulse').hide();
					$('div.itemNotfound').hide();
					$('form.itemInfo').show();
					$('input.repairId').val(data.info['unique_id']);
					$('input.repairItemNo').val(data.info['itemNo']);
					$('input.repairDamage').val(data.info['damage']);
					$('div#repairIssue').html(data.info['issue']).text();
					$('input.repairBroken').val(data.info['created_at']);
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
				if(data.errors["unique_id"]){
					
					$('span.repairUnique_id').text(data.errors["unique_id"]).show();
					
					
					if(data.errors["unique_id"] == "The selected unique id is invalid."){
						$('span.repairUnique_id').text("This Item is already Returned").show();
						$('div.repairUnique_id').addClass('has-error');
					}
					$('div.unique_id').addClass('has-error');
				}
				if(data.errors["itemNo"]){
					$('span.itemNo').text(data.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				
				if(data.errors["dateRepair"]){
					$('span.dateRepair').text(data.errors["dateRepair"]).show();
					$('div.dateRepair').addClass('has-error');
				}
			}else{
				repairItem.ladda('stop');
				$('form.returnItem').trigger('reset');
				var table = $('table#issue').data('footable');
				
				table.removeRow($('tr#'+data.response['unique_id']));
				$('#repairReport').modal('hide');
				swal('','Item Repaired','success');
				
				$('form.itemInfo').hide();
				
			}
			
		});
	});
	// Broken Item
	
	$('input.brokenUniqueId').change(function(){				
		$('span.unique_id').text("").hide();
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('div.unique_id').removeClass('has-error');
		$('div.input-group').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('input.brokenUniqueId').val() } ,
		}).done(function(data){
			if(data.success == true){
				$('input.brokenItemNo').val(data.info['itemNo']);
			
				if(data.info['itemStatus'] == "Broken"){
					$('div.repairUnique_id').addClass('has-error');
					$('span.repairUnique_id').text("This Item is already Reported").show();
					
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
		
		console.log($('form.returnItem').serialize());
		
		$.ajax({
			type : "POST",
			url : "/inventory/brokenItem",
			data : $('form#brokenItem').serialize() ,
		}).done(function(data){
			if(data.success == false){
				issueItem.ladda('stop');
				if(data.errors["unique_id"]){
					
					$('span.brokenUnique_id').text(data.errors["unique_id"]).show();
					
					
					if(data.errors["unique_id"] == "The selected unique id is invalid."){
						$('span.brokeneUnique_id').text("This Item is already Reported").show();
						$('div.brokenUniqueId').addClass('has-error');
					}
					$('div.brokenUniqueId').addClass('has-error');
				}
				if(data.errors["itemNo"]){
					$('span.itemNo').text(data.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				if(data.errors["damage"]){
					$('span.brokenDamage').text(data.errors["damage"]).show();
					$('div.brokenDamage').addClass('has-error');
				}
				if(data.errors["summary"]){
					$('span.brokenSummary').text(data.errors["summary"]).show();
					$('div.brokenSummary').addClass('has-error');
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
				issueItem.ladda('stop');
				$('form.brokenItem').trigger('reset');
				var table = $('table#broken').data('footable');
					
					
				$('tbody#brokenItem').prepend("<tr><td> <input type='checkbox' class='i-checks brokenItem' value='"+data.response['unique_id'] +"'/> &nbsp;"+ 
						data.response['unique_id'] + "</td>"+
						"<td>" + data.response['itemNo']+ "</td><td>" + data.response['damage']+" </td>" +
						"<td>"+data.response['brokenStatus']+"</td><td>"+ data.response['first_name']+" "+ data.response['last_name']+"</td>" +
						"<td>" + data.response['created_at']+ "</td><td>"+ decodeURI(data.response['brokenSummary']) +"</tr>");
				
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				table.redraw();
				$('#brokenReport').modal('hide');
				
				swal('','Item Reported','success');
				
				
			}
			
		});
	});
	// create agent
	$('button.add-account').click(
			function(e) {
				$('div.form-groups').removeClass('has-error');
				$('span.text-danger').hide();
				$('label.text-danger').hide();
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "/checkEmail",
					data : $('.agentForm').serialize(),

				}).done(
						function(data) {
							if (data.response != "") {
								console.log(data.errors);
								if (data.errors['email']) {
									$('div.email').addClass('has-error');
									$('span.email').text(
											'*' + data.errors['email']).show();
								}
								if (data.errors['firstname']) {
									$('div.fname').addClass('has-error');
									$('label.fname').text(
											'*' + data.errors['firstname'][0])
											.show();
								}
								if (data.errors['lastname']) {
									$('div.lname').addClass('has-error');
									$('label.lname').text(
											'*' + data.errors['lastname'][0])
											.show();
								}
								if (data.errors['user_type']) {
									$('div.usertype').addClass('has-error');
									$('span.usertype').text(
											'*' + data.errors['user_type'])
											.show();
								}

							} else {
								// Validation Success Tell user to input his/her
								// password to continue/confirm adding
								console.log($('.agentForm').serialize());
								validateSuccess();
							}

						});
			});

	function validateSuccess() {
		swal(
				{
					title : "Are you sure you want to create new agent?",
					text : "If you are sure, Please enter your password.",
					type : "input",
					inputType : "password",
					showCancelButton : true,
					closeOnConfirm : false,
					showLoaderOnConfirm : true,
					disableButtonsOnConfirm : true,
				},
				function(inputValue) {
					if (inputValue != "") {
						$.ajax(
								{
									headers : {
										'X-CSRF-Token' : $(
												'input[name="_token"]').val()
									},
									type : 'post',
									url : '/inventory/verifyPassword',
									data : {
										password : inputValue
									},
								}).done(function(data) {
							if (data.success == true) {

								addNew();
							} else {
								swal.showInputError("Wrong Password");
								return false;
							}
						});
					} else {
						swal
								.showInputError("You need to type in your password in order to do this!");
						return false;
					}
				});
	}
	;
	function addNew() {
		$.ajax({
			type : "POST",
			url : "/inventory/register",
			data : $('form.agentForm').serialize(),
		}).done(function() {
			sendActivation();
		});

	}
	;
	function sendActivation() {
		$.ajax({
			type : "POST",
			url : "/admin/sendActivate",
			data : $('form.agentForm').serialize(),
			success: function(){
				swal({
					title : 'Success',
					text : 'New user has been added',
					type : 'success'
				}, function() {
					window.location.href = "/admin/agents";
				});
			},
			error: function () {
				sendActivation();
			},
		});
	}
	;
	
});