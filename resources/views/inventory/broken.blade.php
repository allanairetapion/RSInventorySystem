@extends('inventory.inventory')

@section('title', 'RS | Broken Items')

@section('header-page')
					<div class="col-lg-10">
						<h2>Broken Items</h2>
						<ol class="breadcrumb">
							<li>
								<a href="index.html">Home</a>
							</li>

							<li class="active">
								<strong>Broken Items</strong>
							</li>
						</ol>
					</div>
					
				
	@endsection	
@section('content')
				
				
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">

									<button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
						data-target="#brokenReport">Report Broken Item</button>
						<div class="btn-group">
							                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"> Mark as <span class="caret"></span></button>
							                            <ul class="dropdown-menu">
							                            	<li><a id="brokenMark" href="#">Repaired</a></li>
							                                <li><a id="brokenMark" href="#">Send to Supplier</a></li>
							                                <li><a id="brokenMark" href="#">For Displacement</a></li>
							                                <li><a id="brokenMark" href="#">No Budget to Fix</a></li>
							                                <li><a id="brokenMark" href="#">Can be Repaired</a></li>
							                                <li><a id="brokenMark" href="#">Cannot be Repaired</a></li>
							                            </ul>
							                        </div>
								</div>
								<div class="ibox-content">
<div class="input-group"><input type="text" class="form-control">

                                            <div class="input-group-btn">
                                            <button class="btn btn-white dropdown-toggle" type="button"><span class="caret"></span></button>
                                                
                                                <button tabindex="-1" class="btn btn-white" type="button">Action</button>
                                                
                                            </div>
                                            </div>
                                            
									<br>
										<table class="table table-bordered table-hover dataTables-example" >
											<thead>
												<tr>
													<th><input type="checkbox" class="i-checks"/> &nbsp;Unique Identifier</th>
													<th>Item No. </th>
													<th>Damage</th>
													<th> Current Status</th>
													<th>Reported By</th>
													
													<th>Date Broken</th>
													
												</tr>
											</thead>
											<tbody id="brokenItem">
											@foreach($brokenItems as $brokenItem)
											
												<tr id="{{$brokenItem->unique_id}}">
													<td><input type="checkbox" class="i-checks brokenItem"
													value="{{$brokenItem->unique_id}}"/> 
													
													&nbsp;{{$brokenItem->unique_id}}</td>
													<td>{{$brokenItem->itemNo}}</td>
													<td>{{$brokenItem->damage}}</td>
													<td>{{$brokenItem->brokenStatus}}</td>
													<td>{{$brokenItem->first_name.' '.$brokenItem->last_name}}</td>
													<td>{{$brokenItem->created_at}}</td>
													
												</tr>
												
											@endforeach

											</tbody>
										</table>
									

								</div>
							</div>
						</div>
						</div>
					
					
<div id="brokenReport" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Broken Item Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal repairItem" id="brokenItem">
				{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 brokenUniqueId">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control brokenUniqueId"
									placeholder="Unique Identifier" name="unique_id">
									<span class="help-block text-danger brokenUnique_id">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control brokenItemNo"
									name="itemNo" readonly>
									<span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-7 brokenStatus">
							<label class="control-label col-lg-4"> Status:</label>
							<div class="col-lg-8">
								<select name="status" class="form-control">
								<option value="" selected></option>
								<option value="Repaired">Repaired </option>
							    <option value="Send to Supplier"> Send to Supplier</option>
							    <option value="For Displacement"> For Displacement </option>
							    <option value="No Budget to Fix"> No Budget to Fix </option>
							    <option value="Can be Repaired"> Can be Repaired </option>
							    <option value="Cannot be Repaired"> Cannot be Repaired </option>
								</select>
									<span class="help-block text-danger brokenStatus">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 dateBroken">
							<label class="control-label col-lg-4"> Date:</label>
							<div class="col-lg-8">
							<div class="input-group date dateBroken">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateBroken"
									name="dateBroken">

									</div>
							
                   
									<span class="help-block text-danger dateBroken">192.168.100.200</span>
									
							</div>
						</div>
						<div class="form-group col-lg-12 brokenDamage">
							<label class="control-label col-lg-2"> Damage:</label>
							<div class="col-lg-10">
								<textarea name="damage" class="form-control brokenDamage" rows="2"> </textarea>	
								<span class="help-block text-danger brokenDamage">192.168.100.200</span>
							</div>
						</div>
						
					
					</div>

				</form>
				<center>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <span
						class="sr-only">Loading...</span>
					
					</center>
					<div class="itemNotfound">
					<hr>
					<h2 class="text-center">Item is already reported</h2>
					</div>
					
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary brokenItem" type="button" data-style="zoom-in">
					<strong>Save</strong>
				</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>


					
<script type="text/javascript">
$(document).ready(function() {
	$('form.itemInfo').hide();
	$('i.fa-pulse').hide();
	$('div.itemNotfound').hide();
	$('span.text-danger').hide();

	});
$('td').click(function(){
	  var col = $(this).parent().children().index($(this));
	  var row = $(this).parent().parent().children().index($(this).parent());
	  alert('Row: ' + row + ', Column: ' + col);
	});
	
$('li a#brokenMark').click(function(){
	var items = [];
	var mark = $(this).text();
	
	
	swal({
		title : 'Are you sure?',
		text : "This Action can't be undone",
		type : 'warning',
		showCancelButton : true,
		showCancelButton : true,
		closeOnConfirm : false,
		showLoaderOnConfirm : true,
		disableButtonsOnConfirm : true,
	}, function() {
		$('input:checkbox.brokenItem').each(function () {
		       if(this.checked){
			       items.push($(this).val());
			   		$('tr#'+$(this).val()+' td').eq(3).text(mark);
		       }
		  });

		$.ajax({
			headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
			type : "PUT",
			url : "/inventory/brokenMark",
			data : {items : items, mark : mark},
			success: function(data){
				swal({
					title:"Success",
					text: "Successfully Marked as "+ mark,
					type: "success",
					},function(){
						$(data.response).each(function (i,v) {
							$('tr#'+ v.unique_id +' td').eq(3).text(mark);
							$('tr#'+ v.unique_id +' td').eq(5).text(v.updated_at);
							});
						});
				
					}
			});
		});
	
	  
});

</script>
@endsection

