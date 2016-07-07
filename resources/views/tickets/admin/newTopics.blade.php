@extends('tickets.ticketadminlayout')
@section('body')
<div class="row">
	<div class="  ibox animated fadeInDown">
		<div class=" col-md-12">
			<div class="ibox">
				<div class="ibox-title text-center">
					<h3 class="font-bold ">Topic</h3>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-md-6">
							<div class = "panel panel-default">
								<div class = "panel-heading">
									<div class="pull-right">
											<button type="button" data-style="zoom-in" class="ladda-button btn btn-xs btn-primary updateTopic ">
												Update Selection
											</button>

										</div>
									<h4 class = "panel-title">Choose Topics:</h4>
									
									
								</div>

								<div class = "panel-body">
									<form class="topic form-horizontal">
										{!! csrf_field() !!}
										<div>
											<table class="table table-striped table-bordered ticket_topics">
												<thead>
													<tr>
														<th class="text-center">
														<input type="checkbox" class="topicCB">
														</th>
														<th class="text-center">Description</th>
														<th class="text-center">Priority Level</th>
														<th class="text-center">Actions</th>
													</tr>
												</thead>
												<tbody class="topics text-center">
													@foreach ($topics as $topic)
													<tr id="{{$topic->topic_id}}">

														<td> @if ($topic->status == 1)
														<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}" checked>
														@else
														<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}">
														@endif </td>

														<td class="topicDescription{{$topic->topic_id}}"> {{$topic->description}}</td>
														<td class="topicPriority{{$topic->topic_id}}"> {{$topic->priority_level}}</td>
														<td>
														<button type="button" class="btn btn-warning btn-xs editTopic" value="{{$topic->topic_id}}">
															Edit
														</button>
														<button type="button" class="btn btn-danger btn-xs deleteTopic"  value="{{$topic->topic_id}}">
															Delete
														</button></td>
													</tr>
													@endforeach
												</tbody>
											</table>

										</div>

										
										
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-6">

							<div class = "panel panel-default">
								<div class = "panel-heading">
									<h4 class="panel-title">Add New Topic:</h4>
								</div>

								<div class = "panel-body">
									<form class="form-horizontal addTopic" >

										{!! csrf_field() !!}
										<div class="form-group addTopic">
											<label class="col-md-3 control-label">Topic:</label>
											<div class="col-md-9">
												<input type="text" class="form-control input-sm addTopic" name="description">
												<label class="text-danger help-block addTopic"></label>
											</div>

										</div>
										<div class="form-group priority">
											<label class="col-md-3 control-label">Priority Level:</label>
											<div class="col-md-9">
												<select class="form-control" name="priority">
													<option selected value="">Select Priority Level...</option>
													<option value="High">High</option>
													<option value="Normal">Normal</option>
													<option value="Low">Low</option>
												</select>
												<label class="text-danger help-block priority"></label>
											</div>
										</div>
										<hr/>
										<button type="button" class="btn btn-primary btn-sm pull-right addTopic">
											Add topic
										</button>
									</form>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal" id="editTopic" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated fadeIn">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<i class="fa fa-edit modal-icon"></i>
				<h4 class="modal-title">Edit Topic:</h4>

			</div>
			<div class="modal-body">				
				<div class="row">

					<form class="editTopic form-horizontal">
						{!! csrf_field() !!}
						<input type="hidden" name="editTopic_id" class="editTopic_id" value="" />
						<div class="form-group addTopic">
							<label class="col-md-3 control-label">Topic:</label>
							<div class="col-md-9">
								<input type="text" class="form-control input-sm editTopic" name="description">
								<label class="text-danger help-block editTopic"></label>
							</div>

						</div>
						<div class="form-group priority">
							<label class="col-md-3 control-label">Priority Level:</label>
							<div class="col-md-9">
								<select class="form-control editPriority" name="priority">

									<option value="High">High</option>
									<option value="Normal">Normal</option>
									<option value="Low">Low</option>
								</select>
								<label class="text-danger help-block priority"></label>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white " data-dismiss="modal">
					Cancel
				</button>
				<button type="button" class="btn btn-primary saveEditTopic" >
					Save
				</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('label.text-danger').hide();
	}); 
</script>
@endsection
