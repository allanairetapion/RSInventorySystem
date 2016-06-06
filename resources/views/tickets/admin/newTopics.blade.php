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
						<form class="topic form-horizontal" method="post">
							{!! csrf_field() !!}
							<div style=" height:180px; overflow-y: scroll; overflow-x: hidden;">
								<table class="table table-striped table-bordered topics">
									<thead>
										<tr>
											<th class="text-center">
											<input type="checkbox" class="topicCB">
											</th>
											<th class="text-center">Description</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($topics as $topic)
										<tr>

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
								<button type="button" class="btn btn-sm btn-warning deleteTopic ">
									Delete Topic
								</button>

								<button type="button" class="btn btn-sm btn-primary updateTopic ">
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
									<input type="text" class="form-control input-sm addTopic" name="topic" placeholder="Type new topic here...">
									<span class="addTopic glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
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
@endsection
