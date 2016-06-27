@extends('tickets.ticketadminlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-2 col-md-8">
		<div class="ibox">
			<div class="ibox-title text-center">
				<h3 class="font-bold ">Edit Topic</h3>
			</div>
			<div class="ibox-content">

				<div class = "panel panel-default">
					<div class = "panel-heading">
						<h4 class = "panel-title">Choose Topics:</h4>
					</div>

					<div class = "panel-body">
						<form class="topic form-horizontal">
							{!! csrf_field() !!}
							<div style=" height:180px; overflow-y: scroll; overflow-x: hidden;">
								<table class="table table-striped table-bordered ">
									<thead>
										<tr>
											<th class="text-center">
											<input type="checkbox" class="topicCB">
											</th>
											<th class="text-center">Description</th>
										</tr>
									</thead>
									<tbody class="topics">
										@foreach ($topics as $topic)
										<tr id="{{$topic->topic_id}}">

											<td class="text-center"> @if ($topic->status == 1)
											<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}" checked>
											@else
											<input class="topic" type="checkbox" name ="{{$topic->topic_id}}" value="{{$topic->topic_id}}">
											@endif </td>

											<td class="text-center"> {{$topic->description}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>

							</div>

							<br>
							<div class="pull-right">
								<button type="button" data-style="zoom-in" class="btn btn-sm btn-warning deleteTopic ">
									Delete Topic
								</button>

								<button type="button" data-style="zoom-in" class="ladda-button btn btn-sm btn-primary updateTopic ">
									Update Selection
								</button>
								

							</div>
							<br>
						</form>
					</div>
				</div>
				<div class = "panel panel-default">
					<div class = "panel-heading">
						<h4 class="panel-title">Add Another Topic:</h4>
					</div>

					<div class = "panel-body">
						<form class="form-horizontal addTopic" >
							
							{!! csrf_field() !!}
							<div class="form-group addTopic">
								<div class="col-md-8">
									<input type="text" class="form-control input-sm addTopic" name="description" placeholder="Type new topic here...">
									
									<label class="text-danger addTopic"></label>
								</div>
								<div class="col-md-4">
									<button type="button" class=" btn btn-primary btn-block btn-sm addTopic">
										Add topic
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('label.addTopic').hide();
	});
</script>
@endsection
