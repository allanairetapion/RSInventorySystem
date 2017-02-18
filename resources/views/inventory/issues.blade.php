@extends('layouts.inventory_basic') @section('title', 'RS | Items Have
Issues') @section('header-page')
<div class="col-lg-10">
	<h2>Items With Issues</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Item Have Issues Form</strong></li>
	</ol>
</div>
@endsection @section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">

			<div class="ibox-content">
				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#issueReport">Report Item Issue</button>

						<button type="button" class="btn btn-primary btn-sm"
							data-toggle="modal" data-target="#repairReport">Report Item
							Repair</button>
						
					</div>
					<div class="col-md-offset-4 col-md-4">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="issueSearch"
								name="issueSearch" placeholder="Search...">
							<div class="input-group-btn">
								<button id="issueSearch" class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
								<button class="btn btn-success" data-toggle="collapse" data-target="#advancedSearch"
									type="button">
									<span class="caret"></span>
								</button>

							</div>
						</div>

					</div>
				</div>

				<div id="advancedSearch" class="panel panel-default collapse">
					<div class="panel-body">
						<form class="issueTicketSearch form-horizontal">
							{!! csrf_field() !!}
							<div class="row">
								<div class="col-md-4">
									<label class="control-label col-md-4">Item No:</label>
									<div class="col-md-8">
										<select class="form-control itemNo chosen-select"
											name="itemNo">
											<option value="" selected></option> @foreach($itemNumbers as
											$id)
											<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
											@endforeach
										</select>
									</div>


								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Unique ID:</label>
									<div class="col-md-8">
										<input class="form-control" name="unique_id" type="text">
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label col-md-4">Agent:</label>
									<div class="col-md-8">
										<select class="form-control chosen-select" name="reported_by">
											<option value="" selected></option> @foreach($agents as
											$agent)
											<option value="{{$agent->id}}">{{$agent->first_name.'
												'.$agent->last_name}}</option> @endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<br> <label class="control-label col-md-4">Date:</label>
									<div class="col-md-8">
										<div class="input-group date dateReported">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
												type="text" class="form-control dateReported"
												name="dateReported">

										</div>
									</div>
								</div>



								<div class=" col-md-3 ">
									<br>

									<button type="button" class="btn btn-primary issueTicketSearch">
										<i class="fa fa-search"></i> Search
									</button>
									<button type="reset" class="btn btn-warning">
										<i class="fa fa-refresh"></i> Clear
									</button>

								</div>
							</div>

						</form>
					</div>
				</div>
				<div class="spiner-example hide" id="spinner">
					<div class="sk-spinner sk-spinner-three-bounce">
						<div class="sk-bounce1"></div>
						<div class="sk-bounce2"></div>
						<div class="sk-bounce3"></div>
					</div>
				</div>
				<div class="table-responsive" id="issueResult">
					<table id="issue"
						class="footable table table-bordered toggle-arrow-tiny"
						data-filter="#filter" data-striping="false">
						<thead>
							<tr>

								<th data-toggle="true">Item No</th>								
								<th>Item Type</th>
								<th>Brand + Model</th>
								<th>Damage</th>
								<th>Item User</th>								
								<th>Reported By</th>
								<th>Date Reported</th>

							</tr>
						</thead>
						<tbody class="issue">
							@foreach($issueItems as $issue)
							<tr id="{{$issue->itemNo}}" value="{{$issue->issue}}">
								<td>{{$issue->itemNo}}</td>								
								<td>{{$issue->itemType}}</td>
								<td>{{$issue->brand}} - {{$issue->model}}</td>
								<td>{{$issue->damage}}</td>
								<td>{{$issue->first_name.' '.$issue->last_name}}</td>								
								<td>{{$issue->agent_FN.' '.$issue->agent_LN}}</td>
								<td>{{$issue->created_at}}</td>

							</tr>
							@endforeach
						</tbody>

					</table>
				</div>
				<div id="issueSearchResult" class="hide">
				<table id="issueSearchResult"
					class="table table-bordered toggle-arrow-tiny">
					<thead>
						<tr>

							<th>Item No</th>
							<th>Item Type</th>
							<th>Brand + Model</th>
							<th>Damage</th>
							<th>Item User</th>							
							<th>Reported By</th>
							<th>Date Reported</th>

						</tr>
					</thead>
					<tbody>

					</tbody>

				</table>
</div>
			</div>
		</div>
	</div>
</div>



<div id="issueReport" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Issue Report</h4>
			</div>

			<div class="ibox-content">
				<form class="issueItem" id="issueItem">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group issueItemNo">
								<label class="control-label"> Item No:</label> <select
									id="issueItemNo" class="form-control chosen-select"
									name="itemNo">
									<option value="" selected></option> @foreach($itemNumbers as
									$id)
									<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
									@endforeach
								</select> <span class="help-block text-danger issueItemNo">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemUser">
								<label class="control-label"> Item User:</label> <select
									class="form-control itemUser chosen-select" name="item_user">
									<option value="" selected></option> @foreach($users as $user)
									<option value="{{$user->id}}">{{$user->first_name.'
										'.$user->last_name}}</option> @endforeach
								</select> <span class="help-block text-danger itemUser">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group itemDamage">
								<label class="control-label"> Damage:</label> <input type="text"
									class="form-control itemDamage" name="damage"> <span
									class="help-block text-danger itemDamage">192.168.100.200</span>

							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group dateReported">
								<label class="control-label"> Date:</label>

								<div class="input-group date dateReported">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
										type="text" class="form-control dateReported"
										name="dateReported">

								</div>
								<span class="help-block text-danger dateReported">192.168.100.200</span>


							</div>
						</div>
						<div class="form-group col-lg-12 itemIssue">
							<label class="control-label "> Issue:</label> <input
								type="hidden" id="itemIssue" name="summary" rows="2">
							<div id="issueSummary"></div>
							<span class="help-block text-danger itemIssue">192.168.100.200</span>

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
				<button class="ladda-button btn btn-w-m btn-primary issueItem"
					type="button" data-style="zoom-in">
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
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Item Repair Report</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<form class="form repairItem" id="repairItem">
						{!! csrf_field() !!}


						<div class="form-group repairItemNo">
							<label class="control-label">Item No:</label> <select
								id="repairItemNo" class="form-control chosen-select"
								name="itemNo">
								<option value="" selected></option> @foreach($itemNumbers as
								$id)
								<option value="{{$id->itemNo}}">{{$id->itemNo}}</option>
								@endforeach
							</select> <span class="help-block text-danger repairItemNo">192.168.100.200</span>
						</div>
				
				</div>

				</form>



			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary repairItem"
					type="button" data-style="zoom-in">
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
<div class="modal inmodal" id="itemIssue" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>

				<h4 class="modal-title itemIssue">Modal title</h4>

			</div>
			<div class="modal-body">
				<p> Damage: <span class="issueDamage font-bold"></span> </p>
				<p>Summary: <span class="issueSummary"></span></p>
			</div>
			<div class="modal-footer">
				<a class="btn btn-primary itemDetails" href="#">View Item Details</a>
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>
@endsection @section('scripts')
<script type="text/javascript" src="/js/inventory/inventoryIssue.js">
</script>
@endsection
