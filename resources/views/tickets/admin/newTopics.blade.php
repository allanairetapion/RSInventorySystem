@extends('tickets.ticketadminlayout') @section('body')
<div class="row">
	<div class="  ibox animated fadeInDown">
		<div class=" col-md-12">
			<div class="ibox">
				<div class="ibox-title">
					

					<div class="pull-right">
						<button type="button" data-style="zoom-in"
							class="ladda-button btn btn-sm btn-info updateTopic ">Update
							Selection</button>
						<button type="button" data-style="zoom-in"
							class="ladda-button btn btn-sm btn-primary" data-toggle="modal"
							data-target="#myModal">
							<i class="fa fa-plus-circle"></i> Add Topic
						</button>
					</div>
					
					<h2 class="font-bold ">Topics</h2>
				</div>
				<div class="ibox-content">
					<form class="topic form-horizontal">
						{!! csrf_field() !!}
						<div>
							<table class="table table-bordered ticket_topics" data-sort="false">
								<thead>
									<tr>
										<th class="text-center"><input type="checkbox" class="topicCB">
										</th>
										<th class="text-center">Description</th>
										<th class="text-center">Priority Level</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>
								<tbody class="topics text-center">
									@foreach ($topics as $topic)
									<tr id="{{$topic->topic_id}}">

										<td>@if ($topic->status == 1) <input class="topic"
											type="checkbox" name="{{$topic->topic_id}}"
											value="{{$topic->topic_id}}" checked> @else <input
											class="topic" type="checkbox" name="{{$topic->topic_id}}"
											value="{{$topic->topic_id}}"> @endif
										</td>

										<td class="topicDescription{{$topic->topic_id}}">
											{{$topic->description}}</td>
										<td class="topicPriority{{$topic->topic_id}}">
											{{$topic->priority_level}}</td>
										<td>
										<div class="btn-group">
                               <button type="button"
												class="btn btn-warning btn-xs editTopic"
												value="{{$topic->topic_id}}">Edit</button>
											
                            </div>
											
										</td>
									</tr>
									@endforeach
								</tbody>
								<tfoot>
						<tr>
							<td colspan="4">
								<ul class="pagination pull-right"></ul>
							</td>
						</tr>
					</tfoot>
							</table>

						</div>



					</form>



				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal" id="editTopic" tabindex="-1" role="dialog"
	aria-hidden="true">
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
			<div class="spiner">
                                <div class="sk-spinner sk-spinner-three-bounce">
                                    <div class="sk-bounce1"></div>
                                    <div class="sk-bounce2"></div>
                                    <div class="sk-bounce3"></div>
                                </div>
                            </div>
				<div class="row">

					<form class="editTopic form-horizontal">
						{!! csrf_field() !!} <input type="hidden" name="editTopic_id"
							class="editTopic_id" value="" />
						<div class="form-group addTopic">
							<label class="col-md-3 control-label">Topic:</label>
							<div class="col-md-9">
								<input type="text" class="form-control input-sm editTopic"
									name="description"> <label
									class="text-danger help-block editTopic"></label>
							</div>

						</div>
						<div class="form-group priority">
							<label class="col-md-3 control-label">Priority Level:</label>
							<div class="col-md-9">
								<select class="form-control editPriority" name="priority">

									<option value="High">High</option>
									<option value="Normal">Normal</option>
									<option value="Low">Low</option>
								</select> <label class="text-danger help-block priority"></label>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white " data-dismiss="modal">
					Cancel</button>
				<button type="button" class="btn btn-primary saveEditTopic">Save</button>
			</div>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog ">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Topic</h4>
			</div>

			<div class="ibox-content">
				<form class="form-horizontal addTopic">

					{!! csrf_field() !!}
					<div class="form-group addTopic">
						<label class="col-md-4 control-label">Topic:</label>
						<div class="col-md-8">
							<input type="text" class="form-control input-sm addTopic"
								name="description"> <label
								class="text-danger help-block addTopic"></label>
						</div>

					</div>
					<div class="form-group priority">
						<label class="col-md-4 control-label">Default Priority Level:</label>
						<div class="col-md-8">
							<select class="form-control" name="priority">
								<option selected value=""></option>
								<option value="High">High</option>
								<option value="Normal">Normal</option>
								<option value="Low">Low</option>
							</select> <label class="text-danger help-block priority"></label>
						</div>
					</div>

				</form>



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-w-m" id="addTopic">Add
					topic</button>
				<button type="button" class="btn btn-w-m btn-danger"
					data-dismiss="modal">
					<strong>Cancel</strong>
				</button>
			</div>
		</div>

	</div>
</div>
<script>
	$(document).ready(function() {
		$('table.ticket_topics').footable();
		$('label.text-danger').hide();
		$('div.spiner').hide();
	}); 
	$('button#addTopic').click(
			function(e) {
				$('span.addTopic').hide();
				$('div.form-group').removeClass(
						'has-error');
				
				$('label.text-danger').hide();

				$.ajax({
							type : "POST",
							url : "/admin/addTopic",
							data : $('form.addTopic').serialize(),
						})
						.done(
								function(data) {

									var msg = "";
									if (data.success == false) {
										if (data.errors['description']) {
											$('div.addTopic').addClass(
													'has-error');
											$('label.addTopic')
													.text(
															'*'
																	+ data.errors['description'])
													.show();
										}
										if (data.errors['priority']) {
											$('div.priority').addClass(
													'has-error');
											$('label.priority')
													.text(
															'*'
																	+ data.errors['priority'])
													.show();
										}

									} else {
										$('form.addTopic').trigger(
												'reset');
										var html;
										var table = $('table.ticket_topics').data('footable');
										

										html += "<tr id=" + data.response['topic_id'] + "><td class='text-center'><input class='topic' type='checkbox' name ="
																	+ data.response['topic_id']
																	+ " value="
																	+ data.response['topic_id']
																	+ " checked></td>"
																	+ "<td>"
																	+ data.response['description']
																	+ "</td>"
																	+ "<td class='text-center'>"
																	+ data.response['priority_level']
																	+ "</td>"
																	+ "<td><button type='button' class='btn btn-warning btn-xs editTopic' value="
																	+ data.response['topic_id']
																	+ ">Edit</button> </td></tr>";
														
										$('tbody.topics').append(html);
										$('table').trigger('footable_redraw');
										$('div#myModal').modal('hide');
						
										toastr.success('New Topic has been added.');

									}
								});

			});

</script>
@endsection
