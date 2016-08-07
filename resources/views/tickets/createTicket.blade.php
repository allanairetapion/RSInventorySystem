@extends('tickets.ticketclientlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-1 col-md-10">
		<div class="ibox">
			<div class="ibox-title">
				<h3 class="font-bold">Create Ticket</h3>
			</div>
			<div class="ibox-content">

				<form class="m-t form-horizontal createTicket" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					
					<div class="form-group topic">
						<label class="col-md-2 control-label">Topic: &nbsp; </label>
						<div class="col-md-10">
							<select name="topic"class="form-control topic">
								<option value="" selected hidden> Choose a topic... </option>
								@foreach ($topics as $topic)														
								<option value="{{$topic->topic_id}}"> {{$topic->description}}</option>								
								@endforeach
							</select>
							
						</div>
					</div>
					<div class="form-group subject">
						<label class="col-md-2 control-label">Subject: &nbsp; </label>
						<div class="col-md-10">
							<input type="text" class="form-control" placeholder="Enter subject..." name="subject" required>

						</div>
					</div>

				<div class="form-group summary">

							

							<label class="col-md-2 control-label">Summary:</label>

							<div class="col-md-10" style="padding:0px;border: solid grey 1px;">
								<input type="hidden" class="form-control topic"  rows="5" name="summary">
								
									<div class="summernote">

									</div>


							</div>
						</div>
						<hr>

					<center>
						<button type="button" class="btn btn-info btn-lg create-ticket">
							Create
						</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
