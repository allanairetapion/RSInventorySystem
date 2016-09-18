@extends('inventory.inventory')

@section('title', 'RS | Items Have Issues')

@section('header-page')
                <div class="col-lg-10">
                    <h2>Items With Issues</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                 
                        <li class="active">
                            <strong>Item Have Issues Form</strong>
                        </li>
                    </ol>
                </div>
@endsection	
@section('content')
        
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <!-- Trigger the modal with a button -->
					<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#issueReport">Report Item Issue</button>
						<button type="button" class="btn btn-primary" data-toggle="modal"
						data-target="#repairReport">Report Item Repair</button>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                    <table class="table  table-bordered " >
                    <thead>
                    <tr>
                        <th>Unique Id</th>
                        <th>Item No</th>
                        <th>Damage</th>
                        <th>Issue</th>
                        <th>Reported By</th>
                        <th>Date Reported</th>
                       
                    </tr>
                    </thead>
                    <tbody class="issueItem">
                   		@foreach($issueItems as $issue)
                   		<tr>
                   			<td>{{$issue->unique_id}}</td>
                   			<td>{{$issue->itemNo}}</td>
                   			<td>{{$issue->damage}}</td>
                   			<td>{{$issue->issue}}</td>
                   			<td>{{$issue->first_name.' '.$issue->last_name}}</td>
                   			<td>{{$issue->created_at}}</td>
                   			
                   			</tr>
                   		@endforeach
                    </tbody>
                    
                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
            
    

<div id="issueReport" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Issue Report</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal issueItem" id="issueItem">
				{!! csrf_field() !!}
					<div class="row">
						<div class="form-group col-lg-7 issueUnique_id">
							<label class="control-label col-lg-4"> Unique Identifier:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control issueUniqueId"
									placeholder="Unique Identifier" name="unique_id">
									<span class="help-block text-danger issueUnique_id">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-5 itemNo">
							<label class="control-label col-lg-4"> Item No:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control infoItemNo"
									name="itemNo" readonly>
									<span class="help-block text-danger itemNo">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-12 itemDamage">
							<label class="control-label col-lg-2"> Damage:</label>
							<div class="col-lg-10">
							<input type="text" class="form-control itemDamage"
									name="damage">
								
									<span class="help-block text-danger itemDamage">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-12 itemIssue">
							<label class="control-label col-lg-2"> Issue:</label>
							<div class="col-lg-10">
								<textarea class="form-control itemIssue" name="issue" rows="2"> </textarea>
								<span class="help-block text-danger itemIssue">192.168.100.200</span>
							</div>
						</div>
						<div class="form-group col-lg-12 dateReported">
							<label class="control-label col-lg-2"> Date Reported:</label>
							<div class="col-lg-4">
							<div class="input-group date dateReported">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control dateReported"
									name="dateReported">

									</div>
							
                   
									<span class="help-block text-danger dateReported">192.168.100.200</span>
									
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
					<form class="form-horizontal itemInfo">
						<hr>
						<div class="row">
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Unique Identifier :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoId"
										value="Unique Identifier" readonly>
								</div>
							</div>
							<div class="form-group col-lg-5">
								<label class="control-label col-lg-4"> Item No :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoItemNo"
										value="Item No." readonly>
								</div>
							</div>
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Company :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoCompany"
										value="Company" readonly>
								</div>
							</div>
							<div class="form-group col-lg-5">
								<label class="control-label col-lg-4"> Station No :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoStationNo"
										value="Station No." readonly>
								</div>
							</div>
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Brand :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoBrand" value="Brand"
										readonly>
								</div>
							</div>
							<div class="form-group col-lg-5">
								<label class="control-label col-lg-4"> Model :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoModel" value="Model"
										readonly>
								</div>
							</div>
							<div class="form-group col-lg-7">
								<label class="control-label col-lg-4"> Item Type :</label>
								<div class="col-lg-8">
									<input type="text" class="form-control infoItemType"
										value="Item Type" readonly>
								</div>
							</div>
							


						</div>
					</form>
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary issueItem" type="button" data-style="zoom-in">
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

	$('.input-group.date.dateReported').datepicker({
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