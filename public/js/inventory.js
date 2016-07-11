/**
 * @author ITojt04
 */

		 
			$('#data_1 .input-group.date').datepicker({
				todayBtn : "linked",
				keyboardNavigation : false,
				forceParse : false,
				calendarWeeks : true,
				autoclose : true,
				format: 'yyyy-mm-dd'
			});

		 
		
	// INPUT BORROW  
	 
		$('#inputBorrowBtn').click(function(e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "/inventory/borrow",
				data : $('form#inputBorrow').serialize(),
			}).done(function(data) {
				var msg = "";
				if (data != "") {
					$.each(data.errors, function(k, v) {
						msg = v + "\n" + msg;
					});

					swal("Oops...", msg, "warning");
				} else {
					swal("Success!", "Report Added!", "success");
					$('form#inputBorrow').each(function() {
						this.reset();
											window.location.reload();

					});

				}
			});
		});

	 
// Input return  
	 
			$('#returnBtn').click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "/inventory/return",
					data : $('form#returnForm').serialize(),
				}).done(function(data) {
					var msg = "";

					if (data != "") {
						$.each(data.errors, function(a, v) {
							msg = msg + v + "\n";
						});

						swal("Oops...", msg, "warning");

					} else {

						swal("Success!", "Item Status Updated", "success");
						window.location.reload();

					}
				});
			});
		 


	// search unique borrow  

	 
		$('#uniqueSrch').keyup(function() {
			$.ajax({
				type : "GET",
				url : "/inventory/srchUnique",
				data : {
					uniqueSrch : $('#uniqueSrch').val()
				},
			}).done(function(data) {
				
				if (data.itemsTbl['status'] == "Not Available" || data.itemsTbl['status'] == "Broken" || data.itemsTbl['status'] == "On supplier"){
					swal("Oops...","Cannot borrow item. Item is not available","warning");
					$('#unique-id').focus();
					return false;
				}
				else {				
				var itemData = String(data.itemsTbl['item_type']);
				var modelData = String(data.itemsTbl['model']);
				var brandData = String(data.itemsTbl['brand']);
				var uniqueData = String(data.itemsTbl['unique_identifier']);
				var itemNoData = String(data.itemsTbl['item_no']);
				
				$('#item').val(itemData);
				$('#model').val(modelData);
				$('#brand').val(brandData);
				$('#itemNo').val(itemNoData);
				$('#unique').val(uniqueData);
				$('#itemNoSrch').val(itemNoData);
					}
			})
		});

	 

	// search itemNo borrow 
	 
		$('#itemNoSrch').keyup(function() {
			$.ajax({
				type : "GET",
				url : "/inventory/srchItemNo",
				data : {
					itemNoSrch : $('#itemNoSrch').val()
				},
			}).done(function(data) {
				
				if (data.itemsTbl['status'] == "Not Available" || data.itemsTbl['status'] == "Broken" || data.itemsTbl['status'] == "On supplier"){
					swal("Oops...","Cannot borrow item. Item is not available","warning");
					$('#unique-id').focus();
					return false;
				}
				else {
				var itemData = String(data.itemsTbl['item_type']);
				var modelData = String(data.itemsTbl['model']);
				var brandData = String(data.itemsTbl['brand']);
				var uniqueData = String(data.itemsTbl['unique_identifier']);
				var itemNoData = String(data.itemsTbl['item_no']);
				$('#item').val(itemData);
				$('#model').val(modelData);
				$('#brand').val(brandData);
				$('#itemNo').val(itemNoData);
				$('#unique').val(uniqueData);
				$('#uniqueSrch').val(uniqueData);
				}
			})
		});

	 
		
		// advancedSrch borrow  
		
 
 var $rows = $('#tbody tr');
$('#advancedSrch').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
 
