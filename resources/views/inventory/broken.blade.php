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
							<div class="ibox float-e-margins">
								<div class="ibox-title">

									<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#brokenReport">Report Broken Item</button>
						<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#repairReport">Report Item Repair</button>
								</div>
								<div class="ibox-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover dataTables-example" >
											<thead>
												<tr>
													<th>Unique Identifier</th>
													<th>Item No. </th>
													<th>Damage</th>
													<th>Reported By</th>
													
													<th>Date Broken</th>
												
												</tr>
											</thead>
											<tbody id="brokenItem">
											@foreach($brokenItems as $brokenItem)
											
												<tr>
													<td>{{$brokenItem->unique_id}}</td>
													<td>{{$brokenItem->itemNo}}</td>
													<td>{{$brokenItem->damage}}</td>
													<td>{{$brokenItem->first_name.' '.$brokenItem->last_name}}</td>
													<td>{{$brokenItem->date_broken}}</td>
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
						<div class="form-group col-lg-12 brokenDamage">
							<label class="control-label col-lg-2"> Damage:</label>
							<div class="col-lg-10">
								<textarea name="damage" class="form-control brokenDamage" rows="2"> </textarea>	
								<span class="help-block text-danger brokenDamage">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-12 dateBroken">
							<label class="control-label col-lg-2"> Date Broken:</label>
							<div class="col-lg-4">
							<div class="input-group date dateBroken">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateBroken"
									name="dateBroken">

									</div>
							
                   
									<span class="help-block text-danger dateBroken">192.168.100.200</span>
									
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

<!-- Repair Report -->

<div id="repairReport" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Repair Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal repairItem" id="repairItem">
				{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 repairUnique_id">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control repairUniqueId"
									placeholder="Unique Identifier" name="unique_id">
									<span class="help-block text-danger repairUnique_id">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control repairItemNo"
									name="itemNo" readonly>
									<span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-7 dateRepair">
							<label class="control-label col-lg-4"> Date Repaired:</label>
							<div class="col-lg-8">
							<div class="input-group date dateRepair">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateRepair"
									name="dateRepair">

									</div>
							
                   
									<span class="help-block text-danger dateRepair">192.168.100.200</span>
									
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
					<h2 class="text-center">Item is not Broken or with Issues</h2>
					</div>
					<form class="form-horizontal itemInfo">
						<hr>
						<div class="row">
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Unique Identifier :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control repairId"
										value="Unique Identifier" readonly>
								</div>
							</div>
							
								<div class="form-group col-lg-12">
							<label class="control-label col-lg-2"> Damage:</label>
							<div class="col-lg-10">
							<input type="text" class="form-control repairDamage" readonly>			
							</div>
						</div>
						<div class="form-group col-lg-12">
							<label class="control-label col-lg-2"> Issue:</label>
							<div class="col-lg-10">
								<textarea class="form-control repairIssue" rows="2" readonly> </textarea>	
							</div>
						</div>
						<div class="form-group col-lg-12">
							<label class="control-label col-lg-2"> Date Reported:</label>
							<div class="col-lg-4">
							
										<input type="text" class="form-control repairBroken" readonly>
														
							</div>
						</div>
							


						</div>
					</form>
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary repairItem" type="button" data-style="zoom-in">
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

	$('.input-group.date.dateBroken').datepicker({
	    format : 'yyyy-mm-dd',
	    todayBtn: "linked"
		});
	});

$('.input-group.date.dateRepair').datepicker({
    format : 'yyyy-mm-dd',
    todayBtn: "linked"
	});

</script>
@endsection

