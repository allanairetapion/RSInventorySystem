@extends('inventory.inventory') @section('title', 'RS | Item Detail')

@section('header-page')
<br>
<div class="col-lg-4">
	<div class="carousel slide" id="carousel1">
		<div class="carousel-inner">
		<?php $item->photo = ($item->photo == null) ? null : explode(",",$item->photo)?>
		@if($item->photo != null)
		@foreach($item->photo as $photo)
		@if($photo != "")
			<div class="item">
				<img alt="image" class="img-responsive center-block"
					src="{{$photo}}" style="height: 205px; margin: auto;">
			</div>
			@endif @endforeach @else
			<div class="item">
				<img alt="image" class="img-responsive" src="/img/remote_logo2.jpg"
					style="height: 205px; margin: auto;">
			</div>
			@endif
		</div>
		<a data-slide="prev" href="#carousel1" class="left carousel-control">
			<span class="icon-prev"></span>
		</a> <a data-slide="next" href="#carousel1"
			class="right carousel-control"> <span class="icon-next"></span>
		</a>

	</div>
	<br>
	<button type="button" class="btn btn-primary btn-w-m"
	data-toggle="modal" data-target="#itemDetails">Update Details</button>
	<button type="button" class="btn btn-primary btn-w-m"
		data-toggle="modal" data-target="#itemPhoto">Update/Add Photo</button>
	<div></div>
</div>
<div class="col-lg-4">
	<h3>Status: {{$item->itemStatus}}</h3>
	<h3>Unique ID: <span id="itemUniqueID">{{$item->unique_id}}</span></h3>
	<h3>Item No: {{$item->itemNo}}</h3>
	<h4>Current Location:  
	@if($item->itemStatus != "Borrowed")
	Technical Support
	@else
	Station No. {{$item->stationNo}}
	@endif
	</h4>
	<h4>Item Type: {{$item->itemType}}</h4>
	<h4>Brand: {{$item->brand}}</h4>
	<h4>Model: {{$item->model}}</h4>

