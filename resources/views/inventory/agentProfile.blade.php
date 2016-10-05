@extends('inventory.inventory') 

@section('title', 'RS | Agent Profile')

@section('header-page')
<br>
<div class="col-lg-3">
	<h3>{{$agent->first_name.' '.$agent->last_name}}</h3>
		<img alt="image" class="img-responsive"
		src="{{$agent->photo ? $agent->photo : '/img/default-profile.jpg'}} ">
		
	
	
</div>
<div class="col-lg-9">
	<div>
                                <div id="slineChart" ></div>
                            </div>

</div>

@endsection @section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">

			<div class="ibox-content">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#tab-1">Borrow Logs</a></li>
					<li class=""><a data-toggle="tab" href="#tab-2">Return Logs</a></li>
					<li class=""><a data-toggle="tab" href="#tab-3">Issue Logs</a></li>
					<li class=""><a data-toggle="tab" href="#tab-4">Broken Logs</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab-1" class="tab-pane active">
						<br>
						<table id="borrow_logs"
							class="table table-bordered toggle-arrow-tiny"
							data-striping="false">
							<thead>
								<th>ID</th>
								<th>Borrower</th>
								<th>Borrowee</th>
								<th>Borrower Station No.</th>
								<th>Date Borrowed</th>
							</thead>
							<tbody>
								@foreach($borrows as $borrow)
								<tr>
									<td>{{$borrow->id}}</td>
									<td>{{$borrow->borrower}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}</td>
									<td>{{$borrow->borrowerStationNo}}</td>
									<td>{{$borrow->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>
					<div id="tab-2" class="tab-pane">
						<br>
						<table id="return_logs"
							class="footable table table-bordered toggle-arrow-tiny"
							data-striping="false">
							<thead>
								<th>ID</th>
								<th>Borrower</th>
								<th>Receiver</th>
								<th>Date Returned</th>
							</thead>
							<tbody>
								@foreach($returns as $return)
								<tr>
									<td>{{$return->id}}</td>
									<td>{{$return->borrower}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}</td>
									<td>{{$return->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>
					<div id="tab-3" class="tab-pane">
						<br>
						<table id="issue_logs"
							class="footable table table-bordered toggle-arrow-tiny"
							data-striping="false">
							<thead>
								<th>ID</th>
								<th data-hide="all">Issue</th>
								<th>Damage</th>
								<th>Reported By</th>
								<th>Date Reported</th>
							</thead>
							<tbody>
								@foreach($issues as $issue)
								<tr>
									<td>{{$issue->id}}</td>
									<td>
										<button type="button" class="btn btn-primary btn-xs issue"
											data-toggle="modal" data-target="#myModal"
											value='{{$issue->issue}}'>Click to view full details</button>
									</td>
									<td>{{$issue->damage}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}</td>
									<td>{{$issue->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>
					<div id="tab-4" class="tab-pane">
						<br>
						<table id="broken_logs"
							class="footable table table-bordered table-hover"
							data-striping="false">
							<thead>
								<th data-toggle="true">ID</th>
								<th>Damage</th>
								<th data-hide="all">Summary</th>
								<th>Status</th>
								<th>Reported By</th>
								<th>Date Reported</th>
								<th>Date Updated</th>
							</thead>
							<tbody>
								@foreach($brokens as $broken)
								<tr>
									<td>{{$broken->id}}</td>
									<td>{{$broken->damage}}</td>
									<td><button type="button" class="btn btn-primary btn-xs broken"
											data-toggle="modal" data-target="#myModal"
											value='{{$broken->brokenSummary}}'>Click to view full details
										</button></td>
									<td>{{$broken->brokenStatus}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}</td>
									<td>{{$broken->created_at}}</td>
									<td>{{$broken->updated_at}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>

				</div>
			</div>

		</div>

	</div>

</div>

<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>

				<h4 class="modal-title">Modal title</h4>

			</div>
			<div class="modal-body itemlogs"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>

<div class="modal inmodal fade" id="itemPhoto" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Item Photo</h4>
				
			</div>
			<div class="modal-body">
			<div class="row">
				<div id="itemPhotoPreview">
					h
					
					</div>
				</div>
			</div>

			<div class="modal-footer">
			<label class="btn btn-primary">
					<i class="fa fa-plus" style="vertical-align: middle;horizontal-align:middle;"></i>
					<input type="file" accept="image/*" name="file" id="inputImage" class="hide">
					Add Photo
					 </label>
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal fade" id="itemDetails" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Item Details</h4>
				
			</div>
			<div class="modal-body">
			<div class="row">
				<form id="itemDetails"class="form-horizontal">
				{!! csrf_field() !!}
				
				</form>
			</div>
</div>
			<div class="modal-footer">
			
					
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<<script type="text/javascript">
$(document).ready(function(){
	c3.generate({
        bindto: '#slineChart',
        data:{
            x: "x",
            columns: 
                <?php echo json_encode($stats); ?>,
                
            
            colors:{
                data1: '#1ab394',
                data2: '#BABABA'
            },
            type: 'spline'
        },
        axis : {
			x : {
				type : 'category',
			}
			}
		
    });
	
});
$('button.issue').click(function(){
	$('h4.modal-title').text('Issue Log ID: ' + $('td:first', $(this).parents('tr')).text());

	$('div.itemlogs').html($(this).val());
});
$('button.broken').click(function(){
$('h4.modal-title').text('Broken Log ID: ' + $('td:first', $(this).parents('tr')).text());

$('div.itemlogs').html($(this).val());
});
</script>
@endsection
