@extends('tickets.ticketclientlayout') 

@section('body')

<div class="col-md-offset-1 col-md-10">
	<div class="ibox float-e-margins animated fadeInDown">
		<div class="ibox-title">
			<button class="pull-right btn btn-primary btn-xs" data-toggle="modal"
						data-target="#myModal">
				<i class="fa fa-plus-circle"></i> Add new topic
			</button>
			<h3 class="font-bold">Create Ticket</h3>
		</div>
		<div class="ibox-content ">

			<form class="m-t form-horizontal createTicket" role="form"
				method="Post" action="/checkAdmin">
				{!! csrf_field() !!}


				<div class="form-group topic">
					<label class="col-md-2 control-label">Topic: &nbsp; </label>
					<div class="col-md-10">
						<select name="topic" class="form-control topic">
							<option value="" selected hidden>Choose a topic...</option>
							@foreach ($topics as $topic)
							<option value="{{$topic->topic_id}}">{{$topic->description}}</option>
							@endforeach
						</select>

					</div>
				</div>
				<div class="form-group subject">
					<label class="col-md-2 control-label">Subject: &nbsp; </label>
					<div class="col-md-10">
						<input type="text" class="form-control"
							placeholder="Enter subject..." name="subject" required>

					</div>
				</div>

				<div class="form-group summary">



					<label class="col-md-2 control-label">Summary:</label>

					<div class="col-md-10">
						<input type="hidden" class="form-control topic" name="summary">

						<div class="ibox-content no-padding">
							<div class="summernote"></div>
						</div>


					</div>
				</div>
				<hr>
				<div class="row">
					<div class="pull-right">
						<button type="button"
							class="btn btn-w-m btn-primary  create-ticket">Create</button>
						<a href="/tickets/" class="btn btn-w-m btn-danger btn-outline ">Cancel</a>

					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Topic</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal borrowItem" id="borrowItem">
				{!! csrf_field() !!}
				
				<div class="form-group">
					<label class="col-md-2 control-label">Topic:</label>
					<div class="col-md-10">
					<input type="text" class="form-control" name="topic">
					</div>
				</div>

				</form>
				
					
				
			</div>

			<div class="modal-footer">
				<button class="ladda-button btn btn-w-m btn-primary borrowItem" type="button">
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
@endsection
