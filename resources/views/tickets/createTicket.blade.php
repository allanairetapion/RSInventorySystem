@extends('tickets.ticketclientlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-1 col-md-10">
		<div class="ibox">
			<div class="ibox-title">
				<h2 class="font-bold">Ticket Form</h2>
			</div>
			<div class="ibox-content">

				<form class="m-t form-horizontal createTicket" role="form" method="Post" action="/checkAdmin">
					{!! csrf_field() !!}

					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label class="col-md-2 control-label">Email:&nbsp;</label>
						<div class="col-md-10">
							<input type="email" class="form-control email" placeholder="Email Address"  disabled name="Email" value="{{ Auth::guard('user')->user()->email   }}" required="">
						</div>
					</div>
					<div class="form-group ">
						<label class="col-md-2 control-label">Name:&nbsp;</label>
						<div class="col-md-10">
							<input type="text" disabled class="form-control"  name="Fullname"
							value="{{ Auth::guard('user')->user()->clientProfile ? Auth::guard('user')->user()->clientProfile->first_name.' '.Auth::guard('user')->user()->clientProfile->last_name : '' }}" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Department:&nbsp;</label>
						<div class="col-md-10">
							<input type="text" disabled class="form-control email" placeholder="Email Address" name="department" value="{{ Auth::guard('user')->user()->department   }}" required="">

						</div>
					</div>
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
