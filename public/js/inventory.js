/**
 * 
 */

$(function() {
	$('button.advancedSearch').click(function() {
		$('div#advancedSearch').slideToggle();
	});
	$('.input-group.date.dateArrived').datepicker({
	    
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	
	//Add Item
	$('button.addItem').click(function(){
		$('div.form-group').removeClass('has-error');
		$('span.text-danger').hide();
		console.log( $("form.addItem").serialize() );
		$.ajax({
			type : "POST",
			url : "/inventory/addItem",
			data :  $("form.addItem").serialize(),
		}).done(function(data){
			
			if(data.success == false){
				
				if(data.errors["unique_id"]){
					$('span.unique_id').text(data.errors["unique_id"]).show();
					$('div.unique_id').addClass('has-error');
				}
				if(data.errors["itemNo"]){
					$('span.itemNo').text(data.errors["itemNo"]).show();
					$('div.itemNo').addClass('has-error');
				}
				if(data.errors["company"]){
					$('span.company').text(data.errors["company"]).show();
					$('div.company').addClass('has-error');
				}
				if(data.errors["stationNo"]){
					$('span.stationNo').text(data.errors["stationNo"]).show();
					$('div.stationNo').addClass('has-error');
				}
				if(data.errors["brand"]){
					$('span.brand').text(data.errors["brand"]).show();
					$('div.brand').addClass('has-error');
				}
				if(data.errors["model"]){
					$('span.model').text(data.errors["model"]).show();
					$('div.model').addClass('has-error');
				}
				if(data.errors["itemType"]){
					$('span.itemType').text(data.errors["itemType"]).show();
					$('div.itemType').addClass('has-error');
				}
				if(data.errors["dateArrived"]){
					$('span.dateArrived').text(data.errors["dateArrived"]).show();
					$('div.dateArrived').addClass('has-error');
				}
			}else if(data.success == true){
				swal('','New Item Added','success');
				$('form.addItem').trigger('reset');
			}
			
			
			
		});
	});
	
	//Borrow Form
	$('input.uniqueId').change(function(){
		
		
		$('span.unique_id').text("").hide();
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('div.unique_id').removeClass('has-error');
		
		$.ajax({
			type : "GET",
			url : "/inventory/itemInfo",
			data : { item : $('input.uniqueId').val() } ,
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
					
					$('tbody.borrowItem').prepend("<tr><td>"+ data.response['itemType']+" </td>" +
							"<td>" + data.response['brand'] +"</td><td>"+ data.response['model']+"</td>" +
							"<td>" + data.response['unique_id'] + "</td><td>" + data.response['itemNo']+ "</td>" +
							"<td>" + data.response['first_name']+" "+data.response['last_name']+"</td>"+
							"<td>" + data.response['borrower'] +"</td><td>" +data.response['borrowerStationNo']+ "</td>" +
							"<td>" + data.response['dateBorrowed'] +"</td><td></td</tr>");
					$('#myModal').modal('hide');
					swal('','Item Borrowed','success');
					
					$('form.itemInfo').hide();
					
				}
				
			});
		});
		
		
	//Return Form
	$('input.borrowUniqueId').change(function(){
		
		
		$('span.unique_id').text("").hide();
		$('i.fa-pulse').show();
		$('form.itemInfo').hide();
		$('div.itemNotfound').hide();
		$('div.unique_id').removeClass('has-error');
		$('form.borrowInfo').hide();
		$.ajax({
			type : "GET",
			url : "/inventory/borrowInfo",
			data : { item : $('input.borrowUniqueId').val() } ,
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
				$('input.infodateBorrowed').val(data.borrow['dateBorrowed']);
				
				
				
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
				$('tbody.returnItem').prepend("<tr><td>"+ data.response['itemType']+" </td>" +
						"<td>" + data.response['brand'] +"</td><td>"+ data.response['model']+"</td>" +
						"<td>" + data.response['unique_id'] + "</td><td>" + data.response['itemNo']+ "</td>" +
						"<td>" + data.response['borrower'] +"</td>"+
						"<td>" + data.response['first_name']+" "+data.response['last_name']+"</td>"+
						"<td>"+ data.response['dateReturned'] +"</td><td></td</tr>");
				$('#myModal').modal('hide');
				swal('','Item Returned','success');
				
				$('form.itemInfo').hide();
				
			}
			
		});
	});
	
	
});