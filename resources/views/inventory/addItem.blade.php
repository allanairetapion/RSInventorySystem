@extends('inventory.inventory') @section('title', 'RS | Add Items')

@section('header-page')
<div class="col-lg-10">
	<h2>Add Items</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><strong>Add Items</strong></li>
	</ol>
</div>
<div class="col-lg-2"></div>

@endsection @section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">

			<div class="ibox-content">
				<div class="row">
					<div class="col-md-2">
						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#addItem">Add Item</button>
						<div class="btn-group">
							<button data-toggle="dropdown"
								class="btn btn-primary btn-sm dropdown-toggle">
								Export <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="#" id="exportExcel">excel</a></li>
								<li><a href="#" id="exportCSV">csv</a></li>

							</ul>
						</div>

					</div>
					<div class="col-md-offset-6 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="filter"
								placeholder="Search...">
							<div class="input-group-btn">
								<button tabindex="-1" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" id="itemAdvancedSearch"
									type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>
				</div>
				<div id="itemAdvancedSearch" class="panel panel-default">
					<div class="panel-body">
						<form class="form-horizontal" id="addItemSearch">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-md-4">
									<label class="control-label col-md-4">Item No:</label>
									<div class="col-md-8">
										<input type="text" name="itemNo" class="form-control">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Unique ID:</label>
									<div class="col-md-8">
										<input class="form-control" name="unique_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Item Type:</label>
									<div class="col-md-8">
										<select class="form-control" name="itemType">
											<option value="" selected></option> @foreach($itemTypes as
											$itemType)
											<option value="{{$itemType->itemType}}">{{$itemType->itemType}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Brand:</label>
									<div class="col-md-8">
										<input class="form-control" name="brand" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Model:</label>
									<div class="col-md-8">
										<input class="form-control" name="model" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateBorrowed">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateBorrowed"
												name="dateArrived">

										</div>
									</div>
								</div>



								<div class=" col-md-4 ">
									<br>

									<button type="button" class="btn btn-primary btn-w-m"
										id="addItemSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning btn-w-m">
										<i class="fa fa-refresh"></i> Clear
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>
				<table id="itemList" class="table table-bordered"
					data-filter="#filter" data-striping="false">
					<thead>
						<tr>
							<th>Item No.</th>
							<th>Item Type</th>
							<th>Unique ID</th>
							<th>Brand</th>
							<th>Model</th>
							<th>Date Arrived</th>
						</tr>
					</thead>
					<tbody>
						@foreach($items as $item)
						<tr>
							<td>{{$item->itemNo}}</td>
							<td>{{$item->itemType}}</td>
							<td>{{$item->unique_id}}</td>
							<td>{{$item->brand}}</td>
							<td>{{$item->model}}</td>
							<td>{{$item->created_at}}</td>
						</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6" class="text-right">
								<ul class="pagination"></ul>
							</td>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>


</div>
<div id="addItem" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Item</h4>
			</div>

			<div class="ibox-content">
				<form class="addItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group brand">
								<label class="control-label"> Brand:</label> <input type="text"
									class="form-control" placeholder="Brand" name="brand"
									id="brand" required> <span class="help-block text-danger brand">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group model">
								<label class="control-label"> Model:</label> <input type="text"
									class="form-control" placeholder="Model" name="model"
									id="model" required> <span class="help-block text-danger model">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group serial_no">
								<label class="control-label">Serial No:</label> <input
									type="text" class="form-control" placeholder="Serial Number"
									id="serial_no" name="serial_no" required> <span
									class="help-block text-danger serial_no">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group company">
								<label class="control-label"> Company:</label> 
								<input name="company" id="company" class="form-control" type="text">
								 <span class="help-block text-danger company">192.168.100.200</span>
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group specification">
								<label class="control-label"> Specification:</label> <input
									type="hidden" id="specification" name="specification">
								<div id="itemDescription"></div>
								<span class="help-block text-danger specification">192.168.100.200</span>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemType">
								<label class="control-label"> Item Type:</label> <select
									class="form-control" name="itemType" id="itemType">
									<option value="" selected></option>
									<option value="Laptop">Laptop</option>
									<option value="Keyboard">Keyboard</option>
									<option value="Mouse">Mouse</option>
									<option value="Headset">Headset</option>
									<option value="Monitor">Monitor</option>
									<option value="" id="otherItemType">Other</option>
								</select> <input type="text" class="form-control hidden"
									id="otherItemType" placeholder="Please Specify"> <span
									class="help-block text-danger itemType">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group dateArrived">
								<label class="control-label "> Date Arrived:</label>

								<div class="input-group date dateArrived">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateArrived"
										name="dateArrived" id="dateArrived">

								</div>

								<span class="help-block text-danger dateArrived">192.168.100.200</span>

							</div>
						</div>



						<div class="col-lg-12">
							<br>
							<div id="itemPhoto" class="dropzone">
								<div class="dropzone-previews"></div>
								<div class="dz-message" data-dz-message>
									<span>Click to upload or Drop Item's photo here.</span><br> <span>Image
										files only. </span><br> <span>2 MB per image limit. 10 MB
										total limit.</span>
								</div>
							</div>
						</div>




					</div>
				</form>

			</div>

			<div class="modal-footer">
				<button class="btn btn-w-m btn-primary addItem" type="button">
					<strong>Submit</strong>
				</button>
				<button class="btn btn-danger btn-w-m" data-dismiss="modal">Cancel</button>

			</div>
		</div>

	</div>
</div>

<script>
	$(document).ready(function(){
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
			$('div#itemAdvancedSearch').hide();
			$('table#itemList').footable();
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
										if(errors.errors["serial_no"]){
											$('span.serial_no').text(errors.errors["serial_no"]).show();
											$('div.serial_no').addClass('has-error');
										}
										if(errors.errors["itemNo"]){
											$('span.itemNo').text(errors.errors["itemNo"]).show();
											$('div.itemNo').addClass('has-error');
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
		var table = $('table#itemList').data('footable');
		var newRow = "<tr><td>" + data.response['itemNo'] + "</td><td>" + data.response['itemType'] + "</td>" +
					"<td>" + data.response['unique_id'] + "</td><td>" + data.response['brand'] + "</td>" +
					"<td>" + data.response['model'] + "</td><td>" + data.response['dateArrived']['date'] + "</td></tr>";
		$('table#itemList > tbody').prepend(newRow);
		table.redraw();
		swal({
			title : "",
			text : "New Item Added",
			type : "success",
		},function() {
			$('#addItem').modal('hide');
			$('form.addItem').trigger('reset');
});
		
		};
	$('button#itemAdvancedSearch').click(function(){
		$('div#itemAdvancedSearch').slideToggle();
	});

	$('button#addItemSearch').click(function(){
		$.ajax({
			type : "get",
			url : "/inventory/addItem/search",
			data : $('form#addItemSearch').serialize(),
			success: function(data){
				var table = $('table#itemList').data('footable');
				$('tbody>tr').each(function(){
					table.removeRow(this);
					});

				if(data.response.length >= 1){
					$.each(data.response,function(i, v) {
						var newRow = "<tr><td><a href='/inventory/items/"+ v.itemNo +" '>" + v.itemNo + "</a></td><td>" + v.itemType + " </td>"+
									"<td>" + v.unique_id + "</td><td>" + v.brand + "</td><td>" + v.model + "</td>" +
									"<td>" + v.created_at + "</td></tr>";
						table.appendRow(newRow);
						});
					}else{
					table.appendRow("<tr><td colspan='9' class='text-center'> No Data Found.</td></tr>");
					}
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
</script>
@endsection

