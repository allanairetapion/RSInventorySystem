$(function() {
	$(document).ready(function(){
		$('.chosen-select', this).chosen();
		$('span.text-danger').hide();
		$('div#itemDescription').summernote({
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
						[ 'height', [ 'height' ] ] ]
			});
		$('table#itemList').DataTable({
            dom: '<"html5buttons"B>Tgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Remote Staff Inc \n Add Items'},
                {extend: 'pdf', title: 'Remote Staff Inc \n Add Items'},

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
		$('form').trigger('reset');
		
		Dropzone.options.itemPhoto = {
				autoDiscover : false,
				paramName: "photo",
				url: "/inventory/addItem",
				acceptedFiles: 'image/*',
				autoProcessQueue: false,
				addRemoveLinks: true,
				uploadMultiple: true,
				parallelUploads: 100,
				maxFilesize: 2,
				maxFiles: 4,
				init: function() 
			    {
				    
			    thisDropzone = this;
			    var totalFileSize = 0;
			    var createTicket = $('button.create-ticket').ladda();
			    
			    this.on("addedfile", function (file) {
                    var _this = this;
                    
                    if ($.inArray(file.type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']) == -1) {
                        swal('Ooops','You can only upload image files','info');
                        _this.removeFile(file);
                        return false;
                    }
                    totalFileSize += file.size;
                    if(totalFileSize > 10485760){
                    	_this.removeFile(file);
                    	swal('Ooops','You exceeded the total file upload size limit (10 MB)','info');
                        }
                    
                });
			    this.on("removedfile", function (file) {
			    	totalFileSize -= file.size;
				    });
                this.on("completemultiple", function(file) {
                	file.status = Dropzone.QUEUED;
                });
                
			    $('button.addItem').click(function(e) {
			    	$('div.form-group').removeClass('has-error');
					$('span.text-danger').hide();
			    	e.preventDefault();
					e.stopPropagation();
					console.log($("select#itemType").val());
					$('input#specification').val($('div#itemDescription').summernote('code'));
					
					if (thisDropzone.getQueuedFiles().length > 0) {
						thisDropzone.processQueue();
						createTicket.ladda('start');
					  }
					  else {
						 
						createTicket.ladda('start');
						  console.log('here');
						  $('input[type="hidden"].topic').val($('div.ticketsummernote').summernote('code'));
							
							
							$('div').removeClass('has-error');
							e.preventDefault();

							$.ajax({
								type : "POST",
								url : "/inventory/addItem",
								data :  $("form.addItem").serialize(),
								error: function(data){
									var errors = data.responseJSON;
									if(errors.errors["itemTag"]){
										$('span.itemTag').text(errors.errors["itemTag"]).show();
										$('div.itemTag').addClass('has-error');
									}
									if(errors.errors["serial_no"]){
										$('span.serial_no').text(errors.errors["serial_no"]).show();
										$('div.serial_no').addClass('has-error');
									}
									if(errors.errors["serviceTag"]){
										$('span.serviceTag').text(errors.errors["serviceTag"]).show();
										$('div.serviceTag').addClass('has-error');
									}
									if(errors.errors["company"]){
										$('span.company').text(errors.errors["company"]).show();
										$('div.company').addClass('has-error');
									}
									if(errors.errors["stationNo"]){
										$('span.stationNo').text(errors.errors["stationNo"]).show();
										$('div.stationNo').addClass('has-error');
									}
									if(errors.errors["brand"]){
										$('span.brand').text(errors.errors["brand"]).show();
										$('div.brand').addClass('has-error');
									}
									if(errors.errors["specification"]){
										$('span.specification').text(errors.errors["specification"]).show();
										$('div.specification').addClass('has-error');
									}
									if(errors.errors["model"]){
										$('span.model').text(errors.errors["model"]).show();
										$('div.model').addClass('has-error');
									}
									if(errors.errors["itemType"]){
										$('span.itemType').text(errors.errors["itemType"]).show();
										$('div.itemType').addClass('has-error');
									}
									if(errors.errors["dateArrived"]){
										$('span.dateArrived').text(errors.errors["dateArrived"]).show();
										$('div.dateArrived').addClass('has-error');
									}
									},
									success: function(data){
										addNewRow(data);
										}
							});
					  }
			    });
			    

				this.on("sendingmultiple", function(data, xhr, formData) {
					formData.append("_token", $('[name=_token').val());
		            formData.append("serial_no", $("input#serial_no").val());
		            formData.append("itemNo", $("input#itemNo").val());
		            formData.append("brand", $("input#brand").val());
		            formData.append("model", $("input#model").val());
		            formData.append("company", $("input#company").val());
		            formData.append("stationNo", $("input#stationNo").val());
		            formData.append("specification", $("input#specification").val());
		            formData.append("itemType", $("select#itemType").val());
		            formData.append("dateArrived", $("input#dateArrived").val());
		           
		        });
		        
				this.on('error', function (file,response) {
					if(file.size > 2097152){
						this.RemoveFile(file);
						return false;
						}
					file.status = Dropzone.QUEUED;

					$(file.previewElement).find('.dz-error-message').text("An error occurred");
					
					});
				this.on("errormultiple", function(file, response, xhr) {
					if(file.size > 2097152){
						this.RemoveFile(file);
						return false;
						}
					file.status = Dropzone.QUEUED;
					$(file.previewElement).find('.dz-error-message').text("An error occurred");
					if(response.errors["serial_no"]){
						$('span.serial_no').text(response.errors["serial_no"]).show();
						$('div.serial_no').addClass('has-error');
					}
					if(response.errors["itemNo"]){
						$('span.itemNo').text(response.errors["itemNo"]).show();
						$('div.itemNo').addClass('has-error');
					}
					if(response.errors["company"]){
						$('span.company').text(response.errors["company"]).show();
						$('div.company').addClass('has-error');
					}
					if(response.errors["stationNo"]){
						$('span.stationNo').text(response.errors["stationNo"]).show();
						$('div.stationNo').addClass('has-error');
					}
					if(response.errors["brand"]){
						$('span.brand').text(response.errors["brand"]).show();
						$('div.brand').addClass('has-error');
					}
					if(response.errors["specification"]){
						$('span.specification').text(response.errors["specification"]).show();
						$('div.specification').addClass('has-error');
					}
					if(response.errors["model"]){
						$('span.model').text(response.errors["model"]).show();
						$('div.model').addClass('has-error');
					}
					if(response.errors["itemType"]){
						$('span.itemType').text(response.errors["itemType"]).show();
						$('div.itemType').addClass('has-error');
					}
					if(response.errors["dateArrived"]){
						$('span.dateArrived').text(response.errors["dateArrived"]).show();
						$('div.dateArrived').addClass('has-error');
					}
					createTicket.ladda('stop');
					
					});
				this.on("successmultiple", function (data,response,xhr) {
					console.log(response);							
					addNewRow(response);							
					thisDropzone.removeAllFiles();						
			      });

			    }
		};
	});

function addNewRow(data){
	$('div').removeClass('has-error');
	var table = $('table#itemList').DataTable();
	table.row.add([
		data.response['itemNo'],
		data.response['itemType'],
		data.response['unique_id'],
		data.response['serviceTag'],
		data.response['brand'] + " - " + data.response['model'],
		data.response['created_at']
	]).draw();
	swal({
		title : "",
		text : "New Item Added",
		type : "success",
	},function() {
		$('#addItem').modal('hide');
		$('form.addItem').trigger('reset');
});
	
	};

$('button#addItemSearch').click(function(){
	$.ajax({
		type : "get",
		url : "/inventory/addItem/advancedSearch",
		data : $('form#addItemSearch').serialize(),
		success: function(data){
			var table = $('table#itemList').DataTable();
			table.clear();
			
			if(data.response.length >= 1){
				$.each(data.response,function(i, v) {
					table.row.add([
					               v.itemNo,
					               v.itemType,
					               v.unique_id,
					               v.serviceTag,
					               v.brand + " - " + v.model,
					               v.created_at]);
					
					});
				}
			table.draw();
			}
	});
});
$('button#addSearch').click(function(){
	$.ajax({
		type : "get",
		url : "/inventory/addItem/search",
		data : {search : $('input#addSearch').val()},
		success: function(data){
			var table = $('table#itemList').DataTable();
			table.clear();
			
			if(data.response.length >= 1){
				$.each(data.response,function(i, v) {
					table.row.add([
					               v.itemNo,
					               v.itemType,
					               v.unique_id,
					               v.serviceTag,
					               v.brand + " - " + v.model,
					               v.created_at]);
					
					});
				}
			table.draw();
			}
	});
});
$('input#otherItemType').change(function(){
		$('option#otherItemType').val($(this).val());
		});
$('select#itemType').change(function(){
		if($("select option:last").is(":selected")){
				$('input#otherItemType').removeClass('hidden');
			}else{
				$('input#otherItemType').addClass('hidden');
				}
	});
});