// Accounts  

 
			$('#usertypeBtn').click(function() {

				var data = {
					updates : []
				};

				$(".userTypeSelection").each(function() {
					var item = {
						id : $(this).data("id"),
						user_type : $(this).val()
					};

					data.updates.push(item);
				});

				console.log(data);

				swal({
					title : "Are you sure?",
					text : "Grant user(s) type",
					type : "warning",
					showCancelButton : true,

					confirmButtonText : "Yes",
					closeOnConfirm : false

				}, function() {

					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : "POST",
						url : "/inventory/manageAccounts",
						data : data,

					}).done(function(data) {
						if (data.success) {
							swal("Updated!", "User type granted!", "success");
							console.log(data);
							window.location.reload();
						} else {

						}

						//$('table#items').html(data);
					})
				});

			});
		 


		//Add item 
		 
			$('#addItemBtn').click(function(e) {
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "/inventory/addItems",
					data : $('form#addItemForm').serialize(),
				}).done(function(data) {
					var msg = "";
					if (data != "") {
						$.each(data.errors, function(k, v) {
							msg = msg + v + "\n";
						});

						swal("Oops...", msg, "warning");
					} else {

						swal("Success!", "Item Added!", "success");
						$('form#addItemForm').each(function() {
							this.reset();
						});
					window.location.reload();

					}
				});
			});
		 

					
	// if laptop or not (disable textbox) 
	$(function () {
			    $("#item").keyup(function () {
                     
                    var $value = $("#item").val().toLowerCase();
                    console.log($value);
                    if($value == "laptop") {
                        $("#date_deployed").attr("disabled",false);
                        
                    }
                    else if($value == "mouse") {
                    	$("#date_deployed").attr("disabled",false);
                        
                    }
                    else if($value == "headset") {
                    	$("#date_deployed").attr("disabled",false);
                        
                    }
                    else {
                    	
                        
                        $("#morning_shift").attr("disabled", "disabled");
                        $("#night_shift").attr("disabled", "disabled");
                        $("#date_deployed").attr("disabled","disabled");
                        $('#date_1').attr("disabled","disabled");
                 		$("#morning").val(" ");
                 		$("#night").val(" ");
                 		//$("#morning").prop("disabled", "disabled");
                        //$("#night").prop("disabled", "disabled");
            
                    }
                });
                           });
	 
			
			
			$('#date_deployed').change(function () {
				
			
           var $dateValue = $("#date_deployed").val();

            
             if($dateValue != null){
              if($('#date_deployed').attr("disabled")){	
             	    console.log($dateValue);
             	        $("#morning_shift").attr("disabled",true);
                        $("#night_shift").attr("disabled",true);
             	
             	}
 
             	else {
             	$("#morning_shift").attr("disabled",false);
             	$("#night_shift").attr("disabled",false);
             	}
             	
             }  
            
            if($dateValue =="") {
             	 		      
            
                        $("#morning_shift").attr("disabled",true);
                        $("#night_shift").attr("disabled",true);
             	}    
                
        });
        
        $('#date_deployed').keyup(function () {
				
			
           var $dateValue = $("#date_deployed").val();
     console.log($dateValue);		      
            
             if($dateValue != null){
             	$("#morning_shift").attr("disabled",false);
             	$("#night_shift").attr("disabled",false);
             }  
             if($dateValue == "") {
                        $("#morning_shift").attr("disabled",true);
                        $("#night_shift").attr("disabled",true);
             	}    
                
        });
        
				 	
				
		// search unique broken  

	 
		$('#uniqueSrchBrkn').keyup(function() {
			$.ajax({
				type : "GET",
				url : "/inventory/srchUnique",
				data : {
					uniqueSrch : $('#uniqueSrchBrkn').val()
				},
			}).done(function(data) {
				if (data.itemsTbl['status'] == "Broken"){
					swal("Oops...","Cannot report item. Item is already declared as broken","warning");
					$('#uniqueSrchBrkn').focus();
					return false;
				}
				else {
				
				
				var itemData = String(data.itemsTbl['item_type']);
				var modelData = String(data.itemsTbl['model']);
				var brandData = String(data.itemsTbl['brand']);
				var uniqueData = String(data.itemsTbl['unique_identifier']);
				var itemNoData = String(data.itemsTbl['item_no']);
				
				$('#item').val(itemData);
				$('#model').val(modelData);
				$('#brand').val(brandData);
				$('#itemNo').val(itemNoData);
				$('#unique').val(uniqueData);
				$('#itemNoSrchBrkn').val(itemNoData);
				}	
			})
		});

	 
				
	// search itemNo broken 
	 
		$('#itemNoSrchBrkn').keyup(function() {
			$.ajax({
				type : "GET",
				url : "/inventory/srchItemNo",
				data : {
					itemNoSrch : $('#itemNoSrchBrkn').val()
				},
			}).done(function(data) {

				if (data.itemsTbl['status'] == "Broken"){
					swal("Oops...","Cannot report item. Item is already declared as broken","warning");
					$('#unique-id').focus();
					return false;
				}
				else {
				
				var itemData = String(data.itemsTbl['item_type']);
				var modelData = String(data.itemsTbl['model']);
				var brandData = String(data.itemsTbl['brand']);
				var uniqueData = String(data.itemsTbl['unique_identifier']);
				var itemNoData = String(data.itemsTbl['item_no']);
				$('#item').val(itemData);
				$('#model').val(modelData);
				$('#brand').val(brandData);
				$('#itemNo').val(itemNoData);
				$('#unique').val(uniqueData);
				$('#uniqueSrchBrkn').val(uniqueData);
				
				}
			})
		});

	 
		// INPUT BROKEN  
	 
		$('#inputBrokenBtn').click(function(e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "/inventory/broken",
				data : $('form#inputBroken').serialize(),
			}).done(function(data) {
				var msg = "";
				if (data != "") {
					$.each(data.errors, function(k, v) {
						msg = v + "\n" + msg;
					});

					swal("Oops...", msg, "warning");
				} else {
					swal("Success!", "Report Added!", "success");
					$('form#inputBroken').each(function() {
						this.reset();
											window.location.reload();

					});

				}
			});
		});

	 
	
	// broken Updates  

 
			$('#statusBtn').click(function() {

				var data = {
					updates : []
				};

				$(".statusSelection").each(function() {
					var item = {
						id : $(this).data("id"),
						status : $(this).val()
					};

					data.updates.push(item);
				});

				console.log(data);

				swal({
					title : "Are you sure?",
					text : "Change item status",
					type : "warning",
					showCancelButton : true,

					confirmButtonText : "Yes",
					closeOnConfirm : false

				}, function() {

					$.ajax({
						headers : {
							'X-CSRF-Token' : $('input[name="_token"]').val()
						},
						type : "POST",
						url : "/inventory/brokenUpdate",
						data : data,

					}).done(function(data) {
						
							swal("Updated!", "Status Updated!", "success");
							console.log(data);
							window.location.reload();
						
						//$('table#items').html(data);
					})
				});

			});
		 
	