</div>
<div class="col-lg-4">
	<h4>Morning User: <span id="itemMorningClient">{{$item->morningFN.' '.$item->morningLN}}</span> </h4>
	<h4>Night User: <span id="itemNightClient">{{$item->nightFN.' '.$item->nightLN}}</span></h4>
	<h4>Specification:</h4>
	<div style="height: 150px; overflow: auto;">{!!html_entity_decode($item->specification)!!}</div>

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
									<td>{{$borrow->first_name.' '.$borrow->last_name}}</td>
									<td>{{$borrow->borrowerStationNo}}</td>
									<td>{{$borrow->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
						<tr>
						<td colspan="5" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
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
									<td>{{$return->first_name.' '.$return->last_name}}</td>
									<td>{{$return->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
						<tr>
						<td colspan="4" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
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
									<td>{{$issue->damage}}</td>
									<td>
										<button type="button" class="btn btn-primary btn-xs issue"
											data-toggle="modal" data-target="#myModal"
											value='{{$issue->issue}}'>Click to view full details</button>
									</td>
									<td>{{$issue->first_name.' '.$issue->last_name}}</td>
									<td>{{$issue->created_at}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
						<tr>
						<td colspan="5" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
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
								<th>Summary</th>
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
									<td>{{$broken->first_name.' '.$broken->last_name}}</td>
									<td>{{$broken->created_at}}</td>
									<td>{{$broken->updated_at}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
						<tr>
						<td colspan="7" class="text-right">
						<ul class="pagination"></ul>
						</td>
						</tr>
					</tfoot>
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
					@if($item->photo != null) 
					@foreach($item->photo as $photo)
					@if($photo != "")
					<div class="item col-md-3 text-center">
						<img alt="image" class="img-responsive center-block"
							src="{{$photo}}" style="height: 205px; margin: auto;">
							<a href="#" id="deleteItemPhoto" >Remove File</a>
					</div>
					@endif 
					@endforeach 
					@endif
					
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
				<form id="updateItemDetails"class="form-horizontal">
				{!! csrf_field() !!}
				<input type="hidden" name="itemNo" value="{{$item->itemNo}}"/>
				<div class="form-group col-md-6">
					<label class="control-label col-md-4">Morning User: </label>
					<div class="col-md-8">
					<select class="form-control morning_user" name="morning_user">
					<option value="" selected></option>
					@foreach($clients as $client)
					<option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
					@endforeach
					</select>
					
					
					</div>
				</div>
				<div class="form-group col-md-6">
					<label class="control-label col-md-4">Evening User: </label>
					<div class="col-md-8">
					<select class="form-control evening_user" name="evening_user">
					<option value="" selected></option>
					@foreach($clients as $client)
					<option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
					@endforeach
					</select>
					
					
					</div>
				</div>
				</form>
			</div>
</div>
			<div class="modal-footer">
			
					
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="updateItemDetails">Save changes</button>
			</div>
		</div>
	</div>
</div>

<script>
$('button#updateItemDetails').click(function(){
	console.log('click');
	$.ajax({
		type : "PUT",
		url : "/inventory/updateItemDetails",
		data : $('form#updateItemDetails').serialize(),
		success: function(data){
			if($('select.morning_user').val() != null){
			$('span#itemMorningClient').text($('select.morning_user :selected').text());
			}
			if($('select.evening_user').val() != null){
			$('span#itemNightClient').text($('select.evening_user :selected').text());
			}
			toastr.success('Detail succesfully updated.');
			},
		error: function(data){
			toastr.error('An error occured');
			}
    });
});


$(document).ready(function(){
	$('div.item:first').addClass('active');
	$('button.issue').click(function(){
			$('h4.modal-title').text('Issue Log ID: ' + $('td:first', $(this).parents('tr')).text());
			$('div.itemlogs').html($(this).val());
		});
	$('button.broken').click(function(){
		$('h4.modal-title').text('Broken Log ID: ' + $('td:first', $(this).parents('tr')).text());
	
		$('div.itemlogs').html($(this).val());
	});
	$('div.tab-pane').on( 'click', function () {
	    $('table').trigger('footable_redraw');
	});
	$('table#borrow_logs').footable();
	$('table#return_logs').footable();
	$('table#issue_logs').footable();
	$('table#broken_logs').footable();

	
	
});

var $inputImage = $("#inputImage");
if (window.FileReader) {
    $inputImage.change(function() {
        var fileReader = new FileReader(),
                files = this.files,
                file;

        if (!files.length) {
            return;
        }

        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
            fileReader.readAsDataURL(file);
            fileReader.onload = function () {
				var photo = this.result;
                
                $.ajax({
					headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
					type : "POST",
					url : "/inventory/addItemPhoto",
					data : {id : $('span#itemUniqueID').text(),name: file.name,photo : photo},
					success: function(data){
						var newPhoto = '<div class="item col-md-3 text-center">'+
						'<img alt="image" class="img-responsive center-block" src="'+ data.response + 
						'"style="height: 205px; margin: auto;"><a href="#" id="deleteItemPhoto">Remove File</a></div>';
		                $('div#itemPhotoPreview').append(newPhoto);
		                
		                var carouselPhoto = '<div class="item"><img alt="image" class="img-responsive center-block"'+
							'src="'+ data.response +'" style="height: 205px; margin: auto;"></div>';
		                $('div.carousel-inner').append(carouselPhoto);
		                $('div.item:first').addClass('active');
		                toastr.success('New Photo has been added.');
						},
                });
                
            };
        } else {
            showMessage("Please choose an image file.");
        }
    });
} else {
    $inputImage.addClass("hide");
}
$(document).on('click','a#deleteItemPhoto',function(){
	var src = $(this).parent().find('img').attr('src');
	var currentPhoto = this;
	$.ajax({
		headers : {'X-CSRF-Token' : $('input[name="_token"]').val()},
		type : "DELETE",
		url : "/inventory/deleteItemPhoto",
		data : {id : $('span#itemUniqueID').text(),name: src},
		success: function(){
			$('img[src="'+ src +'"]').parent().remove();
			$('div.item:first').addClass('active');
			
			toastr.success('Photo succesfully deleted.');
			},
    });
	
});


</script>
@endsection
